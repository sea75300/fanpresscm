<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions;

/**
 * Permissions handler object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
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
     * Article permissions
     * @var items\article 
     */
    public $article;

    /**
     * Comment permissions
     * @var items\comment
     */
    public $comment;

    /**
     * Common system permissions
     * @var items\system
     */
    public $system;

    /**
     * Module manager permissions
     * @var items\modules
     */
    public $modules;

    /**
     * Filemanager permissions
     * @var items\uploads
     */
    public $uploads;

    /**
     * Exclude items from databse save
     * @var array
     */
    protected $dbExcludes = ['checkedData', 'article', 'comment', 'system', 'modules', 'uploads'];

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

        $sessObj = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        if (!$rollid && $sessObj->exists()) {
            $rollid = $sessObj->getCurrentUser()->getRoll();
        }

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
        $this->permissiondata = json_encode(array_merge(sets::getAllFalse(), $permissiondata));
    }

    /**
     * Berechtigungen initialisieren
     * @return void
     */
    public function init()
    {
        $data = $this->dbcon->selectFetch( (new \fpcm\model\dbal\selectParams($this->table))->setWhere('rollid = ?')->setParams([$this->rollid]) );

        $this->id = $data->id;
        $this->rollid = $data->rollid;
        $this->permissiondata = json_decode($data->permissiondata, true);

        if (!is_array($this->permissiondata)) {
            return false;
        }

        $this->initItems();
        return true;
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
     * Initialisiert Berechtigungen mit Standardwerten
     * @param int $rollid
     * @return bool
     */
    public function addDefault($rollid)
    {
        $this->setRollId($rollid);
        $this->setPermissionData(sets::getDefault());
        return $this->save();
    }

    /**
     * Gibt leeren Standard-Berechtigungsset zurück
     * @return array
     */
    public function getPermissionSet()
    {
        return sets::getAllFalse();
    }

    /**
     * Gibt Array mit allen Berechtigungsdatensätzen zurück
     * @return array
     */
    public function getPermissionsAll()
    {
        $datasets = $this->dbcon->selectFetch( (new \fpcm\model\dbal\selectParams($this->table))->setFetchAll(true) );

        $res = [];
        foreach ($datasets as $dataset) {
            $res[$dataset->rollid] = json_decode($dataset->permissiondata, true);
        }

        return \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('permission\getAll', $res);
    }

    /**
     * User has comment editing permissions
     * @return bool
     */
    public function editArticles() : bool
    {
        return $this->article->edit || $this->article->editall;
    }

    /**
     * User has comment editing permissions
     * @return bool
     */
    public function editArticlesMass() : bool
    {
        if (!$this->article->massedit) {
            return false;
        }
        
        return $this->editArticles();
    }

    /**
     * User has comment editing permissions
     * @return bool
     */
    public function editComments() : bool
    {
        return $this->editArticles() && ($this->comment->edit || $this->comment->editall);
    }

    /**
     * User has comment editing permissions
     * @return bool
     */
    public function editCommentsMass() : bool
    {
        if (!$this->comment->massedit) {
            return false;
        }

        return $this->editComments();
    }

    /**
     * User has permissions for article trash
     * @return bool
     */
    public function articleTrash() : bool
    {
        if (!$this->article->edit && !$this->article->editall) {
            return false;
        }

        return $this->article->delete;
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
     * Init permission object items
     * @return bool
     * @since 4.4
     */
    final protected function initItems()
    {
        if (!is_array($this->permissiondata)) {
            return false;
        }
        
        foreach ($this->permissiondata as $key => $value) {
            $className = "\\fpcm\\model\\permissions\items\\{$key}";
            $this->$key = new $className($value);
        }
        
        return true;
    }

    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::getEventModule
     * @return string
     * @since 4.1
     */
    protected function getEventModule(): string
    {
        return 'permission';
    }

    /**
     * Is triggered after successful database insert
     * @see \fpcm\model\abstracts\dataset::afterSaveInternal
     * @return bool
     * @since 4.1
     */
    protected function afterSaveInternal(): bool
    {
        $this->cache->cleanup();
        return true;
    }

    /**
     * Prüft ob Benutzer Berechtigung hat
     * @param array $permissionArray
     * @return bool
     * @deprecated since version FPCM 4.4
     */
    final public function check(array $permissionArray) : bool
    {
        trigger_error('Method "check" or permissions objects of instance \\fpcm\\model\\system\\permissions are deprecated. Use \\fpcm\\model\\permissions\\permissions instead', E_USER_DEPRECATED);
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
}
