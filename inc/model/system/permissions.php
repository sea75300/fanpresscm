<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

/**
 * Permission handler Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class permissions extends \fpcm\model\abstracts\dataset {

    /**
     * Rollen-ID
     * @var int
     */
    protected $rollid;

    /**
     * Berechtigungsdaten
     * @var array
     */
    protected $permissiondata = [];

    /**
     * Berechtigungsdaten - bereits geprüft
     * @var array
     */
    protected $checkedData = [];

    /**
     * Nicht in Datenbank zu speichernde Daten
     * @var array
     */
    protected $dbExcludes = ['defaultPermissions', 'permissionSet', 'checkedData'];

    /**
     * Standard-Berechtigungsset für Anlegen einer neuen Gruppe
     * @var array
     */
    protected $defaultPermissions = [
        'article' => array(
            'add' => 1,
            'edit' => 1,
            'editall' => 0,
            'delete' => 0,
            'archive' => 0,
            'approve' => 0,
            'revisions' => 0,
            'authors' => 0,
            'massedit' => 0
        ),
        'comment' => array(
            'edit' => 1,
            'editall' => 0,
            'delete' => 0,
            'approve' => 1,
            'private' => 1,
            'move' => 0,
            'massedit' => 0
        ),
        'system' => array(
            'categories' => 0,
            'options' => 0,
            'users' => 0,
            'rolls' => 0,
            'permissions' => 0,
            'templates' => 0,
            'smileys' => 0,
            'update' => 0,
            'logs' => 0,
            'crons' => 0,
            'backups' => 0,
            'wordban' => 0,
            'ipaddr' => 0
        ),
        'modules' => array(
            'install' => 0,
            'uninstall' => 0,
            'configure' => 0
        ),
        'uploads' => array(
            'visible' => 1,
            'add' => 1,
            'delete' => 0,
            'thumbs' => 1,
            'rename' => 0
        ),
    ];

    /**
     * Standard-Berechtigungsset beim Aktualisieren der Brechtigungen
     * @var array
     */
    protected $permissionSet = [
        'article' => array(
            'add' => 0,
            'edit' => 0,
            'editall' => 0,
            'delete' => 0,
            'archive' => 0,
            'approve' => 0,
            'revisions' => 0,
            'authors' => 0,
            'massedit' => 0
        ),
        'comment' => array(
            'edit' => 0,
            'editall' => 0,
            'delete' => 0,
            'approve' => 0,
            'private' => 0,
            'move' => 0,
            'massedit' => 0
        ),
        'system' => array(
            'categories' => 0,
            'options' => 0,
            'users' => 0,
            'rolls' => 0,
            'permissions' => 0,
            'templates' => 0,
            'smileys' => 0,
            'update' => 0,
            'logs' => 0,
            'crons' => 0,
            'backups' => 0,
            'wordban' => 0,
            'ipaddr' => 0
        ),
        'modules' => array(
            'install' => 0,
            'uninstall' => 0,
            'configure' => 0
        ),
        'uploads' => array(
            'visible' => 0,
            'add' => 0,
            'delete' => 0,
            'thumbs' => 0,
            'rename' => 0
        ),
    ];

    /**
     * Konstruktor
     * @param int $rollid ID der Benutzerrolle
     * @return void
     */
    public function __construct($rollid = 0)
    {
        $this->table = \fpcm\classes\database::tablePermissions;
        $this->cacheName = 'system/permissioncache' . $rollid;

        parent::__construct();

        if (!$rollid) {
            return;
        }

        $this->rollid = $rollid;
        $this->init();
    }

    /**
     * Rollen-ID auslesen
     * @return int
     */
    function getRollId()
    {
        return $this->rollid;
    }

    /**
     * Berechtigungsdaten auslesen
     * @return array
     */
    public function getPermissionData()
    {
        if (is_array($this->permissiondata)) {
            return $this->permissiondata;
        }

        return json_decode($this->permissiondata, true);
    }

    /**
     * Rollen-ID setzen
     * @param array $rollid
     */
    public function setRollId($rollid)
    {
        $this->rollid = $rollid;
    }

    /**
     * Berechtigungsdaten setzen
     * @param array $permissiondata
     */
    public function setPermissionData(array $permissiondata)
    {
        $this->permissiondata = json_encode(array_merge($this->permissionSet, $permissiondata));
    }

    /**
     * Berechtigungen initialisieren
     * @return void
     */
    public function init()
    {
        if (!$this->cache->isExpired($this->cacheName)) {
            $this->permissiondata = $this->cache->read($this->cacheName);
            return true;
        }

        $data = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams($this->table))
                ->setWhere('rollid = ?')
                ->setParams([$this->rollid])
        );
        
        if (!is_object($data)) {
            return false;
        }

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        $this->permissiondata = json_decode($this->permissiondata, true);
        $this->cache->write($this->cacheName, $this->permissiondata, $this->config->system_cache_timeout);
    }

    /**
     * Speichert einen neuen Rechte-Datensatz in der Datenbank
     * @return bool
     */
    public function save()
    {
        if (!($this->events instanceof \fpcm\events\events)) {
            $this->events = \fpcm\classes\loader::getObject('\fpcm\events\events');
        }
        
        return parent::save() === false ? false : true;
    }

    /**
     * Aktualisiert einen Rechte-Datensatz in der Datenbank
     * @return bool
     */
    public function update()
    {
        if (!($this->events instanceof \fpcm\events\events)) {
            $this->events = \fpcm\classes\loader::getObject('\fpcm\events\events');
        }

        $params = $this->getPreparedSaveParams();
        $params = $this->events->trigger($this->getEventName('update'), $params);

        $fields = array_keys($params);

        $params[] = $this->getRollId();

        $return = false;
        if ($this->dbcon->update($this->table, $fields, array_values($params), 'rollid = ?')) {
            $return = true;
        }

        $this->cache->cleanup();
        $this->init();

        return $return;
    }

    /**
     * Löschen Berechtigungsdatensatz aus DB
     * @return bool
     */
    public function delete()
    {
        $this->dbcon->delete($this->table, 'rollid = ?', [$this->rollid]);
        $this->cache->cleanup();

        return true;
    }

    /**
     * Prüft ob Benutzer Berechtigung hat
     * @param array $permissionArray
     * @return bool
     */
    public function check(array $permissionArray) : bool
    {
        if (!count($this->permissiondata)) {
            return false;
        }

        $res = true;

        $permissionArrayHash = \fpcm\classes\tools::getHash(json_encode($permissionArray));
        if (isset($this->checkedData[$permissionArrayHash])) {
            return $this->checkedData[$permissionArrayHash];
        }

        $permissionArray = \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('permission\check', $permissionArray);
        foreach ($permissionArray as $module => $permission) {

            if (!isset($this->permissiondata[$module])) {
                trigger_error("No permissions available for module \"{$module}\" and roll \"{$this->rollid}\"!". PHP_EOL.
                              "   > Permission-Debug: ".PHP_EOL.(is_array($permission) ? implode(PHP_EOL, $permission) : $permission) );
                return false;
            }

            $check = false;
            if (is_array($permission)) {

                foreach ($permission as $permissionItem) {
                    $check = isset($this->permissiondata[$module][$permissionItem]) ? $this->permissiondata[$module][$permissionItem] : false;
                    if ($check) {
                        break;
                    }
                }
            } else {
                $check = isset($this->permissiondata[$module][$permission]) ? (bool) $this->permissiondata[$module][$permission] : false;
            }

            $res = $res && $check;
        }

        $this->checkedData[$permissionArrayHash] = $res;
        return $res;
    }

    /**
     * Initialisiert Berechtigungen mit Standardwerten
     * @param int $rollid
     * @return bool
     */
    public function addDefault($rollid)
    {
        $this->setRollId($rollid);
        $this->setPermissionData($this->defaultPermissions);

        return $this->save();
    }

    /**
     * Gibt leeren Standard-Berechtigungsset zurück
     * @return array
     */
    public function getPermissionSet()
    {
        return $this->permissionSet;
    }

    /**
     * Gibt Array mit allen Berechtigungsdatensätzen zurück
     * @return array
     */
    public function getPermissionsAll()
    {
        $datasets = $this->dbcon->fetch($this->dbcon->select($this->table, 'rollid, permissiondata'));

        $res = [];
        foreach ($datasets as $dataset) {
            $res[$dataset->rollid] = json_decode($dataset->permissiondata, true);
        }

        return \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('permission\getAll', $res);
    }

    /**
     * Magic get
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        if ($name == 'permissionData') {
            return $this->permissiondata;
        }

        parent::__get($name);
    }

    /**
     * Magic set
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if ($name == 'permissionData') {
            $this->permissiondata = $value;
            return true;
        }

        parent::__set($name, $value);
    }

    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::getEventModule
     * @return string
     * @since FPCM 4.1
     */
    protected function getEventModule(): string
    {
        return 'permission';
    }

    /**
     * Is triggered after successfull database insert
     * @see \fpcm\model\abstracts\dataset::afterSaveInternal
     * @return bool
     * @since FPCM 4.1
     */
    protected function afterSaveInternal(): bool
    {
        $this->cache->cleanup();
        return true;
    }

}
