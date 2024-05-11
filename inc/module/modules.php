<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Modules list
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\module
 */
class modules extends \fpcm\model\abstracts\tablelist {

    /**
     * Enabled modules cache
     * @var array
     */
    private $enabledCache;

    /**
     * Module key cache
     * @var array
     */
    private $keyCache;

    /**
     * Konstruktor
     * @return bool
     */
    public function __construct()
    {
        $this->table = \fpcm\classes\database::tableModules;
        $this->dbcon = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        
        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return false;
        }

        $this->events = \fpcm\classes\loader::getObject('\fpcm\events\events');
        $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
    }

    /**
     * Module keys from database
     * @return array
     */
    public function getKeysFromDatabase() : array
    {
        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return [];
        }
        
        if (is_array($this->keyCache)) {
            return $this->keyCache;
        }

        $this->keyCache = [];

        $result = $this->dbcon->selectFetch( (new \fpcm\model\dbal\selectParams($this->table))->setItem('mkey')->setFetchAll(true) );
        if (!$result) {
            return $this->keyCache;
        }

        foreach ($result as $dataset) {
            $this->keyCache[] = $dataset->mkey;
        }

        return $this->keyCache;
    }
    
    /**
     * Fetch modules from database
     * @param type $sort
     * @return array
     */
    public function getFromDatabase($sort = false) : array
    {
        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return [];
        }

        $where = $sort ? '1=1 '.$this->dbcon->orderBy(['installed DESC, active DESC, mkey ASC']) : '';
        $result = $this->dbcon->selectFetch( (new \fpcm\model\dbal\selectParams($this->table))->setFetchAll(true)->setWhere($where) );
        if (!$result) {
            return [];
        }

        $modules = [];
        foreach ($result as $dataset) {
            $this->createResult($dataset, $modules);
        }

        return $modules;
    }

    /**
     * Fetch installed modules from database
     * @return array
     */
    public function getInstalledDatabase() : array
    {
        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return [];
        }

        $result = $this->dbcon->selectFetch( (new \fpcm\model\dbal\selectParams($this->table))->setWhere('installed = 1')->setFetchAll(true) );        
        if (!$result) {
            return [];
        }

        $modules = [];
        foreach ($result as $dataset) {
            $this->createResult($dataset, $modules);
        }

        return $modules;
    }

    /**
     * Get installed modules with updates
     * @return array
     */
    public function getInstalledUpdates() : array
    {
        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return [];
        }

        $installed = $this->getInstalledDatabase();
        if (!count($installed)) {
            return [];
        }

        $list = [];
        foreach ($installed as $key => $module) {
            
            if (!$module->hasUpdates()) {
                continue;
            }
            
            $list[] = $key;
            
        }

        return $list;
    }

    /**
     * Fetch module data from repository
     * @return array
     */
    public function getFromRepository() : array
    {
        $repoData = (new \fpcm\model\updater\modules())->getData();
        
        if (!is_array($repoData) || !count($repoData)) {
            return [];
        }
        
        $modules = [];
        foreach ($repoData as $key => $value) {
            
            $module = new repoModule($key, false);
            $module->createFromRepoArray([
                'name' => $value['name'],
                'description' => isset($value['description']) ? $value['description'] : '',
                'version' => isset($value['version']) ? $value['version'] : '',
                'author' => isset($value['author']) ?$value['author'] : '',
                'link' => isset($value['link']) ?$value['link'] : '',
                'requirements' => isset($value['requirements']) ? $value['requirements'] : []
            ]);
            
            $modules[$key] = $module;
        }

        return $modules;
    }

    /**
     * Fetch modul data from file system
     * @return bool
     */
    public function updateFromFilesystem() : bool
    {
        $folders = glob(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, '*/*'), GLOB_ONLYDIR);
        if (!$folders) {
            return true;
        }

        $dbList = $this->getFromDatabase();
        foreach ($folders as $folder) {
            $key = module::getKeyFromPath($folder);
            $module = new module( $key, false );
            if (isset($dbList[$key])) {
                continue;
            }

            if (!$module->addModule()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Fetch installed and enabled modules from database
     * @return array
     */
    public function getEnabledDatabase() : array
    {
        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return [];
        }

        if (is_array($this->enabledCache)) {
            return $this->enabledCache;
        }
        
        $cacheName = 'modules/'. __FUNCTION__;
        
        if (!$this->cache->isExpired($cacheName)) {
            $this->enabledCache = $this->cache->read($cacheName);
            return is_array($this->enabledCache) ? $this->enabledCache : [];
        }

        $this->enabledCache = [];
        $result = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams())->setTable($this->table)->setItem('mkey')->setWhere('installed = 1 AND active = 1')->setFetchAll(true));
        if (!$result) {
            $this->cache->write($cacheName, []);
            return $this->enabledCache;
        }

        foreach ($result as $dataset) {
            $this->enabledCache[] = $dataset->mkey;
        }
        
        $this->cache->write($cacheName, $this->enabledCache);
        return $this->enabledCache;
    }

    /**
     * Create module result
     * @param object $dataset
     * @param array $modules
     * @return bool
     */
    private function createResult($dataset, array &$modules) : bool
    {
        $module = new module($dataset->mkey, false);
        $module->createFromDbObject($dataset);
        $modules[$dataset->mkey] = $module;
        return true;
    }

}
