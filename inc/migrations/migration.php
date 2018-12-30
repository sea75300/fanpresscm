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
 * @since FPCM 4.1
 * @ignore
 */
abstract class migration {

    /**
     * Database object
     * @var \fpcm\classes\database
     */
    protected $db;

    /**
     * System config object
     * @var \fpcm\model\system\config
     */
    private $config;

    /**
     * CLI execution flag
     * @var bool
     */
    private $isCli;

    /**
     * Constructor method
     * @return boolean
     */
    final public function __construct()
    {
        $this->db = new \fpcm\classes\database();
        $this->isCli = \fpcm\classes\baseconfig::isCli();
        return true;
    }

    /**
     * Config object getter
     * @return \fpcm\model\system\config
     */
    final protected function getConfig() : \fpcm\model\system\config
    {
        if ( !($this->config instanceof \fpcm\model\system\config) ) {
            $this->config = new \fpcm\model\system\config(false);
        }

        return $this->config;
    }

    /**
     * Migration execution required due to system version
     * @return bool
     */
    final public function isRequired() : bool
    {
        return version_compare($this->getNewVersion(), $this->getConfig()->system_version, '<');
    }

    /**
     * Process migration
     * @return bool
     */
    final public function process() : bool
    {
        if (!$this->updateTables()) {
            trigger_error('Migration '.get_class($this).': failed to update database tables.');
            return false;
        }

        if (!$this->updateFileSystem()) {
            trigger_error('Migration '.get_class($this).': failed to update local filesystem.');
            return false;
        }

        if (!$this->updateSystemConfig()) {
            trigger_error('Migration '.get_class($this).': failed to update system config.');
            return false;
        }

        if (!$this->updatePermissions()) {
            trigger_error('Migration '.get_class($this).': failed to update permission settings.');
            return false;
        }

        if (!$this->updateVersion()) {
            trigger_error('Migration '.get_class($this).': failed to update version.');
            return false;
        }

        return true;
    }

    /**
     * Update system version
     * @return bool
     */
    final protected function updateVersion() : bool
    {
        $newVersion = $this->getNewVersion();
        $this->getConfig()->setNewConfig([
            'system_version' => $newVersion
        ]);

        if (!$this->getConfig()->update()) {
            return false;
        }

        if (!version_compare($this->getConfig()->system_version, $newVersion, '=')) {
            return false;
        }

        return true;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return \fpcm\classes\baseconfig::getVersionFromFile();
    }

    abstract protected function updateTables();

    abstract protected function updateFileSystem();

    abstract protected function updateSystemConfig();

    abstract protected function updatePermissions();
}
