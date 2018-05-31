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
    const STATUS_UPDATED = 1003;
    const STATUS_ENABLED = 1004;
    const STATUS_DISABLED = 1005;
    const STATUS_NOT_INSTALLED = -1001;
    const STATUS_NOT_UNINSTALLED = -1002;
    const STATUS_NOT_UPDATED = -1003;
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
     * Konstruktor
     * @param string $key
     * @param boolean $initDb
     * @return boolean
     */
    final public function __construct($key, $initDb = true)
    {
        $this->db = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->systemConfig = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $this->initDb = $initDb;

        $this->mkey = $key;
        $this->prefix = str_replace('/', '', $this->mkey);

        $this->initObjects();
        $this->init();
    }

    /**
     * Fetch dataset ID
     * @return int
     */
    final public function getId()
    {
        return $this->id;
    }

    /**
     * Return module key
     * @return string
     */
    public function getKey()
    {
        return $this->mkey;
    }

    /**
     * Return installed status
     * @return bool
     */
    final public function isInstalled()
    {
        return (bool) $this->installed;
    }

    /**
     * Return active status
     * @return bool
     */
    final public function isActive()
    {
        return (bool) $this->active;
    }

    /**
     * Set installed status
     * @param bool $installed
     * @return $this
     */
    final public function setInstalled($installed)
    {
        $this->installed = (int) $installed;
        return $this;
    }

    /**
     * Set active status
     * @param bool $active
     * @return $this
     */
    final public function setActive($active)
    {
        $this->active = (int) $active;
        return $this;
    }

    /**
     * Initialize objects
     * @return boolean
     */
    protected function initObjects()
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
     * Fetch module config
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
        $this->config = new config($this->mkey, (!count($result) || !$this->installed ? null : $result->data));

        return true;
    }

    /**
     * Enable module
     * @return boolean
     */
    public function enable()
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
     * @return boolean
     */
    public function disable()
    {
        fpcmLogSystem('Disable module ' . $this->mkey);

        $this->setActive(false);
        if (!$this->db->update(\fpcm\classes\database::tableModules, ['active'], [$this->active, $this->mkey], 'mkey = ?')) {
            return false;
        }

        return true;
    }

    /**
     * Check if module is installed
     * @return boolean
     */
    public function isInstallable()
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
     * @return boolean
     */
    public function hasUpdates()
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
     * Initialize module object
     * @return boolean
     */
    protected function init()
    {
        if (!trim($this->mkey)) {
            return false;
        }

        $result = ($this->initDb
                ? $this->db->fetch($this->db->select(\fpcm\classes\database::tableModules, '*', 'mkey = ?', [$this->mkey]))
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
    private function getFullPrefix($key = '')
    {
        return 'module_' . $this->prefix . '_' . $key;
    }

    /**
     * Remove module prefix
     * @param string $key
     * @return string
     */
    private function removeFullPrefix($key = '')
    {
        return str_replace('module_' . $this->prefix . '_', '', $key);
    }

    /**
     * Fetch \fpcm\model\system\yatdl object
     * @param string $tableFile
     * @return boolean|\fpcm\model\system\yatdl
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
    private function getTableFiles()
    {
        $files = glob($this->config->basePath . 'config' . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . '*.yml');
        if (!is_array($files)) {
            return [];
        }

        return $files;
    }

    /**
     * Fetch module system config options
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
     * Create module config
     * @param \fpcm\model\system\yatdl $tab
     * @return boolean
     */
    private function createTable(\fpcm\model\system\yatdl $tab)
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
     * @return boolean
     */
    final public function install($fromDir = false)
    {
        fpcmLogSystem('Installation of module ' . $this->mkey);

        $this->installed = 1;
        $this->active = 0;

        $this->cache->cleanup();

        if (!$this->installTables()) {
            return false;
        }

        if (!$this->installConfig()) {
            return false;
        }

        if (!\fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('modules\installAfter', $this->mkey)) {
            return false;
        }

        if (!$this->addModule($fromDir)) {
            return false;
        }

        $this->cache->cleanup();

        return true;
    }

    /**
     * Add module entry to database
     * @param boolean $fromDir
     * @return boolean
     */
    public function addModule($fromDir = false)
    {
        fpcmLogSystem('Update modules table with ' . $this->mkey);

        $values = get_object_vars($this);
        unset($values['db'], $values['config'], $values['id'], $values['prefix'], $values['systemConfig'], $values['cache'], $values['initDb']);
        $values['data'] = json_encode($this->config);

        $result = $fromDir ? $this->db->update(\fpcm\classes\database::tableModules, array_keys($values), array_merge(array_values($values), [$this->mkey]), 'mkey = ?') : $this->db->insert(\fpcm\classes\database::tableModules, $values);

        if (!$result) {
            return false;
        }

        $this->id = $this->db->getLastInsertId();
        return true;
    }

    /**
     * Create module tables
     * @return boolean
     */
    private function installTables()
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
     * @return boolean
     */
    private function installConfig()
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
     * Uninstall module
     * @param boolean $delete
     * @return boolean
     */
    final public function uninstall($delete = false)
    {
        fpcmLogSystem('Uninstall module ' . $this->mkey);
        $this->cache->cleanup();

        if (!$delete && !\fpcm\classes\loader::getObject('\fpcm\events\events')->trigger('modules\uninstallAfter', $this->mkey)) {
            return false;
        }

        if (!$this->removeTables() && !$delete) {
            return false;
        }

        if (!$this->removeConfig() && !$delete) {
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
     * @return boolean
     */
    private function removeModule()
    {
        fpcmLogSystem('Remove modules table entry for ' . $this->mkey);
        return $this->db->delete(\fpcm\classes\database::tableModules, 'mkey = ?', [$this->mkey]);
    }

    /**
     * Remove module files
     * @return boolean
     */
    private function removeFiles()
    {
        fpcmLogSystem('Remove modules files from ' . \fpcm\model\files\ops::removeBaseDir($this->config->basePath));
        return \fpcm\model\files\ops::deleteRecursive($this->config->basePath);
    }

    /**
     * Remove module tables
     * @return boolean
     */
    private function removeTables()
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
     * @return boolean
     */
    private function removeConfig()
    {
        $configOptions = $this->getAllConfigOptions();
        if (!count($configOptions)) {
            return true;
        }

        fpcmLogSystem('Remove modules config options for ' . $this->mkey);

        return $this->db->delete(
                        \fpcm\classes\database::tableConfig, 'config_name IN (' . implode(', ', array_fill(0, count($configOptions), '?')) . ')', array_map([$this, 'getFullPrefix'], array_keys($configOptions))
        );
    }

    /**
     * Update module
     * @return boolean
     */
    final public function update()
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
     * @return boolean|int
     */
    private function updateModule()
    {
        fpcmLogSystem('Update modules table with ' . $this->mkey);
        if (!$this->db->update(\fpcm\classes\database::tableModules, ['data'], [json_encode($this->config), $this->mkey], 'mkey = ?')) {
            return false;
        }

        return true;
    }

    /**
     * Update module tables
     * @return boolean
     */
    private function updateTables()
    {
        $tableFiles = $this->getTableFiles();
        if (!count($tableFiles)) {
            return true;
        }

        fpcmLogSystem('Update modules table for ' . $this->mkey . ' during update');

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
                    trigger_error('Unable to drop module table ' . $tableName . ' during update');
                    return false;
                }
            }

            if (in_array($tabBase, $alterTables)) {
                if (!$this->db->addTableCols($tab)) {
                    trigger_error('Unable to alter module table ' . $tableName . ' during update, addition of new columns failed.');
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
     * @return boolean
     */
    private function updateConfig()
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
    public static function getKeyFromPath($path)
    {
        $path = str_replace(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, DIRECTORY_SEPARATOR), '', $path);
        $path = explode(DIRECTORY_SEPARATOR, $path, 3);
        return $path[0] . '/' . $path[1];
    }

    /**
     * fetch module key from filename
     * @param string $path
     * @return string
     */
    public static function getKeyFromFilename($filename)
    {
        return str_replace('_', '/', explode('_version', $filename, 2)[0]);
    }

    /**
     * fetch module key from class name
     * @param string $path
     * @return string
     */
    public static function getKeyFromClass($class)
    {
        if (strpos($class, 'fpcm\\modules\\') === false) {
            return false;
        }

        return explode('\\', $class, 3)[2];
    }

    /**
     * Get module event class name
     * @param string $key
     * @param string $event
     * @return string
     */
    public static function getEventNamespace($key, $event)
    {
        return "\\fpcm\\modules\\" . str_replace('/', '\\', $key) . "\\events\\{$event}";
    }

    /**
     * Get module controller class name
     * @param string $key
     * @param string $event
     * @return string
     */
    public static function getControllerNamespace($key, $event)
    {
        return "\\fpcm\\modules\\" . str_replace('/', '\\', $key) . "\\controller\\{$event}";
    }

    /**
     * Get config file path
     * @param string $key
     * @param string $config
     * @return string
     */
    public static function getConfigByKey($key, $config)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, str_replace('\\', DIRECTORY_SEPARATOR, $key) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR .$config. '.yml');
    }

    /**
     * Get template file path
     * @param string $key
     * @param string $viewName
     * @return string
     */
    public static function getTemplateDirByKey($key, $viewName)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, str_replace('\\', DIRECTORY_SEPARATOR, $key) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $viewName);
    }

    /**
     * Get language file path
     * @param string $key
     * @param string $langKey
     * @return string
     */
    public static function getLanguageFileByKey($key, $langKey)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, str_replace('\\', DIRECTORY_SEPARATOR, $key) . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . $langKey . '.php');
    }

    /**
     * Assign module language variable prefix
     * @param string $key
     * @return string
     */
    public static function getLanguageVarPrefixed($key)
    {
        return 'MODULE_'.strtoupper(str_replace(['\\', DIRECTORY_SEPARATOR], '', $key)).'_';
    }

}
