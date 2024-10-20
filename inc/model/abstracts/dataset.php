<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

use fpcm\classes\baseconfig;
use fpcm\classes\dirs;
use fpcm\classes\loader;
use fpcm\events\abstracts\event;
use fpcm\model\dbal\selectParams;

/**
 * Model base object
 *
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class dataset implements \fpcm\model\interfaces\dataset, \Stringable {

    /**
     * DB-Verbindung
     * @var \fpcm\classes\database
     */
    protected $dbcon;

    /**
     * Objekt-ID
     * @var int
     */
    protected $id;

    /**
     * Tabellen-Name
     * @var string
     */
    protected $table;

    /**
     * data-Array für nicht weiter definierte Eigenschaften
     * @var array
     */
    protected $data = [];

    /**
     * Eigenschaften, welche beim Speichern in DB nicht von getPreparedSaveParams() zurückgegeben werden sollen
     * @var array
     */
    protected $dbExcludes = [];

    /**
     * $this->data beim Speichern nicht berücksichtigen
     * @var bool
     */
    protected $nodata = true;

    /**
     * System-Cache
     * @var \fpcm\classes\cache
     */
    protected $cache;

    /**
     * System-Session
     * @var \fpcm\model\system\session
     */
    protected $session;

    /**
     * Event-Liste
     * @var \fpcm\events\events
     */
    protected $events;

    /**
     * System-Config-Objekt
     * @var \fpcm\model\system\config
     */
    protected $config;

    /**
     * System-Sprachen-Objekt
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     * Notifications
     * @var \fpcm\model\theme\notifications
     * @since 3.6
     */
    protected $notifications;

    /**
     * Controller-Pfad, wenn Objekt Edit-Action besitzt
     * @var string
     */
    protected $editAction;

    /**
     * Objektexistiert
     * @var bool
     */
    protected $objExists = false;

    /**
     * Cache name
     * @var string
     */
    protected $cacheName = false;

    /**
     * Konstruktor
     * @param int $id
     * @return void
     */
    public function __construct($id = null)
    {
        if (method_exists($this, 'getTableName')) {
            $this->getTableName();
        }

        $this->dbcon = loader::getObject('\fpcm\classes\database');
        $this->events = loader::getObject('\fpcm\events\events');
        $this->cache = loader::getObject('\fpcm\classes\cache');
        $this->config = loader::getObject('\fpcm\model\system\config');

        if (baseconfig::installerEnabled()) {
            return;
        }

        $this->language = loader::getObject('\fpcm\classes\language');
        $this->notifications = loader::getObject('\fpcm\model\theme\notifications');

        if (is_object($this->config)) {
            $this->config->setUserSettings();
        }

        if (is_null($id)) {
            return;
        }

        $this->id = (int) $id;
        $this->init();
    }

    /**
     * Magic get
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name] ?? false;
    }

    /**
     * Magic set
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Magic string
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getPreparedSaveParams());
    }

    /**
     * Konstruktor
     * @return void
     */
    public function __destruct()
    {
        $this->dbcon = false;
        $this->data = null;
        $this->cache = null;
        $this->events = null;
        return;
    }

    /**
     * Gibt Inhalt von "data" zurück
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Inittiert Objekt mit Daten aus der Datenbank, sofern ID vergeben wurde
     */
    public function init()
    {
        $data = $this->dbcon->selectFetch((new selectParams($this->table))->setWhere('id = :id')->setParams(['id' => $this->id]));
        if (!$data) {
            trigger_error('Failed to load data for object of type "' . static::class . '" with given id ' . $this->id . '!', E_USER_WARNING);
            return false;
        }

        $this->objExists = true;

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Gibt Object-ID zurück
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dataset id
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Prüft ob Objekt existiert
     * @return bool
     */
    public function exists()
    {
        return $this->objExists;
    }

    /**
     * Prüft, ob "data" gespeichert werden soll
     * @return bool
     */
    function getNodata()
    {
        return $this->nodata;
    }

    /**
     * Möglichkeit, "data"-Eigenschaft mit an Datenbank zu senden
     * @param bool $nodata
     */
    function setNodata($nodata)
    {
        $this->nodata = $nodata;
    }

    /**
     * Executes save process to database and events
     * @return bool|int
     * @since 4.1
     */
    public function save()
    {
        if (method_exists($this, 'removeBannedTexts')) {
            $this->removeBannedTexts();
        }

        if (!$this->dbcon->insert(
                        $this->table, $this->events->trigger(
                                $this->getEventName('save'),
                                $this->getPreparedSaveParams()
                        )->getData()
                )
        ) {
            return false;
        }

        $this->id = $this->dbcon->getLastInsertId();

        $this->afterUpdateInternal();

        $afterEvent = $this->getEventName('saveAfter');
        if (class_exists(event::getEventNamespace($afterEvent))) {
            $this->events->trigger($afterEvent, $this->id)->getData();
        }

        return $this->id;
    }

    /**
     * Executes update process to database and events
     * @return bool|int
     * @since 4.1
     */
    public function update()
    {
        if (method_exists($this, 'removeBannedTexts')) {
            $this->removeBannedTexts();
        }

        $params = $this->getPreparedSaveParams();
        $fields = array_keys($params);

        $params[] = $this->getId();
        $params = $this->events->trigger($this->getEventName('update'), $params)->getData();

        $return = false;
        if ($this->dbcon->update(
                $this->table,
                $fields,
                array_values($params),
                'id = ?'
            )
        ) {
            $return = true;
        }

        $this->afterUpdateInternal();

        $afterEvent = $this->getEventName('updateAfter');
        if (class_exists(event::getEventNamespace($afterEvent))) {
            $this->events->trigger($afterEvent, $this->id)->getData();
        }

        return $return;
    }

    /**
     * Löscht ein Objekt in der Datenbank
     * @return bool
     */
    public function delete()
    {
        $this->dbcon->delete($this->table, 'id = ?', [$this->id]);
        $this->cache->cleanup();
        return true;
    }

    /**
     * Füllt Objekt mit Daten aus Datenbank-Result
     * @param object $object
     * @return bool
     */
    public function createFromDbObject($object)
    {
        if (!is_object($object)) {
            return false;
        }

        $keys = array_keys($this->getPreparedSaveParams());
        $keys[] = 'id';

        foreach ($keys as $key) {

            if (!isset($object->$key)) {
                continue;
            }

            $this->$key = $object->$key;
        }

        $this->objExists = true;

        return true;
    }

    /**
     * Bereitet Eigenschaften des Objects zum Speichern ind er Datenbank vor und entfernt nicht speicherbare Eigenschaften
     * @return array
     */
    protected function getPreparedSaveParams()
    {
        $params = get_object_vars($this);
        unset($params['cache'], $params['config'], $params['dbcon'], $params['events'], $params['session'], $params['id'], $params['nodata'], $params['system'], $params['table'], $params['dbExcludes'], $params['language'], $params['editAction'], $params['objExists'], $params['cacheName'], $params['cacheModule'], $params['wordbanList'], $params['notifications']);

        if ($this->nodata) {
            unset($params['data']);
        }

        if (!count($this->dbExcludes)) {
            return $params;
        }

        return array_diff_key($params, array_flip($this->dbExcludes));
    }

    /**
     * Gibt array mit Values für Prepared Statements zurück
     * @param int $count
     * @return int
     */
    public function getPreparedValueParams($count = false)
    {

        if ($count === false) {
            $count = count($this->getPreparedSaveParams());
        }

        return array_fill(0, (int) $count, '?');
    }

    /**
     * Bereitet Daten für Speicherung in Datenbank vor
     * @return bool
     * @since 3.6
     */
    public function prepareDataSave()
    {
        return true;
    }

    /**
     * Gibt Link für Edit-Action zurück
     * @return string
     */
    public function getEditLink()
    {
        return dirs::getRootUrl('index.php?module=' . $this->editAction . $this->id);
    }

    /**
     * Returns full event name string
     * @param string $event
     * @return string
     * @since 4.1
     */
    final protected function getEventName(string $event) : string
    {
        return $this->getEventModule() . '\\' . $event;
    }

    /**
     * Returns event base string
     * @return string
     * @since 4.1
     */
    abstract protected function getEventModule() : string;

    /**
     * Is triggered after successful database insert
     * @return bool
     * @since 4.1
     */
    protected function afterSaveInternal() : bool
    {
        return true;
    }

    /**
     * Is triggered after successful database update
     * @return bool
     * @since 4.1
     */
    protected function afterUpdateInternal() : bool
    {
        return true;
    }
}
