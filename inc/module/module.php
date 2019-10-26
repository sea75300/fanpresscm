<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Module base model
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\module
 */
class module {

    const STATUS_INSTALLED = 1001;
    const STATUS_UNINSTALLED = 1002;
    const STATUS_ENABLED = 1004;
    const STATUS_DISABLED = 1005;
    const STATUS_NOT_INSTALLED = -1001;
    const STATUS_NOT_UNINSTALLED = -1002;
    const STATUS_NOT_ENABLED = -1004;
    const STATUS_NOT_DISABLED = -1005;

    /**
     * Dataset id
     * @var int
     */
    protected $id = 0;

    /**
     * Module key
     * @var string
     */
    protected $mkey = '';

    /**
     * Module prefix VENDOR_KEY
     * @var string
     */
    protected $prefix = '';

    /**
     * Module installed status
     * @var bool
     */
    protected $installed = 0;

    /**
     * Module active status
     * @var bool
     */
    protected $active = 0;

    /**
     * Module configuration
     * @var config
     */
    protected $config;

    /**
     * System config object
     * @var \fpcm\model\system\config
     */
    protected $systemConfig;

    /**
     * Databse object
     * @var \fpcm\classes\database
     */
    protected $db;

    /**
     * Cache object
     * @var \fpcm\classes\cache
     */
    protected $cache;

    /**
     * Initialize object from database
     * @var bool
     */
    protected $initDb;

    /**
     * Module base path
     * @var string
     * @since FPCM 4.3
     */
    protected $basePath = '';

    /**
     * Konstruktor
     * @param string $key
     * @param boolean $initDb
     * @return bool
     */
    final public function __construct($key, $initDb = true)
    {
        $this->db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->systemConfig = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $this->initDb = $initDb;

        $this->mkey = $key;
        $this->prefix = str_replace('/', '', $this->mkey);
        $this->basePath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, str_replace('\\', DIRECTORY_SEPARATOR, $this->mkey));

