<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * Base config class
 * 
 * @package fpcm\classes\baseconfig
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 */
final class baseconfig {

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
     * auszuschließende Ordner
     * @var array
     */
    public static $folderExcludes = array('.', '..');

    /**
     * Logdatein
     * @var array
     */
    public static $logFiles = [];

    /**
     * Datetime-Masken
     * @var array
     */
    public static $dateTimeMasks = [
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
     * Installer aktiv Status-Datei
     * @var string
     */
    private static $installerEnabledFile;

    /**
     * Zwischenspecher für bestimmte Config-Informationen
     * @var array
     * @since FPCM 3.5
     */
    private static $cfgDat = [];

    /**
     * Initiiert Grundsystem
     */
    public static function init()
    {
        self::$logFiles = array(
            'phplog' => dirs::getDataDirPath(dirs::DATA_LOGS, 'phplog.txt'),
            'syslog' => dirs::getDataDirPath(dirs::DATA_LOGS, 'syslog.txt'),
            'dblog' => dirs::getDataDirPath(dirs::DATA_LOGS, 'dblog.txt'),
            'pkglog' => dirs::getDataDirPath(dirs::DATA_LOGS, 'packages.txt'),
            'cronlog' => dirs::getDataDirPath(dirs::DATA_LOGS, 'cronlog.txt'),
            'eventslogs' => dirs::getDataDirPath(dirs::DATA_LOGS, 'events.txt')
        );

        self::$versionFile = dirs::getFullDirPath('version.php');
        self::$installerEnabledFile = dirs::getDataDirPath(dirs::DATA_CONFIG, 'installer.enabled');

        if (self::dbConfigExists()) {
            loader::getObject('\fpcm\classes\database');
            loader::getObject('\fpcm\classes\crypt');
            loader::getObject('\fpcm\modules\modules')->getEnabledDatabase();

            $config  = loader::getObject('\fpcm\model\system\config');
            
            /* @var $session \fpcm\model\system\session */
            $session = loader::getObject('\fpcm\model\system\session');
            loader::getObject('\fpcm\model\system\config')->setUserSettings();
            loader::getObject('\fpcm\classes\language', $config->system_lang);
            loader::getObject('\fpcm\model\theme\notifications');
            loader::getObject('\fpcm\model\system\permissions', ($session->exists() ? $session->getCurrentUser()->getRoll() : 0));
        }


        self::initServers();
    }

    /**
     * Lädt config.php
     * @return array
     */
    public static function getDatabaseConfig()
    {
        $path = dirs::getDataDirPath(dirs::DATA_CONFIG, 'database.php');
        if (!file_exists($path)) {
            return [];
        }

        include $path;
        return $config;
    }

    /**
     * Lädt crypt.php
     * @return array
     * @since FPCM 3.5
     */
    public static function getCryptConfig()
    {
        $path = dirs::getDataDirPath(dirs::DATA_CONFIG, 'crypt.php');
        if (!file_exists($path)) {
            return [];
        }

        include $path;
        return $config;
    }

    /**
     * Lädt sec.php
     * @return array
     * @since FPCM 3.6
     */
    public static function getSecurityConfig()
    {
        $path = dirs::getDataDirPath(dirs::DATA_CONFIG, 'sec.php');
        if (!file_exists($path)) {
            return [];
        }

        include $path;
        return $config;
    }

    /**
     * Lädt version.php
     * @return string
     * @since FPCM 4
     */
    public static function getVersionFromFile()
    {
        include self::$versionFile;
        return $fpcmVersion;
    }

    /**
     * allow_url_fopen = 1
     * @return bool
     */
    public static function canConnect()
    {
        if (!isset(self::$cfgDat[__FUNCTION__])) {
            self::$cfgDat[__FUNCTION__] = (ini_get('allow_url_fopen') == 1) ? true : false;
        }

        return self::$cfgDat[__FUNCTION__];
    }

    /**
     * allow_url_fopen = 1
     * @return bool
     */
    public static function canCrypt()
    {
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
    public static function canHttps()
    {
        if (!isset(self::$cfgDat[__FUNCTION__])) {
            self::$cfgDat[__FUNCTION__] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? true : false);
        }

        return self::$cfgDat[__FUNCTION__];
    }

    /**
     * Aufruf über CLI
     * @return bool
     * @since FPCM 4.0
     */
    public static function isCli()
    {
        if (!isset(self::$cfgDat[__FUNCTION__])) {
            self::$cfgDat[__FUNCTION__] = (php_sapi_name() === 'cli' ? true : false);
        }

        return self::$cfgDat[__FUNCTION__];
    }

    /**
     * PHP Memory Limit
     * @param bool $inByte Ausgabe in Byte oder Mbyte
     * @return int
     */
    public static function memoryLimit($inByte = false)
    {

        if (!isset(self::$cfgDat[__FUNCTION__])) {
            self::$cfgDat[__FUNCTION__] = (int) substr(ini_get('memory_limit'), 0, -1);
        }

        return $inByte ? self::$cfgDat[__FUNCTION__] * 1024 * 1024 : self::$cfgDat[__FUNCTION__];
    }

    /**
     * PHP Upload filesize limit
     * @param bool $inByte Ausgabe in Byte oder Mbyte
     * @return int
     */
    public static function uploadFilesizeLimit($inByte = false)
    {
        if (!isset(self::$cfgDat[__FUNCTION__])) {
            self::$cfgDat[__FUNCTION__] = (int) substr(ini_get('upload_max_filesize'), 0, -1);
        }

        return $inByte ? self::$cfgDat[__FUNCTION__] * 1024 * 1024 : self::$cfgDat[__FUNCTION__];
    }

    /**
     * Controller abrufen
     * @return array
     */
    public static function getControllers()
    {
        $cacheName = 'system/controllerCache';

        $controllerCache = new cache();

        if (!$controllerCache->isExpired($cacheName)) {
            $controllerList = $controllerCache->read($cacheName);
            if (is_array($controllerList)) {
                return $controllerList;
            }
        }

        include_once loader::libGetFilePath('spyc/Spyc.php');

        $controller = [];

        $controllerFiles = glob(dirs::getDataDirPath(dirs::DATA_CONFIG, '*Controllers.yml'));
        foreach ($controllerFiles as $controllerFile) {
            $controller = array_merge($controller, \Spyc::YAMLLoad($controllerFile));
        }

        $controller = array_unique(array_merge($controller, self::initModuleControllers()));
        $controllerCache->write($cacheName, $controller, FPCM_CACHE_DEFAULT_TIMEOUT);

        return $controller;
    }

    /**
     * Prüft ob Datenbank-Config-Datei existiert
     * @return bool
     */
    public static function dbConfigExists()
    {
        if (!isset(self::$cfgDat[__FUNCTION__])) {
            self::$cfgDat[__FUNCTION__] = count(self::getDatabaseConfig()) ? true : false;
        }

        return self::$cfgDat[__FUNCTION__];
    }

    /**
     * Prüft ob Installer aktiv ist
     * @return bool
     */
    public static function installerEnabled()
    {
        if (defined('FPCM_INSTALLER_ENABLED')) {
            return FPCM_INSTALLER_ENABLED;
        }

        return file_exists(self::$installerEnabledFile);
    }

    /**
     * Aktiviert bzw. deaktiviert Installer
     * @param bool $status neuer Status
     * @return bool
     */
    public static function enableInstaller($status)
    {
        if (self::installerEnabled() && !$status) {
            return unlink(self::$installerEnabledFile);
        }

        return file_put_contents(self::$installerEnabledFile, '');
    }

    /**
     * Prüft ob Ausführung von asynchronen Cronjobs aktiv ist
     * @return bool
     */
    public static function asyncCronjobsEnabled()
    {
        $return = (new \fpcm\model\files\fileOption('cronasync'))->read();
        if ($return === null) {
            return true;
        }

        return $return;
    }

    /**
     * Aktiviert bzw. deaktiviert asynchrone Cronjob-Ausführung
     * @param bool $status neuer Status
     * @return bool
     */
    public static function enableAsyncCronjobs($status)
    {
        $fopt = new \fpcm\model\files\fileOption('cronasync');
        if ($fopt->write($status) && !$status) {
            fpcmLogSystem('Asynchronous cron job execution disabled.');
            return true;
        }

        fpcmLogSystem('Asynchronous cron job execution enabled.');
        return true;
    }

    /**
     * Initialisiert Server-Infos
     */
    private static function initServers()
    {
        include_once loader::libGetFilePath('spyc/Spyc.php');

        $servers = \Spyc::YAMLLoad(dirs::getDataDirPath(dirs::DATA_CONFIG, 'servers.yml'));

        self::$updateServer = $servers['updates'];
        self::$moduleServer = $servers['modules'];
        self::$updateServerManualLink = $servers['updatesManual'];
        self::$moduleServerManualLink = $servers['modulesManual'];
    }

    /**
     * Registriert Controller-Configs aus controllers.yml
     * @return aarray
     */
    private static function initModuleControllers()
    {
        return [];

        $moduleConfigs = glob(dirs::getDataDirPath(dirs::DATA_MODULES, '*/*/config/controllers.yml'));
        if (!$moduleConfigs || !count($moduleConfigs))
            return [];

        $modules = [];
        foreach ($moduleConfigs as $moduleConfig) {
            $modules = array_merge($modules, \Spyc::YAMLLoad($moduleConfig));
        }

        return $modules;
    }

}

?>