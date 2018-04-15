<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\modules;

/**
 * Module base model
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\modules
 */
class module {

    /**
     *
     * @var int
     */
    protected $id = 0;

    /**
     *
     * @var string
     */
    protected $key = '';

    /**
     *
     * @var string
     */
    protected $prefix = '';

    /**
     *
     * @var bool
     */
    protected $installed = false;

    /**
     *
     * @var bool
     */
    protected $active = false;

    /**
     *
     * @var config
     */
    protected $config;

    /**
     *
     * @var \fpcm\model\system\config
     */
    protected $systemConfig;

    /**
     *
     * @var \fpcm\classes\database
     */
    protected $db;

    /**
     *
     * @var \fpcm\classes\cache
     */
    protected $cache;

    /**
     *
     * @var bool
     */
    protected $initDb;

    /**
     * Konstruktor
     * @param string $key
     * @param boolean $initDb
     * @return boolean
     */
    final public function __construct($key, $initDb = false)
    {
        $this->db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->systemConfig = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $this->initDb = $initDb;

        $this->key = $key;
        $this->prefix = str_replace('/', '', $this->key);

        $this->initObjects();
        $this->init();
    }

    /**
     * 
     * @return int
     */
    final public function getId()
    {
        return $this->id;
    }

    /**
     * 
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
       
    /**
     * 
     * @return bool
     */
    final public function isInstalled()
    {
        return (bool) $this->installed;
    }

    /**
     * 
     * @return bool
     */
    final public function isActive()
    {
        return (bool) $this->active;
    }

    /**
     * 
     * @param bool $installed
     * @return $this
     */
    final public function setInstalled($installed)
    {
        $this->installed = (int) $installed;
        return $this;
    }

    /**
     * 
     * @param bool $active
     * @return $this
     */
    final public function setActive($active)
    {
        $this->active = (int) $active;
        return $this;
    }

    /**
     * 
     * @return boolean
     */
    protected function initObjects()
    {
        return true;
    }

    /**
     * 
     * @param string $key
     * @return mixed
     */
    final public function getOption($key)
    {
        return $this->systemConfig->{$this->getFullPrefix($key)};
    }

    /**
     * 
     * @return config
     */
    public function getConfig()
    {
        return $this->config;
    }
        
