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
    public function getInstalledModules()
    {
        $result = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'installed = 1'), true);
        if (!$result) {
            return [];
        }
        
        $modules = [];
        foreach ($result as $dataset) {
            $module = new module($dataset->key, false);
            $module->createFromDbObject($dataset);
            $modules[$dataset->key] = $module;
        }

        return $modules;
    }

    
}
