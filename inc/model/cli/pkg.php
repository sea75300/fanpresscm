<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\cli;

    /**
     * FanPress CM cli help module
     * 
     * @package fpcm\model\cli
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5.1
     */
    final class pkg extends \fpcm\model\abstracts\cli {

        /**
         * Modul ausführen
         * @return void
         */
        public function process() {
            
            $updaterSys = new \fpcm\model\updater\system();
            $updaterMod = new \fpcm\model\updater\modules();
            $moduleList = new \fpcm\model\modules\modulelist();

            $noMaintenanceMode = [
                self::FPCMCLI_PARAM_UPDATE,
                self::FPCMCLI_PARAM_LIST,
                self::FPCMCLI_PARAM_INFO
            ];
            
            if (!in_array($this->funcParams[0], $noMaintenanceMode)) {
                $this->output('Enable maintenance mode...');
                $this->config->setMaintenanceMode(true);
            }

            switch ($this->funcParams[0]) {

                case self::FPCMCLI_PARAM_UPDATE :

                    $this->output('Check for system and module updates...');

                    $successSys = $updaterSys->checkUpdates();
                    $successMod = $updaterMod->checkUpdates();

                    if ($successSys > 1 || $successMod > 1) {
                        $this->output('Unable to update package informations. Check PHP log for further information.'.PHP_EOL.'Error Code: SYS-'.$successSys.' | MOD-'.$successMod, true);
                    }

                    $this->output('Check successfull!');
                    $this->output('Current system version: '.$updaterSys->getRemoteData('version'));
                    $this->output('Module updates available: '.($successMod ? 'yes' : 'no'));

                    break;

                case self::FPCMCLI_PARAM_INSTALL :
                case self::FPCMCLI_PARAM_UPGRADE :

                    if ($this->funcParams[1] !== self::FPCMCLI_PARAM_TYPE_MODULE && $this->funcParams[0] === self::FPCMCLI_PARAM_INSTALL) {                        
                        $this->output('Invalid params', true);
                    }

                    if ($this->funcParams[1] === self::FPCMCLI_PARAM_TYPE_SYSTEM) {

                        $this->output('Start system update...');

                        $successSys = $updaterSys->checkUpdates();
                        $remoteData = $updaterSys->getRemoteData();

                        $fileInfo = pathinfo($remoteData['filepath'], PATHINFO_FILENAME);

                        $pkg = new \fpcm\model\packages\update('update', $fileInfo);
                        $this->processPkg($pkg);

                        $this->cache->cleanup();
                        if ($this->funcParams[1] === self::FPCMCLI_PARAM_TYPE_SYSTEM) {
                            $this->output('System update successful. New version: '.$this->config->system_version);
                        }
                    }
                    elseif ($this->funcParams[1] === self::FPCMCLI_PARAM_TYPE_MODULE) {

                        $list       = $moduleList->getModulesRemote();
                        
                        if ($this->funcParams[2] === 'all' && $this->funcParams[0] === self::FPCMCLI_PARAM_UPGRADE) {
                            
                            /* @var \fpcm\model\modules\listitem $module */
                            foreach ($list as $module) {
                                
                                if (!$module->isInstalled() || !$module->hasUpdates()) {
                                    $this->output('No updates found for '.$module->getKey());
                                    continue;
                                }
                                
                                $this->output('Start mdoule update '.$module->getKey());
                                $pkg     = new \fpcm\model\packages\module('module', $module->getKey(), $module->getVersionRemote());
                                $this->processPkg($pkg);
                            }
                        }
                        else {                            
                            $keyData    = \fpcm\model\packages\package::explodeModuleFileName($this->funcParams[2]);

                            if (!array_key_exists($keyData[0], $list)) {
                                $this->output('The requested module was not found in package list storage. Check your module key or update package information storage.', true);
                            }

                            /* @var $module \fpcm\model\modules\listitem */
                            $module = $list[$keyData[0]];                        
                            $pkg     = new \fpcm\model\packages\module('module', $module->getKey(), $module->getVersionRemote());

                            $this->processPkg($pkg);
                        }
                        
                    }

                    if ($this->funcParams[1] === self::FPCMCLI_PARAM_TYPE_MODULE) {
                        $this->output('Module installed successfull!');
                    }
                    
                    break;

                case self::FPCMCLI_PARAM_UPGRADE_DB :
                    
                    if ($this->funcParams[1] === self::FPCMCLI_PARAM_TYPE_MODULE) {
                        $this->output('Invalid params', true);
                    }
                    
                    if ($this->funcParams[1] === self::FPCMCLI_PARAM_TYPE_SYSTEM) {

                        $this->output('Update database and filesystem...');
                        $this->runFinalizer();
                        
                        $this->output('Update successful. New version: '.$this->config->system_version);

                    }                    
                    
                    break;
                    
                case self::FPCMCLI_PARAM_REMOVE :
                    
                    if ($this->funcParams[1] !== self::FPCMCLI_PARAM_TYPE_MODULE) {                        
                        $this->output('Invalid params', true);
                    }
                    
                    $list       = $moduleList->getModulesRemote();
                    $keyData    = \fpcm\model\packages\package::explodeModuleFileName($this->funcParams[2]);
                    
                    if (!array_key_exists($keyData[0], $list)) {
                        $this->output('The requested module was not found in package list storage. Check your module key or update package information storage.', true);
                    }

                    /* @var $module \fpcm\model\modules\listitem */
                    $module = $list[$keyData[0]];                    
                    if (!$module->isInstalled()) {
                        $this->output('The selected module is not installed. Exiting...', true);
                    }

                    $module->runUninstall();
                    
                    if (!$moduleList->uninstallModules(array($keyData[0]))) {
                        $this->output('Unable to remove module '.$keyData[0], true);
                    }

                    $this->output('Module '.$keyData[0].' was removed successfully.');

                    break;
                    
                case self::FPCMCLI_PARAM_LIST :
                    
                    if ($this->funcParams[1] !== self::FPCMCLI_PARAM_TYPE_MODULE) {                        
                        $this->output('Invalid params', true);
                    }

                    $list = $moduleList->getModulesRemote(false);

                    $out = array('', 'Available modules from package server for current FanPress CM version:', '');
                    
                    /* @var $value \fpcm\model\modules\listitem */
                    foreach ($list as $value) {
                        $line = array(
                            '   == '.$value->getName().' > '.$value->getKey().', '.$value->getVersionRemote(),
                            '   '.$value->getAuthor().' > '.$value->getLink(),
                            '   '.$value->getDescription(),
                            ''
                        );
                        
                        $out[] = implode(PHP_EOL, $line);
                    }
                    
                    $this->output($out);
                    
                    break;
                    
                case self::FPCMCLI_PARAM_INFO :

                    if ($this->funcParams[1] !== self::FPCMCLI_PARAM_TYPE_MODULE) {                        
                        $this->output('Invalid params', true);
                    }

                    $list       = $moduleList->getModulesRemote();
                    
                    $keyData    = \fpcm\model\packages\package::explodeModuleFileName($this->funcParams[2]);
                    
                    if (!array_key_exists($keyData[0], $list)) {
                        $this->output('The requested module was not found in package list storage. Check your module key or update package information storage.', true);
                    }

                    /* @var $module \fpcm\model\modules\listitem */
                    $module = $list[$keyData[0]];
                    
                    $this->output(array(
                        '== '.$module->getName(),
                        '   '.$module->getKey(),
                        '   > '.$module->getDescription(),
                        '   Version: '.$module->getVersionRemote(),
                        '   Author: '.$module->getAuthor(),
                        '   Link: '.$module->getLink(),
                        '   Installed: '.($module->isInstalled() ? 'yes' : 'no'),
                        '   Installed version: '.$module->getVersion(),
                        '   Status: '.($module->getStatus() ? 'enabled' : 'disabled'),
                        '   Dependencies:',
                        '   '.implode(PHP_EOL, $module->getDependencies())
                    ));

                    
                    break;

                default:
                    break;
            }
                    
            if (!in_array($this->funcParams[0], $noMaintenanceMode)) {
                $this->output('Disable maintenance mode...');
                $this->config->setMaintenanceMode(false);
            }

            return true;
        }

        /**
         * Run update finalizer
         * @return boolean
         */
        private function runFinalizer() {

            $finalizer = new \fpcm\model\updater\finalizer();
            $success = $finalizer->runUpdate();
            if ($success !== true) {
                $this->output('Error while running final update steps. ERROR CODE: '.$success, true);
            }
            
            $this->config->init();
            
            return true;
        }

        /**
         * Paket bearbeiten
         * @param \fpcm\model\packages\package $pkg
         * @return bool
         * @since FPCM 3.6
         */
        private function processPkg($pkg) {
            $this->output('Download package from '.$pkg->getRemoteFile().'...');

            $success = $pkg->download();
            if ($success !== true) {
                $this->output('Download failed. ERROR CODE: '.$success, true);
            }

            $this->output('Unpacking package file '.\fpcm\model\files\ops::removeBaseDir($pkg->getLocalFile(), true).'...');
            $success = $pkg->extract();
            if ($success !== true) {
                $this->output('Unpacking failed. ERROR CODE: '.$success, true);
            }

            $this->output('Check files in local file system...');

            $success = $pkg->checkFiles();
            if ($success !== true) {
                $this->output('Check failed, one or more files are not wriatble. ERROR CODE: '.(int) $success);
                $this->output(implode(PHP_EOL, $pkg->getCopyErrorPaths()), true);
            }

            $this->output('Copy package content...');
            $success = $pkg->copy();
            if ($success !== true) {
                $this->output('Copy process failed. ERROR CODE: '.$success, true);
            }

            if ($this->funcParams[1] === self::FPCMCLI_PARAM_TYPE_SYSTEM) {
                $this->output('Run final update steps...');
                $this->runFinalizer();                        
            }
            elseif ($this->funcParams[1] === self::FPCMCLI_PARAM_TYPE_MODULE) {

                $this->output('Perform database changes...');

                $moduleClass = \fpcm\model\abstracts\module::getModuleClassName($pkg->getKey());
                $res = class_exists($moduleClass);

                $moduleClassPath = \fpcm\classes\baseconfig::$moduleDir.$pkg->getKey().'/'.str_replace(array('\\', '/'), '', $pkg->getKey()).'.php';

                if (!file_exists($moduleClassPath)) {
                    $this->output('Module class '.$moduleClass.' not found in "'.$moduleClassPath.'"!', true);
                }

                $modObj     = new $moduleClass($pkg->getKey(), '', $pkg->getVersion());                        
                $moduleItem = new \fpcm\model\modules\listitem($pkg->getKey(), '-', '', $pkg->getVersion());

                if (!is_a($modObj, '\fpcm\model\abstracts\module'))  {
                    $this->output('Module class '.$moduleClass.' must be an instance of "\fpcm\model\abstracts\module"!', true);
                }

                $this->cache->cleanup(false, \fpcm\model\abstracts\module::FPCM_MODULES_CACHEFOLDER);

                if ($this->funcParams[0] === self::FPCMCLI_PARAM_INSTALL) {
                    if ($moduleItem->isInstalled()) {
                        $this->output('The selected module is already installed. Exiting...', true);
                    }                            
                    $res = $modObj->runInstall();
                    $moduleItem->save();
                }
                elseif ($this->funcParams[0] === self::FPCMCLI_PARAM_UPGRADE) {

                    if (!$moduleItem->isInstalled()) {
                        $this->output('The selected module is not installed. Exiting...', true);
                    }

                    $res = $modObj->runUpdate();
                    $moduleItem->update();
                }

            }

            $this->output('Update package manager log...');

            $list = [];
            if (method_exists($pkg, 'getProtocol')) {
                $list = $pkg->getProtocol();
            }

            if (!count($list)) {
                $pkg->loadPackageFileListFromTemp();
                $list = $pkg->getFiles();
            }

            fpcmLogPackages($pkg->getKey().' '.$pkg->getVersion(), $list);

            $this->output('Perform cleanup...');
            $pkg->cleanup();
            
            return true;
        }

        /**
         * Hilfe-Text zurückgeben ausführen
         * @return array
         */
        public function help() {
            $lines   = [];
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
            return $lines;
        }

    }
