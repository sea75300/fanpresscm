<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli help module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5.1
 */
final class pkg extends \fpcm\model\abstracts\cli {

    /**
     *
     * @var \fpcm\model\updater\system
     */
    private $updaterSys;

    /**
     *
     * @var \fpcm\model\updater\modules
     */
    private $updaterMod;

    /**
     *
     * @var string
     */
    private $modulekey;

    /**
     *
     * @var array
     */
    private $noMaintenanceMode = [
        self::FPCMCLI_PARAM_UPDATE,
        self::FPCMCLI_PARAM_LIST,
        self::FPCMCLI_PARAM_INFO
    ];

    /**
     * 
     * @return boolean
     */
    private function initObjects()
    {
        $this->updaterSys = new \fpcm\model\updater\system();
        $this->updaterMod = new \fpcm\model\updater\modules();
        return true;
    }

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {
        if (!in_array($this->funcParams[0], $this->noMaintenanceMode)) {
            $this->output('Enable maintenance mode...');
            $this->config->setMaintenanceMode(true);
            $this->output('-- Finished.'.PHP_EOL);
        }

        if (!trim($this->funcParams[0])) {
            $this->output('Invalid parameter on position 0', true);
        }
        
        $fn = 'process'. ucfirst( str_replace('-', '', trim($this->funcParams[0])) ). ( isset($this->funcParams[1]) && trim($this->funcParams[1]) ? ucfirst( trim($this->funcParams[1]) ) : '' );

        if (defined('FPCM_DEBUG') && FPCM_DEBUG) {
            $this->output('> CLI DEBUG: '.$fn.PHP_EOL);
            $this->output('> CLI DEBUG: '. print_r($this->funcParams, true).PHP_EOL);
        }
        
        if (!method_exists($this, $fn)) {
            $this->output('Invalid parameters', true);
        }

        if (!call_user_func([$this, $fn])) {
            $this->output('Processing error, see error log for further information.', true);
        }

        if (!in_array($this->funcParams[0], $this->noMaintenanceMode)) {
            $this->output('Disable maintenance mode...');
            $this->config->setMaintenanceMode(false);
            $this->output('-- Finished.'.PHP_EOL);
        }

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processUpdate()
    {
        $this->initObjects();
        
        $this->output('Fetch package information from repository...');
        $repository = new \fpcm\model\packages\repository();
        $successRepo = $repository->fetchRemoteData(true);

        if (!$successRepo) {
            $this->output('Unable to fetch package data informations.', true);
        }

        $this->output('Check for updates...');
        $successSys = $this->updaterSys->updateAvailable();

        if (!$successRepo) {
            $this->output('Unable to sync update package informations. Check error log for further information.' . PHP_EOL . 'Error Code ' . $successSys, true);
        }

        $successMod = $this->updaterMod->getData();
        if (!$successMod) {
            $this->output('Unable to sync module package informations. Check error log for further information.' . PHP_EOL . 'Error Code ' . $successMod, true);
        }

        $modules = new \fpcm\module\modules();
        $updates = $modules->getInstalledUpdates();
        
        $this->output('-- successfull!');       
        $this->output('FanPress CM '.$this->updaterSys->version.' version was relesed on ' . $this->updaterSys->release);
        if ($successSys === \fpcm\model\updater\system::FORCE_UPDATE) {
            $this->output('-- This released is forced to be installed, you should run fpcmcli.php pkg --update system as soon as possible.');
        }

        if ($successSys && $this->updaterSys->phpversion && version_compare(phpversion(), $this->updaterSys->phpversion, '<') ) {
            $this->output('-- This released requires PHP '.$this->updaterSys->phpversion.' or better, you current PHP version is '.phpversion());
        }

        $count = count($updates);
        $this->output('Modules: '. $count.' module updates are available.'.($count ? '-- Module keys are:'.PHP_EOL.implode(', ', $updates) : ''));
                
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processUpgradeSystem()
    {
        $this->output('Start system update...');

        $pkg = new \fpcm\model\packages\update(basename($this->updaterSys->url));

        $this->output('Check local file system...');
        $success = $pkg->checkFiles();
        
        if ($success !== true) {
            $this->output('Local file system check failed, one or more files are not wriatble. ERROR CODE: ' . (int) $success, true);
        }        
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('Download package from ' . $pkg->getRemotePath() . '...');
        $success = $pkg->download();
        if ($success !== true) {
            $this->output('Download failed. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);
        
        $this->output('Check package integity ' . basename($pkg->getLocalPath()) . '...');
        $success = $pkg->checkPackage();
        if ($success !== true) {
            $this->output('Package integity check for '.basename($pkg->getLocalPath()).' failed. ERROR CODE: '.$success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('Unpacking package file ' . basename($pkg->getLocalPath()) . '...');
        $success = $pkg->extract();
        if ($success !== true) {
            $this->output('Package extraction for '.basename($pkg->getLocalPath()).' was not successful. ERROR CODE: '.$success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('Update files in local file systems...');
        $success = $pkg->copy();
        if ($success !== true) {
            $this->output('Update of local file system from '.basename($pkg->getLocalPath()).' was not successful. ERROR CODE: '.$success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        $this->processUpgradedbSystem();

        $this->output('Update package manager logfile...');
        $success = $pkg->updateLog();
        if ($success !== true) {
            $this->output('Package manager log update failed. ERROR CODE: '.$success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('Perform system cleanup after update...');
        $pkg->cleanup();
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('System update successful. New version: ' . $this->config->system_version.PHP_EOL);
        $this->output('Please check error log and data folder permissions.'.PHP_EOL);
        return true;
    }

    /**
     * Run update finalizer
     * @return boolean
     */
    private function processUpgradedbSystem()
    {
        $this->output('Update local database...');
        
        $finalizer = new \fpcm\model\updater\finalizer();
        $success = $finalizer->runUpdate();
        if ($success !== true) {
            $this->output('An error occurred during Database update. ERROR CODE: '.$success, true);
        }

        $this->config->init();
        $this->output('-- Finished.'.PHP_EOL);

        return true;
    }
    
    private function processListUpdatefs()
    {
        $this->output('Update local module database from file system...');
        $modules = new \fpcm\module\modules();
        
        if (!$modules->updateFromFilesystem()) {
            $this->output('Failed to update module database from file system ', true);
        }

        $this->output('-- Finished.'.PHP_EOL);
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processListLocal()
    {
        $list = (new \fpcm\module\modules())->getFromDatabase();
        if (!count($list)) {
            $this->output('No modules installed, exit...', true);
        }
        
        /* @var $module \fpcm\module\module */
        foreach ($list as $module) {
            $this->moduleslIstDetails($module, true);
            $this->output('     Update available: '.$this->boolText($module->hasUpdates()));
            $this->output(PHP_EOL);
        }

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processListRemote()
    {
        $list = (new \fpcm\module\modules())->getFromRepository();
        if (!count($list)) {
            $this->output('No modules installed, exit...', true);
        }
        
        /* @var $module \fpcm\module\repoModule */
        foreach ($list as $module) {
            $this->moduleslIstDetails($module, true);
            $this->output(PHP_EOL);
        }

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processInfoModule()
    {
        $this->initObjects();

        $this->getModuleKey();
        $this->moduleslIstDetails(new \fpcm\module\module($this->modulekey), true, true);
        return true;
    }

    /**
     * 
     * @param \fpcm\module\module $module
     * @param bool $remote
     * @param bool $descr
     */
    private function moduleslIstDetails($module, $remote = false, $descr = false)
    {
        $this->output('>> '.$module->getConfig()->name);
        $this->output($module->getKey().' - '.$module->getConfig()->version.' - '.$module->getConfig()->author.' - '.$module->getConfig()->link.PHP_EOL);

        if ($descr) {
            $this->output('-- Description:'.PHP_EOL.wordwrap(strip_tags($module->getConfig()->description)).PHP_EOL);
            $this->output('-- Required: ');
            $this->output('     System: '.$module->getConfig()->requirements['system']);
            $this->output('     PHP: '.$module->getConfig()->requirements['php']);
        }

        $this->output('-- Status: ');
        $this->output('     Installable: '.$this->boolText($module->isInstallable()).( $remote && !$module->isInstalled() ? ', Command: fpcmcli.php pkg --install module '.$module->getKey() :  '') );
        $this->output('     Installed: '.$this->boolText($module->isInstalled()));
        
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processInstallModule()
    {
        $this->initObjects();

        $this->getModuleKey();
        $this->output('Start installation of module '.$this->modulekey);

        $this->processModulePackage('install');
        $this->output('Installation of module '.$this->modulekey.' successfull.'.PHP_EOL);

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processUpgradeModule()
    {
        $this->initObjects();

        $this->getModuleKey();
        $this->output('Start update of module '.$this->modulekey);

        $this->processModulePackage('update', true);
        $this->output('Update of module '.$this->modulekey.' successfull.'.PHP_EOL);

        return true;
    }

    /**
     * 
     * @param string $mode
     * @param bool $checkFiles
     * @return boolean
     */
    private function processModulePackage($mode, $checkFiles = false)
    {
        $pkg = new \fpcm\model\packages\module($this->modulekey);
        
        if ($checkFiles) {
            $this->output('Check local file system...');
            $success = $pkg->checkFiles();

            if ($success !== true) {
                $this->output('Local file system check failed, one or more files are not wriatble. ERROR CODE: ' . (int) $success, true);
            }        
            $this->output('-- Finished.'.PHP_EOL);
        }


        $this->output('Download package from ' . $pkg->getRemotePath() . '...');
        $success = $pkg->download();
        if ($success !== true) {
            $this->output('Download failed. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);
        
        $this->output('Check package integity ' . basename($pkg->getLocalPath()) . '...');
        $success = $pkg->checkPackage();
        if ($success !== true) {
            $this->output('Package integity check for '.basename($pkg->getLocalPath()).' failed. ERROR CODE: '.$success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('Unpacking package file ' . basename($pkg->getLocalPath()) . '...');
        $success = $pkg->extract();
        if ($success !== true) {
            $this->output('Package extraction for '.basename($pkg->getLocalPath()).' was not successful. ERROR CODE: '.$success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('Update files in local file systems...');
        $success = $pkg->copy();
        if ($success !== true) {
            $this->output('Update of local file system from '.basename($pkg->getLocalPath()).' was not successful. ERROR CODE: '.$success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('Update local database...');
        $module = new \fpcm\module\module($this->modulekey);
        if (!method_exists($module, $mode)) {
            fpcmLogSystem('Undefined function '.$mode.' for module database update '.$this->modulekey.'!');
            return true;
        }

        $success = call_user_func([$module, $mode]);
        if ($success !== true) {
            $this->output('An error occurred during Database update. ERROR CODE: '.$success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('Update package manager logfile...');
        $success = $pkg->updateLog();
        if ($success !== true) {
            $this->output('Package manager log update failed. ERROR CODE: '.$success, true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        $this->output('Perform system cleanup after update...');
        $pkg->cleanup();
        $this->output('-- Finished.'.PHP_EOL);
        
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function getModuleKey()
    {
        if (empty($this->funcParams[2])) {
            $this->output('Invalid module package on position 3', true);
        }
        
        $this->modulekey = $this->updaterMod->getDataCachedByKey($this->funcParams[2]);
        if (!$this->modulekey) {
            $this->output('Invalid module key, key not found', true);
        }
        
        $this->modulekey = $this->funcParams[2];
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processRemoveModule()
    {
        $this->initObjects();

        $this->getModuleKey();
        $this->output('Remove module '.$this->modulekey);

        $module = new \fpcm\module\module($this->modulekey);
        if ($module->uninstall() !== true) {
            $this->output('Failed to remove, see error log for fruther information!', true);
        }
        $this->output('-- Finished.'.PHP_EOL);

        return true;
    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {
        $lines = [];
        $lines[] = '> Package manager:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php pkg <action params> system';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php pkg <action params> module <module key>';
        $lines[] = '';
        $lines[] = '> Action params:';
        $lines[] = '';
        $lines[] = '      --update      updates local package list storage';
        $lines[] = '      --upgrade     upgrades files in local file system and performs database changes from given package';
        $lines[] = '      --upgrade-db  performs database changes from given package';
        $lines[] = '      --install     performs setup of a given package (modules only)';
        $lines[] = '      --remove      performs removal of a given package (modules only)';
        $lines[] = '      --info        displays information about a given package (modules only)';
        $lines[] = '      --list        displays list available packages (modules only)';
        $lines[] = '        local       local installed modules';
        $lines[] = '        updatefs    update local installed modules from file system';
        $lines[] = '        remote      modules in package repository';
        return $lines;
    }

}
