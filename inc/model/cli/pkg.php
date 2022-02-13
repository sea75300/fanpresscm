<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

use fpcm\model\packages\repository;
use fpcm\model\updater\modules;
use fpcm\model\updater\system;

/**
 * FanPress CM cli help module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.5.1
 */
final class pkg extends \fpcm\model\abstracts\cli {

    /**
     * System updater object
     * @var \fpcm\model\updater\system
     */
    private $updaterSys;

    /**
     * Module updater object
     * @var \fpcm\model\updater\modules
     */
    private $updaterMod;

    /**
     * Moduel key string
     * @var string
     */
    private $modulekey;

    /**
     * Internal execution via system
     * @var bool
     */
    private $exSystem;

    /**
     * List of actions without maintenace mode enabled
     * @var array
     */
    private $noMaintenanceMode = [
        self::PARAM_UPDATE,
        self::PARAM_LIST,
        self::PARAM_INFO
    ];

    /**
     * Initialize objects
     * @return bool
     */
    private function initObjects()
    {
        $this->updaterSys = new system();
        $this->updaterMod = new modules();
        return true;
    }

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {

        if (!trim($this->funcParams[0])) {
            $this->output('Invalid parameter on position 0', true);
        }
        
        if (!isset($this->funcParams[1])) {
            $this->funcParams[1] = '';
        }
        
        if (!isset($this->funcParams[2])) {
            $this->funcParams[2] = '';
        }

        list($action, $package, $ex) = $this->funcParams;

        $this->exSystem = $ex === self::PARAM_EXECSYSTEM ? true : false;

        if (!in_array($action, $this->noMaintenanceMode) && !$this->exSystem) {
            $this->output('Enable maintenance mode...');
            $this->config->setMaintenanceMode(true);
            $this->output('-- Finished.' . PHP_EOL);
        }

        $fn = 'process' . ucfirst(str_replace('-', '', trim($action))) . ( trim($package) ? ucfirst(trim($package)) : '' );
        if (!method_exists($this, $fn)) {
            $this->output('Invalid parameters', true);
        }

        if (!call_user_func([$this, $fn])) {
            $this->output('Processing error, see error log for further information.', true);
        }

        if (!in_array($action, $this->noMaintenanceMode) && !$this->exSystem) {
            $this->output('Disable maintenance mode...');
            $this->config->setMaintenanceMode(false);
            $this->output('-- Finished.' . PHP_EOL);
        }

        return true;
    }

    /**
     * Process update check
     * @return bool
     */
    private function processUpdate()
    {
        $this->initObjects();

        $this->output('Fetch package information from repository...');
        $repository = new repository();
        $successRepo = $repository->fetchRemoteData(true);

        if (!$successRepo) {
            $this->output('Unable to fetch package data informations.', true);
        }

        $this->output('Check for updates...');
        $this->updaterSys->init();
        $successSys = $this->updaterSys->updateAvailable();

        if (!$successRepo) {
            $this->output('Unable to sync update package informations. Check error log for further information.' . PHP_EOL . 'Error Code ' . $successSys, true);
        }

        $successMod = $this->updaterMod->getData();
        if (!count($successMod)) {
            $this->output('Unable to sync module package informations. Check error log for further information.' . PHP_EOL . 'Error Code ' . print_r($successMod, true), true);
        }

        $updates = (new \fpcm\module\modules())->getInstalledUpdates();

        $this->output('-- successful!');
        $this->output('FanPress CM ' . $this->updaterSys->version . ' was relesed on ' . $this->updaterSys->release . ', size is ' . \fpcm\classes\tools::calcSize($this->updaterSys->size));
        if ($successSys === true) {
            $this->output('-- You are NOT up to date.');            
        }
        elseif ($successSys === false) {
            $this->output('-- You are up to date.');            
        }
        elseif ($successSys === system::FORCE_UPDATE) {
            $this->output('-- This released is forced to be installed, you should run fpcmcli.php pkg --update system as soon as possible.');
        }
        elseif ($successSys && $this->updaterSys->phpversion && version_compare(phpversion(), $this->updaterSys->phpversion, '<')) {
            $this->output('-- This released requires PHP ' . $this->updaterSys->phpversion . ' or better, you current PHP version is ' . phpversion());
        }

        $count = count($updates);
        $this->output('Modules: ' . $count . ' module updates are available.' . ($count ? '-- Module keys are:' . PHP_EOL . implode(', ', $updates) : ''));

        return true;
    }

