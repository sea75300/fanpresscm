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
     * Konstruktor
     * @param string $key
     * @param bool $init
     */
    public function __construct($key)
    {
        $this->db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->systemConfig = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');

        $this->key = $key;
        $this->prefix = str_replace('/', '', $this->key);

        $this->init();
    }

    /**
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
        
    /**
     * 
     * @return bool
     */
    public function isInstalled()
    {
        return (bool) $this->installed;
    }

    /**
     * 
     * @return bool
     */
    public function isActive()
    {
        return (bool) $this->active;
    }

    /**
     * 
     * @param bool $installed
     * @return $this
     */
    public function setInstalled($installed)
    {
        $this->installed = (int) $installed;
        return $this;
    }

    /**
     * 
     * @param bool $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = (int) $active;
        return $this;
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
     * @return boolean
     */
    final private function init()
    {
        if (!trim($this->key)) {
            return false;
        }

        $result = $this->db->fetch($this->db->select(\fpcm\classes\database::tableModules, '*', 'key = ?', [
            $this->key
        ]));

        if (!$result) {
            $this->config = new config($this->key);            
            return false;
        }

        $this->id = isset($result->id) ? $result->id : false;
        $this->installed = isset($result->installed) ? $result->installed : false;
        $this->active = isset($result->active) ? $result->active : false;
        $this->config = new config($this->key, (!count($result) || !$this->installed ? null : $result->data));
        

        return true;
    }

    /**
     * 
     * @param string $key
     * @return string
     */
    final private function getFullPrefix($key = '')
    {
        return 'module_'.$this->prefix.'_'.$key;
    }

    /**
     * 
     * @param string $tableFile
     * @return boolean|\fpcm\model\system\yatdl
     */
    final private function getYaTdlObject($tableFile)
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
    final private function getTableFiles()
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
    final private function getAllConfigOptions()
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
        unset($values['db'], $values['config'], $values['id'], $values['prefix'], $values['systemConfig'], $values['cache']);
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

        if (!$this->removeConfig()) {
            return false;
        }

        if (!$this->removeModule()) {
            return false;
        }

        if (!$this->removeTables()) {
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

            if (!$this->db->drop($tabName)) {
                trigger_error('Unable to drop module table '.$tabName);
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
}