    /**
     * Initialize object with database data
     * @param object $result
     * @return boolean
     */
    public function createFromDbObject($result)
    {
        $this->id = isset($result->id) ? $result->id : false;
        $this->installed = isset($result->installed) ? $result->installed : false;
        $this->active = isset($result->active) ? $result->active : false;
        $this->config = new config($this->key, (!count($result) || !$this->installed ? null : $result->data));

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function init()
    {
        if (!trim($this->key)) {
            return false;
        }

        $result = ($this->initDb
                ? $this->db->fetch($this->db->select(\fpcm\classes\database::tableModules, '*', 'key = ?', [$this->key]))
                : false);

        if (!$result) {
            $this->config = new config($this->key);            
            return false;
        }

        return $this->createFromDbObject($result);
    }

    /**
     * 
     * @param string $key
     * @return string
     */
    private function getFullPrefix($key = '')
    {
        return 'module_'.$this->prefix.'_'.$key;
    }

    /**
     * 
     * @param string $key
     * @return string
     */
    private function removeFullPrefix($key = '')
    {
        return str_replace('module_'.$this->prefix.'_', '', $key);
    }

    /**
     * 
     * @param string $tableFile
     * @return boolean|\fpcm\model\system\yatdl
     */
    private function getYaTdlObject($tableFile)
    {
        $tab = new \fpcm\model\system\yatdl($tableFile);
        $tab->setTablePrefix($this->getFullPrefix());            

        $success = $tab->parse();
        if ($success !== true) {
            trigger_error('Unable to parse table definition for '.$tableFile.', ERROR CODE: '.$success);
            return false;                
        }
        
        return $tab;
    }

    /**
     * 
     * @return array
     */
    private function getTableFiles()
    {
        $files = glob($this->config->basePath.'config'.DIRECTORY_SEPARATOR.'tables'.DIRECTORY_SEPARATOR.'*.yml');
        if (!is_array($files)) {
            return [];
        }

        return $files;
    }

    /**
     * 
     * @return array
     */
    private function getAllConfigOptions()
    {
        if (!is_array($this->config->configOptions)) {
            return [];
        }
        
        $configOptions = array_unique(array_merge($this->config->configOptions['add'], $this->config->configOptions['remove']));
        if (!count($configOptions)) {
            return [];
        }
        
        return $configOptions;
    }

    /**
     * 
     * @param \fpcm\model\system\yatdl $tab
     * @return boolean
     */
    private function createTable(\fpcm\model\system\yatdl $tab)
    {
        $sqlStr = $tab->getSqlString();
        $tmpFile = \fpcm\classes\dirs::getDataDirPath(
            \fpcm\classes\dirs::DATA_TEMP,
            hash(\fpcm\classes\security::defaultHashAlgo, $tab->getArray()['name']).'.sql'
        );

        if (!trim($sqlStr) || !file_put_contents($tmpFile, $sqlStr)) {
            trigger_error('Unable to prepare table definition for execution '.$tableFile);
            return false;
        }

        $this->db->execSqlFile($tmpFile);
        unlink($tmpFile);

        return true;
    }

    /**
     * 
     * @return boolean
     */
    final public function install()
    {
        fpcmLogSystem('Installation of module '.$this->key);
        
        $this->installed = 1;
        $this->active = 0;

        $this->cache->cleanup();

        if (!$this->installTables()) {
            return false;
        }

        if (!$this->installConfig()) {
            return false;
        }

        if (!$this->addModule()) {
            return false;
        }

        if (method_exists($this, 'installAfter') && !$this->installAfter()) {
            return false;
        }

        $this->cache->cleanup();

        return true;
    }

    /**
     * 
     * @return boolean|int
     */
    private function addModule()
    {
        fpcmLogSystem('Update modules table with '.$this->key);
        
        $values = get_object_vars($this);
        unset($values['db'], $values['config'], $values['id'], $values['prefix'], $values['systemConfig'], $values['cache'], $values['initDb']);
        $values['data'] = json_encode($this->config);

        if (!$this->db->insert(\fpcm\classes\database::tableModules, $values)) {
            return false;
        }

        $this->id = $this->db->getLastInsertId();
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function installTables()
    {
        $tableFiles = $this->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        fpcmLogSystem('Add modules table for '.$this->key);

        foreach ($tableFiles as $tableFile) {

            $tab = $this->getYaTdlObject($tableFile);
            if (!$this->createTable($tab)) {
                return false;
            }

        }

        return true;
    }
    
    /**
     * 
     * @return boolean
     */
    private function installConfig()
    {
        $configOptions = $this->getAllConfigOptions();
        if (!count($configOptions)) {
            return true;
        }

        fpcmLogSystem('Add modules config options for '.$this->key);
        foreach ($configOptions as $key => $value) {
            $key = $this->getFullPrefix($key);
            if ($this->systemConfig->add($key, $value) === false) {
                trigger_error('Unable to create config option '.$key);
                return false;
            }
        }
        
        return true;
    }

    /**
     * 
     * @return boolean
     */
    final public function uninstall()
    {
        fpcmLogSystem('Uninstall module '.$this->key);
        $this->cache->cleanup();

        if (!$this->removeTables()) {
            return false;
        }

        if (!$this->removeConfig()) {
            return false;
        }

        if (!$this->removeModule()) {
            return false;
        }

        if (method_exists($this, 'uninstallAfter') && !$this->uninstallAfter()) {
            return false;
        }

        $this->id = 0;
        $this->active = false;
        $this->installed = false;
        $this->cache->cleanup();
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function removeModule()
    {
        fpcmLogSystem('Remove modules table entry for '.$this->key);
        return $this->db->delete(\fpcm\classes\database::tableModules, 'key = ?', [$this->key]);
    }

    /**
     * 
     * @return boolean
     */
    private function removeTables()
    {
        $tableFiles = $this->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        fpcmLogSystem('Remove modules table for '.$this->key);
        foreach ($tableFiles as $tableFile) {
            $tab = $this->getYaTdlObject($tableFile);
            $tabName = $tab->getArray()['name'];

            $struct = $this->db->getTableStructure($tabName);
            if (!count($struct)) {
                continue;
            }

            if (!$this->db->drop($tabName)) {
                trigger_error('Unable to drop module table '.$tabName.' during uninstalling');
                return false;
            }

        }

        return true;
    }
    
    /**
     * 
     * @return boolean
     */
    private function removeConfig()
    {
        $configOptions = $this->getAllConfigOptions();
        if (!count($configOptions)) {
            return true;
        }

        fpcmLogSystem('Remove modules config options for '.$this->key);

        return $this->db->delete(
            \fpcm\classes\database::tableConfig,
            'config_name IN ('. implode(', ', array_fill(0, count($configOptions), '?')) .')',
            array_map([$this, 'getFullPrefix'], array_keys($configOptions))
        );
    }

    /**
     * 
     * @return boolean
     */
    final public function update()
    {
        fpcmLogSystem('update module '.$this->key);
        $this->cache->cleanup();

        if (!$this->updateTables()) {
            return false;
        }

        if (!$this->updateConfig()) {
            return false;
        }

        if (!$this->updateModule()) {
            return false;
        }

        if (method_exists($this, 'updateAfter') && !$this->updateAfter()) {
            return false;
        }

        $this->cache->cleanup();
        return true;
    }

    /**
     * 
     * @return boolean|int
     */
    private function updateModule()
    {
        fpcmLogSystem('Update modules table with '.$this->key);
        if (!$this->db->update(\fpcm\classes\database::tableModules, ['data'], [json_encode($this->config), $this->key], 'key = ?')) {
            return false;
        }

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function updateTables()
    {
        $tableFiles = $this->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        fpcmLogSystem('Update modules table for '.$this->key.' during update');
        
        $addTables = $this->config->tables['add'];
        if (!is_array($addTables)) {
            $addTables = [];
        }

        $alterTables = $this->config->tables['alter'];
        if (!is_array($alterTables)) {
            $alterTables = [];
        }
        
        $dropTables = $this->config->tables['drop'];
        if (!is_array($dropTables)) {
            $dropTables = [];
        }

        foreach ($tableFiles as $tableFile) {

            $tab = $this->getYaTdlObject($tableFile);

            $tableName = $tab->getArray()['name'];
            $tabBase = $this->removeFullPrefix($tableName);

            if (in_array($tabBase, $dropTables)) {                
                if (!$this->db->drop($tableName)) {
                    trigger_error('Unable to drop module table '.$tableName.' during update');
                    return false;
                }
            }

            if (in_array($tabBase, $alterTables)) {
                if (!$this->db->addTableCols($tab)) {
                    trigger_error('Unable to alter module table '.$tableName.' during update, addition of new columns failed.');
                    return false;
                }

                if (!$this->db->removeTableCols($tab)) {
                    trigger_error('Unable to alter module table '.$tableName.' during update, removal of new columns failed.');
                    return false;
                }
            }

            if (!in_array($tabBase, $addTables)) {
                continue;
            }

            if (!$this->createTable($tab)) {
                trigger_error('Unable to create module table '.$tableName.' during update');
                return false;
            }

        }

        return true;
    }
    
    /**
     * 
     * @return boolean
     */
    private function updateConfig()
    {
        $configOptions = $this->getAllConfigOptions();
        if (!count($configOptions)) {
            return true;
        }

        fpcmLogSystem('Add modules config options for '.$this->key);
        foreach ($configOptions as $key => $value) {
            $key = $this->getFullPrefix($key);
            if ($this->systemConfig->add($key, $value) === false) {
                trigger_error('Unable to create config option '.$key);
                return false;
            }
        }
        
        return true;
    }

}
