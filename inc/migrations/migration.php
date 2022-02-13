<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration base class
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 4.3
 */
abstract class migration {

    /**
     * Database object
     * @var \fpcm\classes\database
     */
    private $db;

    /**
     * System config object
     * @var \fpcm\model\system\config
     */
    private $config;

    /**
     * CLI execution flag
     * @var bool
     */
    private $isCli = null;

    /**
     * Result of pre-executed migration, false if one failed
     * @var bool
     */
    protected $requiredResult = true;

    /**
     * Constructor method
     * @return boolean
     */
    final public function __construct()
    {
        $this->init();
        return true;
    }

    /**
     * Config object getter
     * @return \fpcm\model\system\config
     */
    final protected function getConfig() : \fpcm\model\system\config
    {
        if ( !($this->config instanceof \fpcm\model\system\config) ) {
            $this->config = new \fpcm\model\system\config();
        }

        return $this->config;
    }
    
    /**
     * Config object getter
     * @return \fpcm\model\system\config
     */
    final protected function getDB() : \fpcm\classes\database
    {
        if ( !($this->db instanceof \fpcm\classes\database) ) {
            $this->db = new \fpcm\classes\database();
        }

        return $this->db;
    }
    
    /**
     * Config object getter
     * @return \fpcm\model\system\config
     */
    final protected function isCli() : bool
    {
        if ( $this->isCli === null ) {
            $this->isCli = \fpcm\classes\baseconfig::isCli();
        }

        return $this->isCli;
    }

    /**
     * Migration execution required due to system version
     * @return bool
     */
    final public function isRequired() : bool
    {
        return version_compare($this->getPreviewsVersion(), $this->getNewVersion(), '<');
    }

    /**
     * return preview version string
     * @return string
     * @since 4.5.1-b1
     */
    protected function getPreviewsVersion() : string
    {
        return $this->getConfig()->system_version;
    }

    /**
     * Execute migrations
     * @return bool
     */
    final public function process() : bool
    {
        $cn = get_called_class();
        $this->output('Processing migration '.$cn);
        
        $dbType = $this->getDB()->getDbtype();
        if (!in_array($this->getDB()->getDbtype(), $this->onDatabase())) {
            $this->output('Skip migration '.$cn.' on '.$dbType.'...');
            return true;
        }

        if (method_exists($this->getDB(), 'transaction')) {
            $this->getDB()->transaction();
        }

        if (!$this->defaultAlterTables() || !$this->alterTablesAfter()) {
            return false;
        }

        if (!$this->defaultUpdatePermissions() || !$this->updatePermissionsAfter()) {
            return false;
        }

        if (!$this->defaultAddSystemOptions() || !$this->updateSystemConfig()) {
            return false;
        }

        if (!$this->updateFileSystem()) {
            return false;
        }

        if (method_exists($this->getDB(), 'commit')) {
            $this->getDB()->commit();
        }
        
        $this->optimizeTables();

        $this->output('Processing of migration '.$cn.' successful.');
        return true;
    }

    /**
     * 
     * Output of migration messages
     * @param string $str
     * @param bool $log
     * @return void
     * @since 4.3
     */
    final protected function output(string $str, $log = false)
    {
        $log = (int) $log;
        
        if ($log === 1) trigger_error(trim($str));
        elseif ($log === 2) fpcmLogSql(trim($str));
        else fpcmLogSystem(trim($str));

        if (!$this->isCli) {
            return;
        }

        \fpcm\model\cli\io::output($str);
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return \fpcm\classes\baseconfig::getVersionFromFile();
    }

    /**
     * Pre-Initializing
     * @return bool
     */
    protected function init() : bool
    {
        return true;
    }

    /**
     * Execute additional database table changes
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {
        return true;
    }

    /**
     * Executes additional permission updates
     * @return bool
     */
    protected function updatePermissionsAfter() : bool
    {
        return true;
    }

    /**
     * Execute additional file system updates
     * @return bool
     */
    protected function updateFileSystem() : bool
    {
        return true;
    }

    /**
     * Execute additional system config updates
     * @return bool
     */
    protected function updateSystemConfig() : bool
    {
        return true;
    }

    /**
     * Returns a list of database driver names the migration should be executed to,
     * default is MySQL/ MariaDB and Postgres
     * @return array
     * @since 4.4.1
     */
    protected function onDatabase() : array
    {
        return [\fpcm\classes\database::DBTYPE_MYSQLMARIADB, \fpcm\classes\database::DBTYPE_POSTGRES];
    }

    /**
     * Returns migration class namespace
     * @param string $version
     * @return string
     * @static
     */
    public static function getNamespace(string $version) : string
    {
        return 'fpcm\\migrations\\v'.preg_replace('/([^0-9a-z])/i', '', $version);
    }

