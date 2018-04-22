<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\modules;

/**
 * Modules list
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\modules
 */
class modules extends \fpcm\model\abstracts\tablelist {

    public function __construct()
    {
        $this->table = \fpcm\classes\database::tableModules;
        parent::__construct();
    }

    /**
     * 
     * @return array
     */
    public function getFromDatabase()
    {
        $result = $this->dbcon->fetch($this->dbcon->select($this->table, '*'), true);
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
     * 
     * @return array
     */
    public function getInstalledDatabase()
    {
        $result = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'installed = 1'), true);
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
     * 
     * @return array
     */
    public function getFromRepository()
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
     * 
     * @return boolean
     */
    public function updateFromFilesystem()
    {
        $folders = glob(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, '*/*'), GLOB_ONLYDIR);
        if (!$folders) {
            return [];
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
     * 
     * @param object $dataset
     * @param array $modules
     * @return boolean
     */
    private function createResult($dataset, array &$modules)
    {
        $module = new module($dataset->mkey, false);
        $module->createFromDbObject($dataset);
        $modules[$dataset->mkey] = $module;
        return true;
    }

}
