<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\classes;

    /**
     * Base config class
     * 
     * @package fpcm\classes\baseconfig
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     */     
    final class baseconfig {
        
        /**
         * Basisordner
         * @var string
         */
        public static $baseDir;
        
        /**
         * core-Verzeichnis
         * @var string
         */
        public static $coreDir;
        
        /**
         * view-Verzeichnis
         * @var string
         */
        public static $viewsDir;
        
        /**
         * data-Verzeichnis
         * @var string
         */
        public static $dataDir;
        
        /**
         * cache-Verzeichnis
         * @var string
         */
        public static $cacheDir;
        
        /**
         * upload-Verzeichnis
         * @var string
         */
        public static $uploadDir;
        
        /**
         * log-Verzeichnis
         * @var string
         */
        public static $logDir;
        
        /**
         * Revision-Verzeichnis
         * @var string
         */
        public static $revisionDir;
        
        /**
         * temp-Verzeichnis
         * @var string
         */
        public static $tempDir;
        
        /**
         * config-Verzeichnis
         * @var string
         */
        public static $configDir;
        
        /**
         * Filemanager temp-Verzeichnis
         * @var string
         */
        public static $filemanagerTempDir;
        
        /**
         * sharebutton-Verzeichnis
         * @var string
         */
        public static $shareDir;
        
        /**
         * smiley-Verzeichnis
         * @var string
         */
        public static $smileyDir;
        
        /**
         * styles-Verzeichnis
         * @var string
         */
        public static $stylesDir;
        
        /**
         * Update-Server-URL
         * @var string
         */
        public static $updateServer;
        
        /**
         * Link für manuelle Update-Prüfung
         * @var string
         */
        public static $updateServerManualLink;
        
        /**
         * Module-Server-URL
         * @var string
         */
        public static $moduleServer;
        
        /**
         * Link für manuellen Module-Manager
         * @var string
         */
        public static $moduleServerManualLink;

        /**
         * Include-Basis-Verzeichnis
         * @var string
         */
        public static $incDir;
        
        /**
         * Modul-Basis-Verzeichnis
         * @var string
         */
        public static $moduleDir;
        
        /**
         * Dashboard-Conatiner-Verzeichnis
         * @var string
         */
        public static $dashcontainerDir;
        
        /**
         * Sprachpaket-Verzeichnis
         * @var string
         */
        public static $langDir;
        
        /**
         * Verzeichnis für automatisch, via Cron erzeugte Datenbank-Dumps
         * @var string
         */
        public static $dbdumpDir;
        
        /**
         * Verzeichnis für Template-Vorlagen für Editor
         * @var string
         * @since FPCM 3.3
         */
        public static $articleTemplatesDir;
        
        /**
         * root-URL
         * @var string
         */
        public static $rootPath;
        
        /**
         * root-Pfad-URL
         * @var string
         */
        public static $uploadRootPath;
        
        /**
         * sharebutton-URL
         * @var string
         */
        public static $shareRootPath;
        
        /**
         * smiley-URL
         * @var string
         */
        public static $smileyRootPath;
        
        /**
         * theme-URL
         * @var string
         */
        public static $themePath;
        
        /**
         * Javascript-URL - öffentlich
         * @var string
         */
        public static $jsPath;
        
        /**
         * Dateimanager-Temp-URL
         * @var string
         */
        public static $filemanagerRootPath;
        
        /**
         * Pfad zu system-eigenen SQL-Dateien
         * @var string
         * @since FPCM 3.2.0
         */
        public static $dbStructPath;
        
        /**
         * Profile-Daten-Pfad
         * @var string
         * @since FPCM 3.6
         */
        public static $profilePath;
        
        /**
         * auszuschließende Ordner
         * @var array
         */
        public static $folderExcludes   = array('.', '..');
        
        /**
         * Logdatein
         * @var array
         */
        public static $logFiles         = [];
        
        /**
         * Controller-Dateien
         * @var array
         */
        public static $controllerFiles  = [];
        
        /**
         * Datetime-Masken
         * @var array
         */
        public static $dateTimeMasks    = [
            'd.m.Y, H:i',
            'd. M Y, H:i',
            'd.n.Y H:i',
            'j. M Y H:i',
            'j.n.Y H:i',
            'M dS Y - h:ia',
            'm/d/Y - h:ia',
            'M jS Y - h:ia',
            'n/d/Y - h:ia'
        ];

        /**
         * Version-Datei
         * @var string
         */
        public static $versionFile;
        
        /**
         * Eventliste
         * @var \fpcm\model\events\eventList
         */
        public static $fpcmEvents;
        
        /**
         * Zentrales Config-Event
         * @var \fpcm\model\system\config
         */
        public static $fpcmConfig;
        
        /**
         * Zentrales Language-Objekt
         * @var language
         */
        public static $fpcmLanguage;
        
        /**
         * Zentrales Session Objekt
         * @var \fpcm\model\system\session
         */
        public static $fpcmSession;
        
        /**
         * Datenbank-Objekt
         * @var database
         */
        public static $fpcmDatabase;
        
        /**
         * Notifications-Objekt
         * @var \fpcm\model\theme\notifications
         */
        public static $fpcmNotifications;

        /**
         * Installer aktiv Status-Datei
         * @var string
         */
        private static $installerEnabledFile;
        
        /**
         * asynchrone Cronjob-Ausführung Status-Datei
         * @var string 
         */
        private static $cronAsyncFile;
        
        /**
         * Zwischenspecher für bestimmte Config-Informationen
         * @var array
         * @since FPCM 3.5
         */
        private static $cfgDat = [];

        /**
         * Initiiert Grundsystem
         */
        public static function init() {

            self::$baseDir             = dirname(dirname(__DIR__));                        
            self::$dataDir             = self::$baseDir.'/data/';
            self::$cacheDir            = self::$dataDir.'cache/';
            self::$configDir           = self::$dataDir.'config/';
            self::$filemanagerTempDir  = self::$dataDir.'filemanager/';
            self::$logDir              = self::$dataDir.'logs/';
            self::$revisionDir         = self::$dataDir.'revisions/';
            self::$shareDir            = self::$dataDir.'share/';
            self::$smileyDir           = self::$dataDir.'smileys/';
            self::$stylesDir           = self::$dataDir.'styles/';
            self::$tempDir             = self::$dataDir.'temp/';
            self::$uploadDir           = self::$dataDir.'uploads/';
            self::$dbdumpDir           = self::$dataDir.'dbdump/';
            self::$dbStructPath        = self::$dataDir.'dbstruct/';
            self::$articleTemplatesDir = self::$dataDir.'drafts/';
            self::$profilePath         = self::$dataDir.'profiles/';
            
            self::$coreDir             = self::$baseDir.'/core/';
            self::$viewsDir            = self::$coreDir.'views/';
            
            self::$incDir              = self::$baseDir.'/inc/';
            self::$moduleDir           = self::$incDir.'modules/';
            self::$langDir             = self::$incDir.'lang/';
            self::$dashcontainerDir    = self::$incDir.'model/dashboard/';

            if (php_sapi_name() !== 'cli') {
                $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
                self::$rootPath = $http.$_SERVER['HTTP_HOST'].rtrim(dirname(dirname($_SERVER['PHP_SELF'])), '/').'/'.basename(self::$baseDir).'/';
            }
            

            self::$uploadRootPath      = self::$rootPath.basename(self::$dataDir).'/uploads/';
            self::$shareRootPath       = self::$rootPath.basename(self::$dataDir).'/share/';
            self::$smileyRootPath      = self::$rootPath.basename(self::$dataDir).'/smileys/';
            self::$themePath           = self::$rootPath.basename(self::$coreDir).'/theme/';
            self::$jsPath              = self::$rootPath.basename(self::$coreDir).'/js/';
            self::$filemanagerRootPath = self::$rootPath.basename(self::$dataDir).'/filemanager/';

            self::initServers();
            
            self::$logFiles            = array(
                'phplog'    => self::$logDir.'phplog.txt',
                'syslog'    => self::$logDir.'syslog.txt',
                'dblog'     => self::$logDir.'dblog.txt',
                'pkglog'    => self::$logDir.'packages.txt',
                'cronlog'   => self::$logDir.'cronlog.txt'
            );
            
            
            self::$controllerFiles      = array(
                'actions'   => self::$configDir.'actionControllers.yml',
                'ajax'      => self::$configDir.'ajaxControllers.yml'
            );
            
            self::$versionFile          = self::$baseDir.'/version.php';
            
            self::$installerEnabledFile = self::$configDir.'installer.enabled';
            
            self::$cronAsyncFile        = self::$tempDir.'cronjob.disabled';
            
            self::$fpcmEvents           = new \fpcm\model\events\eventList();

            if (self::dbConfigExists()) {
                self::$fpcmDatabase     = new \fpcm\classes\database();                
            }
            
            self::$fpcmConfig           = new \fpcm\model\system\config();
            
            self::$fpcmLanguage         = new language(self::$fpcmConfig->system_lang);
            
            self::$fpcmSession          = new \fpcm\model\system\session();

            self::$fpcmNotifications    = new \fpcm\model\theme\notifications();
            
        }

        /**
         * Lädt config.php
         * @return array
         */
        public static function getDatabaseConfig() {
            if (!file_exists(self::$configDir.'database.php')) return [];            
            
            include self::$configDir.'database.php';
            return $config;            
        }

        /**
         * Lädt crypt.php
         * @return array
         * @since FPCM 3.5
         */
        public static function getCryptConfig() {
            if (!file_exists(self::$configDir.'crypt.php')) return null;
            
            include self::$configDir.'crypt.php';
            return $config;            
        }

        /**
         * Lädt sec.php
         * @return array
         * @since FPCM 3.6
         */
        public static function getSecurityConfig() {
            if (!file_exists(self::$configDir.'sec.php')) return null;
            
            include self::$configDir.'sec.php';
            return $config;            
        }

        /**
         * allow_url_fopen = 1
         * @return bool
         */
        public static function canConnect() {

            if (!isset(self::$cfgDat[__FUNCTION__])) {
                self::$cfgDat[__FUNCTION__] = (ini_get('allow_url_fopen') == 1) ? true : false;
            }
            
            return self::$cfgDat[__FUNCTION__];

        }

        /**
         * allow_url_fopen = 1
         * @return bool
         */
        public static function canCrypt() {
            
            if (!isset(self::$cfgDat[__FUNCTION__])) {
                self::$cfgDat[__FUNCTION__] = function_exists('openssl_encrypt') && function_exists('openssl_decrypt');
            }

            return self::$cfgDat[__FUNCTION__];
        }

        /**
         * HTTPS aktiv
         * @return bool
         * @since FPCM 3.5
         */
        public static function canHttps() {
            
            if (!isset(self::$cfgDat[__FUNCTION__])) {
                self::$cfgDat[__FUNCTION__] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? true : false);
            }

            return self::$cfgDat[__FUNCTION__];
        }
        
        /**
         * PHP Memory Limit
         * @param bool $inByte Ausgabe in Byte oder Mbyte
         * @return int
         * @since FPCM 3.3
         */
        public static function memoryLimit($inByte = false) {

            if (!isset(self::$cfgDat[__FUNCTION__])) {
                self::$cfgDat[__FUNCTION__] = (int) substr(ini_get('memory_limit'), 0, -1);
            }

            return $inByte ? self::$cfgDat[__FUNCTION__] * 1024 * 1024 : self::$cfgDat[__FUNCTION__];
        }
        
        /**
         * PHP Upload filesize limit
         * @param bool $inByte Ausgabe in Byte oder Mbyte
         * @return int
         * @since FPCM 3.3
         */
        public static function uploadFilesizeLimit($inByte = false) {

            if (!isset(self::$cfgDat[__FUNCTION__])) {
                self::$cfgDat[__FUNCTION__] = (int) substr(ini_get('upload_max_filesize'), 0, -1);
            }

            return $inByte ? self::$cfgDat[__FUNCTION__] * 1024 * 1024 : self::$cfgDat[__FUNCTION__];

        }

        /**
         * Controller abrufen
         * @return array
         */
        public static function getControllers() {
            
            $controllerCache = new cache('controllerCache', 'system');
            
            if (!$controllerCache->isExpired()) {
                $controllerList = $controllerCache->read();
                if (is_array($controllerList)) {
                    return $controllerList;
                }                
            }

            include_once loader::libGetFilePath('spyc', 'Spyc.php');

            if (!file_exists(self::$controllerFiles['actions']) || !file_exists(self::$controllerFiles['ajax'])) {
                die('ERROR: Controller config files not found.');
            }
            
            $actions = \Spyc::YAMLLoad(self::$controllerFiles['actions']);
            $ajaxs   = \Spyc::YAMLLoad(self::$controllerFiles['ajax']);
            $modules = self::initModuleControllers();

            $controllerList = array_unique(array_merge($actions, $ajaxs, $modules));
            
            $controllerCache->write($controllerList, FPCM_LANGCACHE_TIMEOUT);
            
            return $controllerList;
        }
        
        /**
         * Prüft ob Datenbank-Config-Datei existiert
         * @return bool
         */
        public static function dbConfigExists() {

            if (!isset(self::$cfgDat[__FUNCTION__])) {
                self::$cfgDat[__FUNCTION__] = file_exists(self::$configDir.'database.php');
            }

            return self::$cfgDat[__FUNCTION__];
        }
        
        /**
         * Prüft ob Installer aktiv ist
         * @return bool
         */
        public static function installerEnabled() {

            if (defined('FPCM_IGNORE_INSTALLER_DISABLED') && FPCM_IGNORE_INSTALLER_DISABLED) return true;
            
            return file_exists(self::$installerEnabledFile);
        }
        
        /**
         * Aktiviert bzw. deaktiviert Installer
         * @param bool $status neuer Status
         * @return bool
         */
        public static function enableInstaller($status) {

            if (self::installerEnabled() && !$status) {
                return unlink(self::$installerEnabledFile);
            }
            
            return file_put_contents(self::$installerEnabledFile, '');            
        }
        
        /**
         * Prüft ob Ausführung von asynchronen Cronjobs aktiv ist
         * @return bool
         */
        public static function asyncCronjobsEnabled() {
            return file_exists(self::$cronAsyncFile) ? false : true;
        }
        
        /**
         * Aktiviert bzw. deaktiviert asynchrone Cronjob-Ausführung
         * @param bool $status neuer Status
         * @return bool
         */
        public static function enableAsyncCronjobs($status) {

            if (self::asyncCronjobsEnabled() && !$status) {
                fpcmLogSystem('Asynchronous cron job execution disabled.');
                return file_put_contents(self::$cronAsyncFile, '');
            }

            if (!file_exists(self::$cronAsyncFile)) {
                return false;
            }
            
            fpcmLogSystem('Asynchronous cron job execution enabled.');
            return unlink(self::$cronAsyncFile);            
        }

        /**
         * Initialisiert Server-Infos
         */
        private static function initServers(){
            
            include_once loader::libGetFilePath('spyc', 'Spyc.php');
            
            $servers = \Spyc::YAMLLoad(self::$configDir.'servers.yml');

            self::$updateServer = $servers['updates'];
            self::$updateServerManualLink = $servers['updatesManual'];
            self::$moduleServer = $servers['modules'];
            self::$moduleServerManualLink = $servers['modulesManual'];
        }
        
        /**
         * Registriert Controller-Configs aus controllers.yml
         * @return aarray
         */
        private static function initModuleControllers() {
            $moduleConfigs = glob(self::$moduleDir.'*/*/config/controllers.yml');
            if (!$moduleConfigs || !count($moduleConfigs)) return [];

            $modules = [];
            foreach ($moduleConfigs as $moduleConfig) {
                $modules = array_merge($modules, \Spyc::YAMLLoad($moduleConfig));
            }
            
            return $modules;
        }
    }
?>