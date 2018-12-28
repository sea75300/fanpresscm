<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration base class
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since FPCM 4.x
 * @ignore
 */
abstract class migration {

    /**
     * Databse object
     * @var \fpcm\classes\database
     */
    protected $db;

    /**
     * System-Config-Objekt
     * @var \fpcm\model\system\config
     */
    private $config;

    /**
     * Konstruktor
     * @param string $key
     * @param boolean $initDb
     * @return bool
     */
    final public function __construct()
    {
        $this->db = new \fpcm\classes\database();
    }

    /**
     * 
     * @return \fpcm\model\system\config
     */
    final protected function getConfig() : \fpcm\model\system\config
    {
        if ( !($this->config instanceof \fpcm\model\system\config) ) {
            $this->config = new \fpcm\model\system\config(false);;
        }

        return $this->config;
    }

    final public function isRequired()
    {
        return version_compare($this->getNewVersion(), $this->getConfig()->system_version, '<');
    }

    final public function updateVersion()
    {
        $this->getConfig()->setNewConfig([
            'system_version' => $this->getNewVersion() ? $this->getNewVersion() : \fpcm\classes\baseconfig::getVersionFromFile()
        ]);

        return $this->getConfig()->update();
    }
    
    final public function process()
    {
        if (!$this->dbAddTables()) {
            return false;
        }

        if (!$this->dbUpdateTables()) {
            return false;
        }

        if (!$this->dbDeleteTables()) {
            return false;
        }

        if (!$this->dbAddFiles()) {
            return false;
        }

        if (!$this->dbUpdateFiles()) {
            return false;
        }

        if (!$this->dbDeleteFiles()) {
            return false;
        }

        if (!$this->dbAddOptions()) {
            return false;
        }

        if (!$this->dbUpdateOptions()) {
            return false;
        }

        if (!$this->dbDeleteOptions()) {
            return false;
        }

        if (!$this->dbAddPermissions()) {
            return false;
        }

        if (!$this->dbUpdatePermissions()) {
            return false;
        }

        if (!$this->dbDeletePermissions()) {
            return false;
        }


    }

    abstract public function getNewVersion();

    abstract public function dbAddTables();

    abstract public function dbUpdateTables();

    abstract public function dbDeleteTables();

    abstract public function fsAddFiles();

    abstract public function fsUpdateFiles();

    abstract public function fsDeleteFiles();

    abstract public function dbAddOptions();

    abstract public function dbUpdateOptions();

    abstract public function dbDeleteOptions();

    abstract public function dbAddPermissions();

    abstract public function dbUpdatePermissions();

    abstract public function dbDeletePermissions();
}