    /**
     * 
     * @return bool
     * @since 5.0.0-b1
     */
    final protected function defaultAlterTables() : bool
    {
        $tableFiles = $this->getDB()->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        $dropTables = [];
        
        $addIndeices = method_exists($this->getDB(), 'addTableIndices');

        $i = 1;
        foreach ($tableFiles as $tableFile) {

            $tab = new \fpcm\model\system\yatdl($tableFile);

            $success = $tab->parse();
            if ($success !== true) {
                $this->output('Unable to parse table definition for ' . $tableFile . ', ERROR CODE: ' . $success, true);
                return false;
            }

            /* @var $tInfo \nkorg\yatdl\tableItem */
            $tInfo = $tab->getTable();
            
            $tableName = $tInfo->name;
            $isView = $tInfo->isview ?? false;

            $this->output("Alter structure for {$tableName}...", 2);

            $struct = $this->getDB()->getTableStructure($tableName);
            $tabExists = count($struct) ? true : false;

            if (!$isView &&  ( $tabExists && !$this->getDB()->addTableCols($tab) || !$this->getDB()->removeTableCols($tab) ) ) {
                $this->output('Failed to alter table ' . $tableName . ' during update.', 2);
                return false;
            }

            if (in_array($tableName, $dropTables)) {

                fpcmLogSql("Drop table {$tableName}...");
                $successDrop = false;
                if (!$tabExists) {
                    $this->output("Table not found, skipping...");
                }
                elseif (!$this->getDB()->drop($tableName)) {
                    $this->output('Unable to drop table ' . $tableName . ' during update', 2);
                    return false;
                }
                else {
                    $successDrop = true;
                }

                if ($successDrop) {
                    $tabExists = false;
                }

            }

            if (!$tabExists) {
                fpcmLogSql("Add table {$tableName}...");
                if (!$this->getDB()->execYaTdl($tableFile)) {
                    $this->output('Unable to create table ' . $tableName . ' during update', 2);
                    return false;
                }

            }
            
            if (!$isView && $tabExists && $addIndeices) {
                $this->getDB()->addTableIndices($tab);
            }
          
            $i++;
        }

        if (!$addIndeices) {
            $this->output("Important!! Table indices could not be added during database update. Please run \"fpcmcli.php pkg " . \fpcm\model\abstracts\cli::PARAM_UPGRADE_DB. " system\" after auto-update was finished.", 2);
        }

        return true;
    }

    /**
     * neue System-Optionen bei Update erzeugen
     * @return bool
     */
    final protected function defaultAddSystemOptions() : bool
    {
        $this->output("Update system options...");
        
        $yatdl = new \fpcm\model\system\yatdl(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DBSTRUCT, '06config.yml'));
        $yatdl->parse();

        $data = $yatdl->getArray();
        if (!isset($data['defaultvalues']['rows']) || !is_array($data['defaultvalues']['rows']) || !count($data['defaultvalues']['rows'])) {
            return true;
        }
        
        $conf = $this->getConfig();
        
        $data['defaultvalues']['rows'] = array_filter($data['defaultvalues']['rows'], function ($option) use ($conf) {
            
            if ($this->getConfig()->{$option['config_name']} !== false) {
                $this->output("'{$option['config_name']}' already existrs, skipping");
                return false;
            }
            
            if ($option['config_name'] === 'smtp_setting') {
                return false;
            }
            
            return true;

        });
        
        $res = true;
        foreach ($data['defaultvalues']['rows'] as $option) {

            $this->output("Update system option {$option['config_name']}...");

            $addres = $this->getConfig()->add($option['config_name'], trim($option['config_value']));
            $this->config = null;

            if ($addres === -1) {
                $this->output("{$option['config_name']} already existrs, skipping");
                $res = $res && true;
                continue;
            }
            
            $res = $res && $addres;
        }


        $this->output("Update system options ".($res ? 'successful' : 'failed')."...");
        return $res;
    }


    /**
     * aktualisiert Berechtigungen
     * @return bool
     */
    final protected function defaultUpdatePermissions() : bool
    {
        $this->output("Update permissions...");
        
        $rolls = (new \fpcm\model\users\userRollList())->getUserRolls();

        $default = null;
        foreach ($rolls as $group) {

            
            $permissionObj = new \fpcm\model\permissions\permissions($group->getId());
            
            if ($default === null) {
                $default = $permissionObj->getPermissionSet();
            }
            
            $data = $permissionObj->getPermissionData();
            
            $default['comment']['lockip'] = 1;
            $default['system']['profile'] = 1;
            
            $newData = $data;
            foreach ($default as $key => $value) {
                $newData[$key] = array_merge(array_intersect_key($data[$key], $value), array_diff_key($value, $data[$key]));
            }
            
            if (\fpcm\classes\tools::getHash(json_encode($data)) === \fpcm\classes\tools::getHash(json_encode($newData))) {
                continue;
            }

            $permissionObj->setPermissionData($newData);
            if (!$permissionObj->update()) {
                return false;
            }

        }
        
        (new \fpcm\classes\cache)->cleanup();

        $this->output("Update permissions successful...");
        return true;
    }

    /**
     * Führt Optimierung der Datenbank-Tabellen durch
     * @since 3.3
     * @return bool
     */
    final protected function optimizeTables() : bool
    {
        $tables = \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('updaterAddOptimizeTables', [
            \fpcm\classes\database::tableArticles,
            \fpcm\classes\database::tableAuthors,
            \fpcm\classes\database::tableCategories,
            \fpcm\classes\database::tableComments,
            \fpcm\classes\database::tableConfig,
            \fpcm\classes\database::tableCronjobs,
            \fpcm\classes\database::tableFiles,
            \fpcm\classes\database::tableIpAdresses,
            \fpcm\classes\database::tableModules,
            \fpcm\classes\database::tablePermissions,
            \fpcm\classes\database::tableRoll,
            \fpcm\classes\database::tableSessions,
            \fpcm\classes\database::tableSmileys,
            \fpcm\classes\database::tableShares,
            \fpcm\classes\database::tableTexts,
            \fpcm\classes\database::tableRevisions
        ]);
        
        foreach ($tables as $i => $table) {
            $this->output("Optimize table {$table}...", 2);
            $this->getDB()->optimize($table);
        }

        return true;
    }

}