        $this->initObjects();
        $this->init();
    }

    /**
     * Fetch dataset ID
     * @return int
     */
    final public function getId() : int
    {
        return $this->id;
    }

    /**
     * Return module key
     * @return string
     */
    public function getKey() : string
    {
        return $this->mkey;
    }

    /**
     * Return installed status
     * @return bool
     */
    final public function isInstalled() : bool
    {
        return (bool) $this->installed;
    }

    /**
     * Return active status
     * @return bool
     */
    final public function isActive() : bool
    {
        return (bool) $this->active;
    }

    /**
     * Set installed status
     * @param bool $installed
     * @return $this
     */
    final public function setInstalled($installed) : module
    {
        $this->installed = (int) $installed;
        return $this;
    }

    /**
     * Set active status
     * @param bool $active
     * @return $this
     */
    final public function setActive($active) : module
    {
        $this->active = (int) $active;
        return $this;
    }

    /**
     * Initialize objects
     * @return bool
     */
    protected function initObjects() : bool
    {
        return true;
    }

    /**
     * Prepares module config options before saving
     * @param array $options
     * @return bool
     */
    public function prepareSaveOptions(array &$options) : bool
    {
        return true;
    }

    /**
     * Fetch system config options
     * @param string $key
     * @return mixed
     */
    final public function getOption($key)
    {
        return $this->systemConfig->{$this->getFullPrefix($key)};
    }

    /**
     * Fetch system config options
     * @return array
     */
    final public function getOptions() : array
    {
        return $this->systemConfig->getModuleOptions($this->getFullPrefix());
    }

    /**
     * Return additional vars for configure view
     * @return array
     */
    public function getConfigViewVars() : array
    {
        $res = \fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('modules\configure', $this->mkey);
        return is_array($res) && count($res) ? $res : [];
    }

    /**
     * Fetch system config options
     * @return array
     */
    
    /**
     * Updates module options
     * @param array $options
     * @return array
     */
    final public function setOptions(array $options) : bool
    {
        $this->systemConfig->init();

        $this->systemConfig->setNewConfig($options);
        $res = $this->systemConfig->update();
        
        if (!$res) {
            return false;
        }
        
        $this->systemConfig->init();
        $this->cache->cleanup();

        return true;
    }

    /**
     * Fetch module config
     * @return config
     */
    public function getConfig() : config
    {
        return $this->config;
    }

    /**
     * Initialize object with database data
     * @param object $result
     * @return bool
     */
    public function createFromDbObject($result) : bool
    {
        $this->id = isset($result->id) ? $result->id : false;
        $this->installed = isset($result->installed) ? $result->installed : false;
        $this->active = isset($result->active) ? $result->active : false;
        $this->config = new config($this->mkey, (isset($this->installed) && $this->installed ? $result->data : null));
        return true;
    }

    /**
     * Enable module
     * @return bool
     */
    public function enable() : bool
    {
        fpcmLogSystem('Enable module ' . $this->mkey);

        $this->setActive(true);
        if (!$this->db->update(\fpcm\classes\database::tableModules, ['active'], [$this->active, $this->mkey], 'mkey = ?')) {
            return false;
        }

        return true;
    }

    /**
     * Disable module
     * @return bool
     */
    public function disable() : bool
    {
        fpcmLogSystem('Disable module ' . $this->mkey);

        $this->setActive(false);
        if (!$this->db->update(\fpcm\classes\database::tableModules, ['active'], [$this->active, $this->mkey], 'mkey = ?')) {
            return false;
        }

        return true;
    }

    /**
     * 
     * @return bool
     * @since FPCM 4.3
     */
    public function isWritable() : bool
    {
        return is_writable($this->basePath) && is_writable($this->basePath.DIRECTORY_SEPARATOR.'module.yml');
    }

    /**
     * Check if module is installed
     * @return bool
     */
    public function isInstallable() : bool
    {
        if (defined('FPCM_MODULE_IGNORE_DEPENDENCIES') && FPCM_MODULE_IGNORE_DEPENDENCIES) {
            return true;
        }

        if (version_compare(PHP_VERSION, $this->config->requirements['php'], '<')) {
            return false;
        }

        if (version_compare($this->systemConfig->system_version, $this->config->requirements['system'], '<')) {
            return false;
        }

        return true;
    }

    /**
     * Check if module has updates
     * @return bool
     */
    public function hasUpdates() : bool
    {
        $data = \fpcm\classes\loader::getObject('\fpcm\model\updater\modules')->getDataCachedByKey($this->mkey);
        if ($data === false) {
            return false;
        }

        if (version_compare($this->config->version, $data['version'], '>=')) {
            return false;
        }

        if (version_compare(PHP_VERSION, $data['requirements']['php'], '<') || version_compare($this->systemConfig->system_version, $data['requirements']['system'], '<')) {
            return false;
        }

        return true;
    }

    /**
     * Checks if file system version number matches module.yml data
     * @return bool
     * @since FPCM 4.1
     */
    public function hasLocalUpdates() : bool
    {
        if (defined('FPCM_MODULE_DEV') && FPCM_MODULE_DEV) {
            return true;
        }

        return version_compare((new config($this->mkey, null))->version, $this->config->version, '=') ? false : true;
    }

    /**
     * Check if configure action should be displayed
     * @return bool
     */
    public function hasConfigure() : bool
    {
        return file_exists(\fpcm\module\module::getTemplateDirByKey($this->mkey, 'configure.php'));
    }

    /**
     * Check if config/files.txt file exists
     * @return bool
     * @since FÜPCM 4.1
     */
    final public function hasFilesListFile() : bool
    {
        return file_exists(rtrim($this->config->basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'files.txt' );
    }

    /**
     * Initialize module object
     * @return bool
     */
    protected function init() : bool
    {
        if (!trim($this->mkey)) {
            return false;
        }

        $result = ($this->initDb
                ? $this->db->selectFetch((new \fpcm\model\dbal\selectParams())->setTable(\fpcm\classes\database::tableModules)->setWhere('mkey = ?')->setParams([$this->mkey]))
                : false);

        if (!$result) {
            $this->config = new config($this->mkey);
            return false;
        }

        return $this->createFromDbObject($result);
    }

    /**
     * Fetch complete module prefix
     * @param string $key
     * @return string
     */
    private function getFullPrefix($key = '') : string
    {
        return 'module_' . $this->prefix . '_' . $key;
    }

    /**
     * Remove module prefix
     * @param string $key
     * @return string
     */
    private function removeFullPrefix($key = '') : string
    {
        return str_replace('module_' . $this->prefix . '_', '', $key);
    }

    /**
     * Fetch \fpcm\model\system\yatdl object
     * @param string $tableFile
     * @return bool|\fpcm\model\system\yatdl
     */
    private function getYaTdlObject($tableFile)
    {
        $tab = new \fpcm\model\system\yatdl($tableFile);
        $tab->setTablePrefix($this->getFullPrefix());

        $success = $tab->parse();
        if ($success !== true) {
            trigger_error('Unable to parse table definition for ' . $tableFile . ', ERROR CODE: ' . $success);
            return false;
        }

        return $tab;
    }

    /**
     * Return list of table files from module
     * @return array
     */
    private function getTableFiles() : array
    {
        $files = glob(rtrim($this->config->basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . '*.yml');
        if (!is_array($files)) {
            return [];
        }
        

        return $files;
    }

    /**
     * Fetch module system config options
     * @return array
     */
    private function getAllConfigOptions() : array
    {
        if (!is_array($this->config->configOptions)) {
            return [];
        }

        $addcfg = isset($this->config->configOptions['add']) && is_array($this->config->configOptions['add'])
                ? $this->config->configOptions['add']
                : [];

        $delcfg = isset($this->config->configOptions['remove']) && is_array($this->config->configOptions['remove'])
                ? $this->config->configOptions['remove']
                : [];

        $configOptions = array_unique(array_merge($addcfg, $delcfg));
        if (!count($configOptions)) {
            return [];
        }

        return $configOptions;
    }

    /**
     * Create module config
     * @param \fpcm\model\system\yatdl $tab
     * @return bool
     */
    private function createTable(\fpcm\model\system\yatdl $tab) : bool
    {
        $sqlStr = $tab->getSqlString();
        $tmpFile = \fpcm\classes\dirs::getDataDirPath(
            \fpcm\classes\dirs::DATA_TEMP,
            hash(\fpcm\classes\security::defaultHashAlgo, $tab->getArray()['name']) . '.sql'
        );

        if (!trim($sqlStr) || !file_put_contents($tmpFile, $sqlStr)) {
            trigger_error('Unable to prepare table definition for execution ' . $tab->getArray()['name']);
            return false;
        }

        $this->db->execSqlFile($tmpFile);
        unlink($tmpFile);

        return true;
    }

    /**
     * Install module
     * @param boolean $fromDir
     * @return bool
     */
    final public function install($fromDir = false) : bool
    {
        fpcmLogSystem('Installation of module ' . $this->mkey);

        $this->cache->cleanup();

        if (!$this->installTables()) {
            trigger_error('Error while installing module tables!');
            return false;
        }

        if (!$this->installConfig()) {
            trigger_error('Error while installing module config options!');
            return false;
        }

        if (!$this->installUpdateCronjobs()) {
            trigger_error('Install module cronjobs was not successful!');
            return false;
        }

        if (!\fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('modules\installAfter', $this->mkey)) {
            return false;
        }

        $this->installed = 1;
        $this->active = 0;

        if (!$this->addModule($fromDir)) {
            return false;
        }

        $this->cache->cleanup();        
        return true;
    }

    /**
     * Add module entry to database
     * @param boolean $fromDir
     * @return bool
     */
    public function addModule($fromDir = false) : bool
    {
        fpcmLogSystem('Update modules table with ' . $this->mkey);

        $values = get_object_vars($this);
        unset($values['db'], $values['config'], $values['id'], $values['prefix'], $values['systemConfig'], $values['cache'], $values['initDb'], $values['basePath']);
        $values['data'] = json_encode($this->config);

        $result = $fromDir
                ? $this->db->update(\fpcm\classes\database::tableModules, array_keys($values), array_merge(array_values($values), [$this->mkey]), 'mkey = ?')
                : $this->db->insert(\fpcm\classes\database::tableModules, $values);

        if (!$result) {
            trigger_error('Error while running update of module table for '.$this->mkey);
            return false;
        }

        $this->id = $this->db->getLastInsertId();
        return true;
    }

    /**
     * Create module tables
     * @return bool
     */
    private function installTables() : bool
    {
        $tableFiles = $this->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        fpcmLogSystem('Add modules table for ' . $this->mkey);

        foreach ($tableFiles as $tableFile) {

            $tab = $this->getYaTdlObject($tableFile);
            if (!$this->createTable($tab)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create module config options
     * @return bool
     */
    private function installConfig() : bool
    {
        $configOptions = $this->getAllConfigOptions();
        if (!count($configOptions)) {
            return true;
        }
        
        fpcmLogSystem('Add modules config options for ' . $this->mkey);
        
        $sysConfig = new \fpcm\model\system\config(false);
        foreach ($configOptions as $key => $value) {
            $key = $this->getFullPrefix($key);
            if ($sysConfig->add($key, $value) === false) {
                trigger_error('Unable to create config option ' . $key.', error code: '.$res);
                return false;
            }
        }

        return true;
    }

    /**
     * Create module config options
     * @return bool
     */
    private function installUpdateCronjobs() : bool
    {
        fpcmLogSystem('Add modules cronjobs for ' . $this->mkey);
        
        $crons = $this->config->crons;
        if (!is_array($crons) || !count($crons)) {
            return true;
        }
        
        $cronjobs = $this->db->selectFetch(
            (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableCronjobs))
                ->setItem('id, cjname')
                ->setWhere('modulekey = ?')
                ->setParams([$this->mkey])
                ->setFetchStyle(\PDO::FETCH_KEY_PAIR)
        );

        $failed = [];
        foreach ($crons as $name => $interval) {
            
            
            $className = self::getCronNamespace($this->mkey, $name);
            if (!class_exists($className)) {
                trigger_error("Unable to add cronjob, class {$className} does not exists!");
                continue;
            }

            if (in_array($name, $cronjobs)) {
                fpcmLogSystem('Module cronjobs '.$name.' already exists, skipping...');
                continue;
            }

            $success = $this->db->insert(\fpcm\classes\database::tableCronjobs, [
                'cjname' => $name,
                'execinterval' => $interval,
                'modulekey' => $this->mkey,
                'lastexec' => 0
            ]);

            if (!$success) {
                $failed[] = $name;
            }
        }

        if (count($failed)) {
            trigger_error("An error occurred while installing the following cronjobs:".PHP_EOL. implode(PHP_EOL, $failed));
            return false;
        }

        return true;
    }

    /**
     * Uninstall module
     * @param boolean $delete
     * @return bool
     */
    final public function uninstall($delete = false) : bool
    {
        fpcmLogSystem('Uninstall module ' . $this->mkey);
        $this->cache->cleanup();

        if (!$delete && !\fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('modules\uninstallAfter', $this->mkey)) {
            return false;
        }

        if (!$this->removeCronjobs() && !$delete) {
            return false;
        }

        if (!$this->removeConfig() && !$delete) {
            return false;
        }

        if (!$this->removeTables() && !$delete) {
            return false;
        }

        if (!$this->removeModule()) {
            return false;
        }

        if (!$this->removeFiles()) {
            return false;
        }

        $this->id = 0;
        $this->active = false;
        $this->installed = false;
        $this->cache->cleanup();
        return true;
    }

    /**
     * Remove module database entry
     * @return bool
     */
    private function removeModule() : bool
    {
        fpcmLogSystem('Remove modules table entry for ' . $this->mkey);
        return $this->db->delete(\fpcm\classes\database::tableModules, 'mkey = ?', [$this->mkey]);
    }

    /**
     * Remove module files
     * @return bool
     */
    private function removeFiles() : bool
    {
        fpcmLogSystem('Remove modules files from ' . \fpcm\model\files\ops::removeBaseDir($this->config->basePath));
        return \fpcm\model\files\ops::deleteRecursive($this->config->basePath);
    }

    /**
     * Remove module tables
     * @return bool
     */
    private function removeTables() : bool
    {
        $tableFiles = $this->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        fpcmLogSystem('Remove modules table for ' . $this->mkey);
        foreach ($tableFiles as $tableFile) {
            $tab = $this->getYaTdlObject($tableFile);
            $tabName = $tab->getArray()['name'];

            $struct = $this->db->getTableStructure($tabName);
            if (!count($struct)) {
                continue;
            }

            if (!$this->db->drop($tabName)) {
                trigger_error('Unable to drop module table ' . $tabName . ' during uninstalling');
                return false;
            }
        }

        return true;
    }

    /**
     * Remove module config
     * @return bool
     */
    private function removeConfig() : bool
    {
        $configOptions = $this->getAllConfigOptions();
        if (!count($configOptions)) {
            return true;
        }

        return $this->db->delete(
            \fpcm\classes\database::tableConfig, 'config_name IN (' . implode(', ', array_fill(0, count($configOptions), '?')) . ')', array_map([$this, 'getFullPrefix'], array_keys($configOptions))
        );
    }

    /**
     * Create module config options
     * @return bool
     */
    private function removeCronjobs() : bool
    {
        fpcmLogSystem('Remove modules cronjobs for ' . $this->mkey);
        
        $crons = $this->config->crons;
        if (!is_array($crons) || !count($crons)) {
            return true;
        }

        return $this->db->delete(\fpcm\classes\database::tableCronjobs, 'modulekey = ?', [$this->mkey]);
    }

    /**
     * Update module
     * @return bool
     */
    final public function update() : bool
    {
        fpcmLogSystem('update module ' . $this->mkey);
        $this->cache->cleanup();

        $this->config = new config($this->mkey);
        if (!$this->updateTables()) {
            return false;
        }

        if (!$this->updateConfig()) {
            return false;
        }

        if (!$this->installUpdateCronjobs()) {
            return false;
        }

        if (!\fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('modules\updateAfter', $this->mkey)) {
            return false;
        }

        if (!$this->updateModule()) {
            return false;
        }

        $this->cache->cleanup();
        return true;
    }

    /**
     * Update module databse entry
     * @return bool|int
     */
    private function updateModule() : bool
    {
        fpcmLogSystem('Update modules table with ' . $this->mkey);
        if (!$this->db->update(\fpcm\classes\database::tableModules, ['data'], [json_encode($this->config), $this->mkey], 'mkey = ?')) {
            return false;
        }

        return true;
    }

    /**
     * Update module tables
     * @return bool
     */
    private function updateTables() : bool
    {
        $tableFiles = $this->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        fpcmLogSystem('Update modules table for ' . $this->mkey . ' during update');

        $tables = $this->config->tables;

        $addTables = $tables['add'] ?? [];
        if (!is_array($addTables)) {
            $addTables = [];
        }

        $alterTables = $tables['alter'] ?? [];
        if (!is_array($alterTables)) {
            $alterTables = [];
        }

        $dropTables = $tables['drop'] ?? [];
        if (!is_array($dropTables)) {
            $dropTables = [];
        }

        foreach ($tableFiles as $tableFile) {

            $tab = $this->getYaTdlObject($tableFile);

            $tableName = $tab->getArray()['name'];
            $tabBase = $this->removeFullPrefix($tableName);

            if (in_array($tabBase, $dropTables)) {
                if (!$this->db->drop($tableName)) {
                    trigger_error('Unable to drop module table ' . $tableName . ' during update');
                    return false;
                }
            }

            if (in_array($tabBase, $alterTables)) {
                if (!$this->db->addTableCols($tab)) {
                    trigger_error('Unable to alter module table ' . $tableName . ' during update, addition of new columns failed.');
                    return false;
                }

                if (!$this->db->addTableIndices($tab)) {
                    trigger_error('Unable to alter module table ' . $tableName . ' during update, addition of new indices failed.');
                    return false;
                }

                if (!$this->db->removeTableCols($tab)) {
                    trigger_error('Unable to alter module table ' . $tableName . ' during update, removal of new columns failed.');
                    return false;
                }
            }

            if (!in_array($tabBase, $addTables)) {
                continue;
            }

            if (!$this->createTable($tab)) {
                trigger_error('Unable to create module table ' . $tableName . ' during update');
                return false;
            }
        }

        return true;
    }

    /**
     * Update module config
     * @return bool
     */
    private function updateConfig() : bool
    {
        $configOptions = $this->getAllConfigOptions();
        if (!count($configOptions)) {
            return true;
        }

        fpcmLogSystem('Add modules config options for ' . $this->mkey);
        foreach ($configOptions as $key => $value) {
            $key = $this->getFullPrefix($key);
            if ($this->systemConfig->add($key, $value) === false) {
                trigger_error('Unable to create config option ' . $key);
                return false;
            }
        }

        return true;
    }

    /**
     * fetch module key from filepath
     * @param string $path
     * @return string
     */
    public static function getKeyFromPath($path) : string
    {
        $path = str_replace(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, DIRECTORY_SEPARATOR), '', $path);
        $path = explode(DIRECTORY_SEPARATOR, $path, 3);
        return $path[0] . '/' . $path[1];
    }

    /**
     * fetch module key from filename
     * @param string $filename
     * @return string
     */
    public static function getKeyFromFilename($filename) : string
    {
        $key = explode('_version', $filename, 2)[0];
        return implode('/', explode('_', $key, 1));
    }

    /**
     * fetch module key from class name
     * @param string $class
     * @return string
     */
    public static function getKeyFromClass($class)
    {
        if (strpos($class, 'fpcm\\modules\\') === false || !preg_match('/(\\\\?)(fpcm\\\\modules\\\\)([a-z]*\\\\[a-zA-Z_-]+)\\\\(.+)/i', $class, $matches)) {
            return false;
        }

        return empty($matches[3]) ? false : str_replace('\\', '/', $matches[3]);
    }

    /**
     * Get module event class name
     * @param string $key
     * @param string $event
     * @return string
     */
    public static function getEventNamespace($key, $event) : string
    {
        return "\\fpcm\\modules\\" . str_replace('/', '\\', $key) . "\\events\\{$event}";
    }

    /**
     * Get module controller class name
     * @param string $key
     * @param string $event
     * @return string
     */
    public static function getControllerNamespace($key, $event) : string
    {
        return "\\fpcm\\modules\\" . str_replace('/', '\\', $key) . "\\controller\\{$event}";
    }

    /**
     * Return Namespace of module cronjobs
     * @param string $key
     * @param string $cron
     * @return string
     * @since FPCM 4.3
     */
    public static function getCronNamespace(string $key, string $cron) : string
    {
        return "\\fpcm\\modules\\" . str_replace('/', '\\', $key) . "\\crons\\{$cron}";
    }

    /**
     * Get config file path
     * @param string $key
     * @param string $config
     * @return string
     */
    public static function getConfigByKey($key, $config) : string
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, str_replace('\\', DIRECTORY_SEPARATOR, $key) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR .$config. '.yml');
    }

    /**
     * Get template file path
     * @param string $key
     * @param string $viewName
     * @return string
     */
    public static function getTemplateDirByKey($key, $viewName) : string
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, str_replace('\\', DIRECTORY_SEPARATOR, $key) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $viewName);
    }

    /**
     * Get language file path
     * @param string $key
     * @param string $langKey
     * @return string
     */
    public static function getLanguageFileByKey($key, $langKey) : string
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, str_replace('\\', DIRECTORY_SEPARATOR, $key) . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $langKey . '.php');
    }

    /**
     * Assign module language variable prefix
     * @param string $key
     * @return string
     */
    public static function getLanguageVarPrefixed($key) : string
    {
        return 'MODULE_'.strtoupper(str_replace(['\\', DIRECTORY_SEPARATOR], '', $key)).'_';
    }

}