    /**
     * Process full system update
     * @return bool
     */
    private function processUpgradeSystem()
    {

        $this->output('Start system update...');

        $this->updaterSys = new \fpcm\model\updater\system();
        $pkg = new \fpcm\model\packages\update(basename($this->updaterSys->url));

        $this->output('Check local file system...');
        $success = $pkg->checkFiles();

        if ($success !== true) {
            $this->output('Local file system check failed, one or more files are not writable. ERROR CODE: ' . (int) $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Download package from ' . $pkg->getRemotePath() . ($this->updaterSys->size ? ' (' . \fpcm\classes\tools::calcSize($this->updaterSys->size) . ')' : '') . '...');
        
        $progress = new \fpcm\model\cli\progress($this->updaterSys->size);

        $success = $pkg->download($progress);
        $progress = null;

        if ($success !== true) {
            $this->output('Download failed. ERROR CODE: ' . $success);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Check package integity ' . basename($pkg->getLocalPath()) . '...');
        $success = $pkg->checkPackage();
        if ($success !== true) {
            $this->output('Package integity check for ' . basename($pkg->getLocalPath()) . ' failed. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Unpacking package file ' . basename($pkg->getLocalPath()) . '...');
        $success = $pkg->extract();
        if ($success !== true) {
            $this->output('Package extraction for ' . basename($pkg->getLocalPath()) . ' was not successful. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Backup files in local file systems...');
        $success = $pkg->backup();
        if ($success !== true) {
            $this->output('File system backup ' . basename($pkg->getLocalPath()) . ' was not successful. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Update files in local file systems...');
        $success = $pkg->copy();
        if ($success !== true) {
            $this->output('Update of local file system from ' . basename($pkg->getLocalPath()) . ' was not successful. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        system('php '. \fpcm\classes\dirs::getFullDirPath('fpcmcli.php'). ' pkg '.self::PARAM_UPGRADE_DB.' system '.self::PARAM_EXECSYSTEM);
        $fopt = new \fpcm\model\files\fileOption('cliDbUpgrade');
        $success = $fopt->read();
        $fopt->remove();
        if ($success === false) {
            exit;
        }

        $this->output('Update package manager logfile...');
        $success = $pkg->updateLog();
        if ($success !== true) {
            $this->output('Package manager log update failed. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Perform system cleanup after update...');
        $pkg->cleanupFiles();
        $pkg->cleanup();
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('System update successful. New version: ' . \fpcm\classes\baseconfig::getVersionFromFile() . PHP_EOL);
        $this->output('Please check error log and data folder permissions.' . PHP_EOL);
        return true;
    }

    /**
     * Run system database update
     * @return bool
     */
    private function processUpgradedbSystem()
    {
        $this->output('Update local database...');

        $finalizer = new \fpcm\model\updater\finalizer();
        $success = $finalizer->runUpdate();

        if ($this->exSystem) {
            (new \fpcm\model\files\fileOption('cliDbUpgrade'))->write($success);
        }

        if ($success !== true) {
            $this->output('An error occurred during Database update. ERROR CODE: ' . $success, true);
        }

        $this->config->init();
        $this->output('-- Finished.' . PHP_EOL);
        return true;
    }

    /**
     * Run update finalizer
     * @return bool
     */
    private function processUpgradedbModule()
    {
        $this->initObjects();
        $this->getModuleKey();

        $this->output('Update module ' . $this->modulekey . ' database...');

        $success = (new \fpcm\module\module($this->modulekey))->update();
        if ($success !== true) {
            $this->output('An error occurred during database update. ERROR CODE: ' . $success, true);
        }

        $this->config->init();
        $this->output('-- Finished.' . PHP_EOL);

        return true;
    }

    /**
     * Process local file system for new modules
     * @return bool
     */
    private function processListUpdatefs()
    {
        $this->output('Update local module database from file system...');
        $modules = new \fpcm\module\modules();

        if (!$modules->updateFromFilesystem()) {
            $this->output('Failed to update module database from file system ', true);
        }

        $this->output('-- Finished.' . PHP_EOL);
        return true;
    }

    /**
     * Process local module list output
     * @return bool
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
            $this->output('     Update available: ' . $this->boolText($module->hasUpdates()));
            $this->output(PHP_EOL);
        }

        return true;
    }

    /**
     * Process remote module list output
     * @return bool
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
     * Displays information for given module key
     * @return bool
     */
    private function processInfoModule()
    {
        $this->initObjects();

        $this->getModuleKey();
        $this->moduleslIstDetails(new \fpcm\module\module($this->modulekey), true, true);
        return true;
    }

    /**
     * Displays information for given module
     * @param \fpcm\module\module $module
     * @param bool $remote
     * @param bool $descr
     */
    private function moduleslIstDetails(\fpcm\module\module $module, $remote = false, $descr = false)
    {
        $this->output('>> ' . $module->getConfig()->name);
        $this->output($module->getKey() . ' - ' . $module->getConfig()->version . ' - ' . $module->getConfig()->author . ' - ' . $module->getConfig()->link . PHP_EOL);

        if ($descr) {
            $this->output('-- Description:' . PHP_EOL . wordwrap(strip_tags($module->getConfig()->description)) . PHP_EOL);
            $this->output('-- Required: ');
            $this->output('     System: ' . $module->getConfig()->requirements['system']);
            $this->output('     PHP: ' . $module->getConfig()->requirements['php']);
        }

        $this->output('-- Status: ');
        $this->output('     Installable: ' . $this->boolText($module->isInstallable()) . ( $remote && !$module->isInstalled() ? ', Command: fpcmcli.php pkg --install module ' . $module->getKey() : ''));
        $this->output('     Installed: ' . $this->boolText($module->isInstalled()));

        return true;
    }

    /**
     * Process module installation
     * @return bool
     */
    private function processInstallModule()
    {
        $this->initObjects();

        $this->getModuleKey();
        $this->output('Start installation of module ' . $this->modulekey);

        $this->processModulePackage('install');
        $this->output('Installation of module ' . $this->modulekey . ' successful.' . PHP_EOL);

        return true;
    }

    /**
     * Process module update
     * @return bool
     * @since 4.5-b8
     */
    private function processUpgradeModules()
    {
        $this->initObjects();

        $updates = (new \fpcm\module\modules())->getInstalledUpdates();
        if (!count($updates)) {
            $this->output('No module updates are available or were already updated.' . PHP_EOL);
            return true;
        }
                
        $this->output('Module updates are available for ' . PHP_EOL . implode(', ', $updates). PHP_EOL );
        $this->input('Press enter to continue update for all modules...');

        $addDelim = count($updates) > 1;
        
        array_map(function($module) use ($addDelim) {

            $this->output('Start update of module ' . $module . PHP_EOL );

            $this->modulekey = $module;
            $this->processModulePackage('update', true);

            $this->output('Update of module ' . $module . ' successful.' . PHP_EOL);
            if (!$addDelim) {
                return true;
            }
            
            $this->output( '-------------------------' . PHP_EOL);
            return true;

        }, $updates);

        return true;
    }

    /**
     * Process module update
     * @return bool
     */
    private function processUpgradeModule()
    {
        $this->initObjects();

        $this->getModuleKey();
        $this->output('Start update of module ' . $this->modulekey);

        $this->processModulePackage('update', true);
        $this->output('Update of module ' . $this->modulekey . ' successful.' . PHP_EOL);

        return true;
    }

    /**
     * Process module package actions
     * @param string $mode
     * @param bool $checkFiles
     * @return bool
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
            $this->output('-- Finished.' . PHP_EOL);
        }

        $this->output('Download package from ' . $pkg->getRemotePath() . '...');

        $success = $pkg->download();
        if ($success !== true) {
            $this->output('Download failed. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Check package integity ' . basename($pkg->getLocalPath()) . '...');
        $success = $pkg->checkPackage();
        if ($success !== true) {
            $this->output('Package integity check for ' . basename($pkg->getLocalPath()) . ' failed. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Unpacking package file ' . basename($pkg->getLocalPath()) . '...');
        $success = $pkg->extract();
        if ($success !== true) {
            $this->output('Package extraction for ' . basename($pkg->getLocalPath()) . ' was not successful. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Update files in local file systems...');
        $success = $pkg->copy();
        if ($success !== true) {
            $this->output('Update of local file system from ' . basename($pkg->getLocalPath()) . ' was not successful. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Update local database...');
        
        $module = new \fpcm\module\module($this->modulekey);
        if (!method_exists($module, $mode)) {
            fpcmLogSystem('Undefined function ' . $mode . ' for module database update ' . $this->modulekey . '!');
            return true;
        }

        $success = call_user_func([$module, $mode]);
        if ($success !== true) {
            $this->output('An error occurred during Database update. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Update package manager logfile...');
        $success = $pkg->updateLog();
        if ($success !== true) {
            $this->output('Package manager log update failed. ERROR CODE: ' . $success, true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        $this->output('Perform system cleanup after update...');
        $pkg->cleanup();
        $this->output('-- Finished.' . PHP_EOL);

        return true;
    }

    /**
     * 
     * Returns module key by cli params and run check for existance
     * @param int $pos
     * @return bool
     */
    private function getModuleKey($pos = 2)
    {
        if (empty($this->funcParams[$pos])) {
            $this->output('Invalid module package on position ' . ($pos + 1), true);
        }

        if (!$this->updaterMod->getDataCachedByKey($this->funcParams[$pos])) {
            $this->output('Invalid module key, key not found', true);
        }

        $this->modulekey = $this->funcParams[$pos];
        return true;
    }

    /**
     * Process complete modul removal
     * @return bool
     */
    private function processRemoveModule()
    {
        $this->initObjects();

        $this->getModuleKey();
        $this->output('Remove module ' . $this->modulekey);

        $module = new \fpcm\module\module($this->modulekey);
        if ($module->uninstall() !== true) {
            $this->output('Failed to remove, see error log for fruther information!', true);
        }
        $this->output('-- Finished.' . PHP_EOL);

        return true;
    }

    /**
     * Process complete modul deletion
     * @return bool
     */
    private function processDeleteModule()
    {
        $this->initObjects();

        $this->getModuleKey();
        $this->output('Delete module ' . $this->modulekey);

        $module = new \fpcm\module\module($this->modulekey);
        if ($module->uninstall(true) !== true) {
            $this->output('Failed to remove, see error log for fruther information!', true);
        }
        $this->output('-- Finished.' . PHP_EOL);

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
        $lines[] = '      --delete      performs deletion of a given package without uninstallation (modules only)';
        $lines[] = '      --info        displays information about a given package (modules only)';
        $lines[] = '      --list        displays list available packages (modules only)';
        $lines[] = '';
        $lines[] = '> List action params:';
        $lines[] = '';
        $lines[] = '        local       local installed modules';
        $lines[] = '        updatefs    update local installed modules from file system';
        $lines[] = '        remote      modules in package repository';
        return $lines;
    }

}
