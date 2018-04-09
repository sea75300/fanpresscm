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
    protected $moduleKey = '';

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
     * Konstruktor
     * @param string $moduleKey
     * @param bool $init
     */
    public function __construct($moduleKey)
    {
        $this->db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->systemConfig = \fpcm\classes\loader::getObject('\fpcm\model\system\config');

        $this->moduleKey = $moduleKey;
        $this->prefix = str_replace('/', '_', $this->moduleKey);

        $this->init();
    }

    /**
     * 
     * @return bool
     */
    public function isInstalled()
    {
        return $this->installed;
    }

    /**
     * 
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * 
     * @param bool $installed
     * @return $this
     */
    public function setInstalled($installed)
    {
        $this->installed = (bool) $installed;
        return $this;
    }

    /**
     * 
     * @param bool $active
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = (bool) $active;
        return $this;
    }

    /**
     * 
     * @return boolean
     */
    final private function init()
    {
        if (!trim($this->moduleKey)) {
            return false;
        }
        
        $result = $this->db->fetch($this->db->select(\fpcm\classes\database::tableModules, '*', 'moduleKey = ?', [
            $this->moduleKey
        ], true));

        if (!$result) {
            return false;
        }

        $this->id = isset($result->id) ? $result->id : false;
        $this->installed = isset($result->installed) ? $result->installed : false;
        $this->active = isset($result->active) ? $result->active : false;
        $this->config = new config($this->moduleKey, (!count($result) || !$this->installed ? null : $result->data));

        return true;
    }

    /**
     * 
     * @return boolean
     */
    final public function install()
    {
        if (!$this->addModule()) {
            return false;
        }

        if (!$this->installTables()) {
            return false;
        }
        
    }

    /**
     * 
     * @return boolean|int
     */
    private function addModule()
    {
        $values = get_object_vars($this);
        unset($values['db'], $values['config'], $values['id'], $values['prefix']);

        if (!$this->db->insert(\fpcm\classes\database::tableModules, $values)) {
            return false;
        }
        
        return $this->dbcon->getLastInsertId();
    }

    /**
     * 
     * @return boolean
     */
    private function installTables()
    {
        $tableFiles = glob($this->config->basePath.'config/tables/*.yml');

        if (!count($tableFiles)) {
            return true;
        }

        foreach ($tableFiles as $tableFile) {

            $tab = new \fpcm\model\system\yatdl($tableFile);
            $tab->setTablePrefix($this->getFullPrefix());            

            $success = $tab->parse();
            if ($success !== true) {
                trigger_error('Unable to parse table definition for '.$tableFile.', ERROR CODE: '.$success);
                return false;                
            }

            $sqlStr = $tab->getSqlString();
            $tmpFile = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, $tab->getArray()['name'].'.sql');

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
        if (!is_array($this->config->configOptions)) {
            return true;
        }
        
        $configOptions = array_unique(array_merge($this->config->configOptions['add'], $this->config->configOptions['remove']));

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
     * @param string $key
     * @return string
     */
    private function getFullPrefix($key = '')
    {
        return 'module_'.$this->prefix.'_'.$key;
    }
}
