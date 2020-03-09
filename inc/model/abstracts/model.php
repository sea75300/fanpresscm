<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * Model base object
 * 
 * @package fpcm\model\abstracts
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class model implements \fpcm\model\interfaces\model {

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
    protected $data;

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
     * @since FPCM 3.6
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
     * Cache name
     * @var string
     */
    protected $cacheModule = '';

    /**
     * Konstruktor
     * @param int $id
     * @return void
     */
    public function __construct($id = null)
    {
        $this->dbcon = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->events = \fpcm\classes\loader::getObject('\fpcm\events\events');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');

        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return false;
        }

        $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language');
        $this->notifications = \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications');

        if (is_object($this->config)) {
            $this->config->setUserSettings();
        }

        if ($id === null) {
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
        return isset($this->data[$name]) ? $this->data[$name] : false;
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
     * Magische Methode für nicht vorhandene Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {
        print "Function '{$name}' not found in " . get_class($this) . '<br>';
        return false;
    }

    /**
     * Magische Methode für nicht vorhandene, statische Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     */
    public static function __callStatic($name, $arguments)
    {
        print "Static function '{$name}' not found in " . get_called_class() . '<br>';
        return false;
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
        $data = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))->setWhere('id = ?')->setParams([$this->id]));
        if (!$data) {
            trigger_error('Failed to load data for object of type "' . get_class($this) . '" with given id ' . $this->id . '!');
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
     * Löscht ein Objekt in der Datenbank
     * @return bool
     */
    public function delete()
    {
        $this->dbcon->delete($this->table, 'id = ?', array($this->id));
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

        if (!is_object($object))
            return false;

        $keys = array_keys($this->getPreparedSaveParams());
        $keys[] = 'id';

        foreach ($keys as $key) {
            if (!isset($object->$key))
                continue;
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
        unset(
                $params['cache'], $params['config'], $params['dbcon'], $params['events'], $params['session'], $params['id'], $params['nodata'], $params['system'], $params['table'], $params['dbExcludes'], $params['language'], $params['editAction'], $params['objExists'], $params['cacheName'], $params['cacheModule'], $params['wordbanList'], $params['notifications']
        );

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
     * @since FPCM 3.6
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
        return \fpcm\classes\dirs::getRootUrl("index.php?module={$this->editAction}" . $this->id);
    }

}
