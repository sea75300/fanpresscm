<?php
    /**
     * Module list entry object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\modules;

    /**
     * Moduleliste Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class modulelist extends \fpcm\model\abstracts\tablelist {

        /**
         * Konstruktor
         */
        public function __construct() {
            $this->table = \fpcm\classes\database::tableModules;
            
            parent::__construct();
        }
                
        /**
         * Gibt Liste mit Modulen zurück, die von Modul-Server angeboten werden
         * @param bool $init
         * @return array
         */
        public function getModulesRemote($init = true) {
            
            if (!\fpcm\classes\baseconfig::canConnect()) return [];
            
            $moduleUpdater = new \fpcm\model\updater\modules();
            $moduleUpdater->getModulelist();
            
            $remoteModues = $moduleUpdater->getRemoteData();
            
            if (!is_array($remoteModues)) return [];
            
            $modules = [];
            foreach ($remoteModues as $key => $value) {
                
                if (version_compare($this->config->system_version, $value['minsysverion'], '<') || version_compare($this->config->system_version, $value['maxsysverion'], '>') ) continue;
 
                $value['description']       = isset($value['description']) ? $value['description'] : '';
                $value['author']            = isset($value['author']) ? $value['author'] : '';
                $value['link']              = isset($value['link']) ? $value['link'] : '';
                $value['systemMinVersion']  = isset($value['minsysverion']) ? $value['minsysverion'] : '';
                
                $mlConfig = $this->getConfigByModuleKey($key, 'modulelist');
                $moduleItem   = new \fpcm\model\modules\listitem(
                        $key,
                        $value['name'],
                        isset($mlConfig['version']) ? $mlConfig['version'] : '-',
                        $value['version'],
                        $value['description'],
                        $value['author'],
                        $value['link'],
                        $value['systemMinVersion'],
                        $init
                );
                
                if (isset($value['dependencies'])) $moduleItem->setDependencies($value['dependencies']);
                
                $modules[$key] = $moduleItem;
                
            }
            
            return $modules;
        }
        
        /**
         * Gibt Liste mit Modulen zurück, die in lokaler Modul-DB-Tabellen enthalten sind und nicht von Modul-Server angeboten werden
         * @return array
         */
        public function getModulesLocal() {
            
            $this->getModuleLocalFilesystem();
            
            $remoteModules = array_keys($this->getModulesRemote(false));

            if (count($remoteModules)) {
                $where = array_fill(0, count($remoteModules), 'modkey NOT '.$this->dbcon->dbLike().' ?');            
                $localModules = $this->dbcon->fetch($this->dbcon->select($this->table, '*', '('.implode(' OR ', $where).')', $remoteModules), true);
            } else {
                $localModules = $this->dbcon->fetch($this->dbcon->select($this->table, '*'), true);
            }
            
            $modules = [];
            foreach ($localModules as $localModule) {
                
                if (!$localModule->modkey) continue;
                
                $mlConfig = $this->getConfigByModuleKey($localModule->modkey, 'modulelist');
                
                $moduleItem   = new \fpcm\model\modules\listitem(
                        $localModule->modkey,
                        isset($mlConfig['name']) ? $mlConfig['name'] : $localModule->modkey,
                        $localModule->version,
                        '',
                        isset($mlConfig['description']) ? $mlConfig['description'] : '-',
                        isset($mlConfig['author']) ? $mlConfig['author'] : '-',
                        isset($mlConfig['link']) ? $mlConfig['link'] : '-',
                        isset($mlConfig['sysversion']) ? $mlConfig['sysversion'] : $this->config->system_version,
                        false
                );
                
                $moduleItem->setStatus($localModule->status);                
                $moduleItem->setIsInstalled(true);
                $moduleItem->setDependenciesOk($this->checkDepencies($localModule->modkey));
                
                if (isset($modules[$localModule->modkey])) continue;
                
                $modules[$localModule->modkey] = $moduleItem;                
            }
                        
            return $modules; 
        }
        
        /**
         * Module in Datenbank schreiben, welche zwar in Filesystem sind aber noch nicht in DB
         * @return boolean
         */
        protected function getModuleLocalFilesystem() {
            $localsDB = $this->getInstalledModules();
            $localsFs = glob(\fpcm\classes\baseconfig::$moduleDir.'*/*');

            if (!is_array($localsFs) || !count($localsFs)) return false;

            $count = 0;
            
            foreach ($localsFs as &$localFs) {

                $key = \fpcm\model\abstracts\module::getModuleKeyByFolder($localFs.'/config/');

                if (!is_dir($localFs) || in_array($key, $localsDB)) continue;
                
                $mlConfig = $this->getConfigByModuleKey($key, 'modulelist');
                
                $newModule = new listitem(
                    $key,
                    isset($mlConfig['name']) ? $mlConfig['name'] : $key,
                    isset($mlConfig['version']) ? $mlConfig['version'] : '0.0.1',
                    isset($mlConfig['version']) ? $mlConfig['version'] : '0.0.1',
                    isset($mlConfig['description']) ? $mlConfig['description'] : '-',
                    isset($mlConfig['author']) ? $mlConfig['author'] : '-',
                    isset($mlConfig['link']) ? $mlConfig['link'] : '-',
                    '',
                    false
                );

                $newModule->save();
                
                $count++;
            }
            
            if ($count) {
                $this->cache->cleanup(false, \fpcm\model\abstracts\module::FPCM_MODULES_CACHEFOLDER);
            }
            
            return true;
        }

        /**
         * Gibt installierte, aber deaktivierte Module zurück
         * @return array
         */
        public function getDisabledInstalledModules() {

            $cache = new \fpcm\classes\cache(__FUNCTION__, \fpcm\model\abstracts\module::FPCM_MODULES_CACHEFOLDER);
            if (!$cache->isExpired()) {
                return $cache->read();
            }

            $modules = $this->dbcon->fetch($this->dbcon->select($this->table, 'modkey', 'status = 0'), true);
            
            $keys = [];
            foreach ($modules as $module) {
                $keys[] = $module->modkey;
            }

            $cache->write($keys, $this->config->system_cache_timeout);

            return $keys;
        }

        /**
         * Gibt installierte, aber deaktivierte Module zurück
         * @return array
         */
        public function getEnabledInstalledModules() {

            $cache = new \fpcm\classes\cache(__FUNCTION__, \fpcm\model\abstracts\module::FPCM_MODULES_CACHEFOLDER);
            if (!$cache->isExpired()) {
                return $cache->read();
            }

            $modules = $this->dbcon->fetch($this->dbcon->select($this->table, 'modkey', 'status = 1', array(), true), true);

            $keys = [];
            foreach ($modules as $module) {
                $keys[] = $module->modkey;
            }

            $cache->write(array_unique($keys), $this->config->system_cache_timeout);

            return $keys;
        }
        
        /**
         * Gibt installierte Module zurück
         * @return array
         */
        public function getInstalledModules() {

            $cache = new \fpcm\classes\cache(__FUNCTION__, \fpcm\model\abstracts\module::FPCM_MODULES_CACHEFOLDER);
            if (!$cache->isExpired()) {
                return $cache->read();
            }

            $modules = $this->dbcon->fetch($this->dbcon->select($this->table, 'modkey'), true);
            
            $keys = [];
            foreach ($modules as $module) {
                $keys[] = $module->modkey;
            }

            $cache->write($keys, $this->config->system_cache_timeout);

            return $keys;
        }

        /**
         * Mehrere Module deaktivieren
         * @param array $keys
         * @return bool
         */
        public function disableModules(array $keys) {
            $this->cache->cleanup();
            return $this->dbcon->reverseBool($this->table, 'status', "(modkey ".$this->dbcon->dbLike()." '".  implode("' OR modkey ".$this->dbcon->dbLike()." '", $keys)."') AND status = 1");
        }
        
        /**
         * Mehrere Module aktivieren
         * @param array $keys
         * @return bool
         */
        public function enableModules(array $keys) {
            $this->cache->cleanup();
            
            foreach ($keys as $key => $val) {
                if (!$this->checkDepencies($val)) {
                    trigger_error("Dependency error detected for module $val!");
                    unset($keys[$key]);
                }
            }
            
            if (!count($keys)) return false;
            
            return $this->dbcon->reverseBool($this->table, 'status', "(modkey ".$this->dbcon->dbLike()." '".  implode("' OR modkey ".$this->dbcon->dbLike()." '", $keys)."') AND status = 0");
        }
        
        /**
         * Module deinstallieren, nur wenn deaktiviert
         * @param array $keys
         * @return boolean
         */
        public function uninstallModules(array $keys) {

            $this->cache->cleanup();
            
            $keys = array_intersect($keys, $this->getDisabledInstalledModules());
            if (!count($keys)) return false;

            $res = true;
            foreach ($keys as $key) {               
                $moduleClass = \fpcm\model\abstracts\module::getModuleClassName($key);
                if (!class_exists($moduleClass)) continue;
                
                $modObj = new $moduleClass($key, '', '');
                if (!is_a($modObj, '\fpcm\model\abstracts\module')) {
                    continue;
                }
                
                $res = $res && $modObj->runUninstall();                
            }
            
            $res     = $res && $this->dbcon->delete($this->table, "(modkey = '".  implode("' OR modkey = '", $keys)."') AND status = 0");
            $lastKey = false;
            foreach ($keys as $key) {
                $res = $res && \fpcm\model\files\ops::deleteRecursive(\fpcm\classes\baseconfig::$moduleDir.$key);
                $lastKey = $key;
            }
            
            if (!$lastKey) {
                return $res;
            }

            $folders = glob(\fpcm\classes\baseconfig::$moduleDir.dirname($lastKey).'/*', GLOB_ONLYDIR);
            if (!count($folders)) {
                $res = $res && \fpcm\model\files\ops::deleteRecursive(\fpcm\classes\baseconfig::$moduleDir.dirname($lastKey));                    
            }

            return $res;
        }

        /**
         * Config-Dateien anhand von Modul-Key und Dateinamen auslesen
         * @param string $moduleKey
         * @param string $configFile
         * @return array
         */
        public function getConfigByModuleKey($moduleKey, $configFile) {
            
            $path = \fpcm\classes\baseconfig::$moduleDir.$moduleKey.'/config/'.$configFile.'.yml';
            
            if (!file_exists($path)) return [];
            
            include_once \fpcm\classes\loader::libGetFilePath('spyc', 'Spyc.php');
            return \Spyc::YAMLLoad($path);            
        }
        
        /**
         * Abhängigkeiten eines Modules prüfen
         * @param string $moduleKey
         * @return boolean
         */
        public function checkDepencies($moduleKey) {

            if (defined('FPCM_MODULE_IGNORE_DEPENDENCIES') && FPCM_MODULE_IGNORE_DEPENDENCIES) return true;
            
            $dependencies = $this->getConfigByModuleKey($moduleKey, 'dependencies');
            if (!count($dependencies)) return true;

            $depencyModules = $this->dbcon->fetch($this->dbcon->select($this->table, "*", "(modkey ".$this->dbcon->dbLike()." '".  implode("' OR modkey ".$this->dbcon->dbLike()." '", array_keys($dependencies)).')'), true);
            if (!count($depencyModules)) return false;
            
            $res = true;
            foreach ($depencyModules as $depencyModule) {                
                $res = $res && version_compare($depencyModule->version, $dependencies[$depencyModule], '>=');                
            }

            return $res;
            
        }

    }
