<?php
    /**
     * AJAX system updates controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\packagemgr;
    
    /**
     * AJAX-Controller Paketmanager - System-Updater
     * 
     * @package fpcm\controller\ajax\packagemgr\sysupdater
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */      
    class sysupdater extends \fpcm\controller\abstracts\ajaxController {
        
        /**
         * Auszuführender Schritt
         * @var int
         */
        protected $step;
        
        /**
         * bestimmten Schritt erzwingen
         * @var int
         */
        protected $forceStep;
        
        /**
         * allow_url_fopen = 1
         * @var bool
         */
        protected $canConnect;
        
        /**
         * Update-Package-Object
         * @var \fpcm\model\packages\update
         */
        protected $pkg;
        
        /**
         * Update-Step-Result
         * @var mixed
         */
        protected $res = false;
        
        /**
         * Version data file
         * @var \fpcm\model\files\tempfile
         */
        protected $versionDataFile = false;

        /**
         * Konstruktur
         */
        public function __construct() {
            parent::__construct();
        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            $this->step      = $this->getRequestVar('step');          
            $this->forceStep = $this->getRequestVar('force', [\fpcm\classes\http::FPCM_REQFILTER_CASTINT]);
            
            return true;
        }
        
        /**
         * Controller-Processing
         */
        public function process() {

            if (!parent::process()) return false;

            $this->canConnect   = \fpcm\classes\baseconfig::canConnect();
            
            if ($this->canConnect) {
                
                $this->versionDataFile = new \fpcm\model\files\tempfile('newversion');
                if ($this->versionDataFile->exists() && $this->versionDataFile->getContent()) {
                    $remoteData = json_decode($this->versionDataFile->getContent(), true);
                }
                else {
                    $updater = new \fpcm\model\updater\system();
                    $updater->checkUpdates();

                    $remoteData = $updater->getRemoteData();
                    $this->versionDataFile->setContent(json_encode($remoteData));
                    $this->versionDataFile->save();
                }

                $fileInfo = pathinfo($remoteData['filepath'], PATHINFO_FILENAME);

                $tmpFile = new \fpcm\model\files\tempfile('forceUpdateFile');
                if ($tmpFile->exists()) {
                    $fileInfo = $tmpFile->getContent();
                }

                $signature = isset($remoteData['signature']) ? $remoteData['signature'] : '';
                $this->pkg = new \fpcm\model\packages\update('update', $fileInfo, '', $signature);
            }

            $this->returnData['current'] = $this->step;
            $fn = 'execStep'.(is_numeric($this->step) ? $this->step : ucfirst($this->step));

            if (method_exists($this, $fn)) {
                call_user_func([$this, $fn]);
            }
            
            $this->returnCode = $this->step.'_'.(int) $this->res;
            $this->getResponse();
        }

        private function execStepDownload() {

            $this->res = $this->pkg->download();

            if ($this->res === \fpcm\model\packages\package::FPCMPACKAGE_REMOTEFILE_ERROR) {
                $this->versionDataFile->delete();
            }

            if ($this->res === true) {
                $this->syslog('Downloaded update package successfully from '.$this->pkg->getRemoteFile());
                $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_EXTRACT;
                return true;
            }

            $this->syslog('Error while downloading update package from '.$this->pkg->getRemoteFile());
            $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP;

        }

        private function execStepCheckfiles() {

            $this->res = $this->pkg->checkFiles();

            if ($this->res === \fpcm\model\packages\package::FPCMPACKAGE_FILESCHECK_ERROR) {
                $this->versionDataFile->delete();
            }

            if ($this->res === true) {
                $this->syslog('All local files are writable '.$this->pkg->getRemoteFile());
                $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_COPY;
                return true;
            }

            $this->syslog('A few files in local file system where not writable '.$this->pkg->getRemoteFile());
            $this->syslog(implode(PHP_EOL, $this->pkg->getCopyErrorPaths()));
            $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP;
   
        }

        private function execStepExtract() {

            $this->res = $this->pkg->extract();
            $from = \fpcm\model\files\ops::removeBaseDir($this->pkg->getLocalFile());

            if ($this->res === true) {
                $this->syslog('Extracted update package successfully from '.$from);
                $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_CHECKFILES;
                return true;
            }

            $this->syslog('Error while extracting update package from '.$from);
            $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP;

        }

        private function execStepCopy() {

            $this->res = $this->pkg->copy();

            $dest = \fpcm\model\files\ops::removeBaseDir(\fpcm\classes\baseconfig::$baseDir);
            $from = \fpcm\model\files\ops::removeBaseDir($this->pkg->getExtractPath());

            if ($this->res === true) {
                $this->syslog('Moved update package content successfully from '.$from.' to '.$dest);
                $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB;
                return true;
            }

            $this->syslog('Error while moving update package content from '.$from.' to '.$dest);
            $this->syslog(implode(PHP_EOL, $this->pkg->getCopyErrorPaths()));
            $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP;
            
        }

        private function execStepUpgradedb() {

            $finalizer = new \fpcm\model\updater\finalizer();
            $this->res = $finalizer->runUpdate();
            
            $this->returnData['nextstep'] = $this->forceStep
                                          ? \fpcm\model\packages\package::FPCMPACKAGE_STEP_FINISH
                                          : \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP;

            if ($this->res === true) {
                $this->syslog('Run final update steps successfully!');
                return true;
            }

            $this->syslog('Error while running final update steps!');
            
        }

        private function execStepCleanup() {

            if ($this->canConnect) {

                $list = [];
                if (method_exists($this->pkg, 'getProtocol')) {
                    $list = $this->pkg->getProtocol();
                }

                if (!count($list)) {
                    $this->pkg->loadPackageFileListFromTemp();
                    $list = $this->pkg->getFiles();
                }


                $this->pkglog($this->pkg->getKey().' '.$this->pkg->getVersion(), $list);
            }

            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            $this->cache->cleanup();

            $this->res = true;
            $this->returnData['nextstep'] = \fpcm\model\packages\package::FPCMPACKAGE_STEP_FINISH;
            
        }

        private function execStepFinish() {

            $this->returnData['newver']   = $this->config->system_version;
            $this->res = true;

            if ($this->versionDataFile->exists()) {
                $this->versionDataFile->delete();
            }

        }

        private function execStep1() {

            $this->res = $this->pkg->download();

            if ($this->res === \fpcm\model\packages\package::FPCMPACKAGE_REMOTEFILE_ERROR) {
                $this->versionDataFile->delete();
            }

            if ($this->res === true) {
                $this->syslog('Downloaded update package successfully from '.$this->pkg->getRemoteFile());
                $this->returnData['nextstep'] = 2;
                return true;
            }

            $this->syslog('Error while downloading update package from '.$this->pkg->getRemoteFile());
            $this->returnData['nextstep'] = 5;

        }
        
        private function execStep2() {

            $this->res = $this->pkg->extract();
            $from = \fpcm\model\files\ops::removeBaseDir($this->pkg->getLocalFile());

            if ($this->res === true) {
                $this->syslog('Extracted update package successfully from '.$from);
                $this->returnData['nextstep'] = 3;
                return true;
            }

            $this->syslog('Error while extracting update package from '.$from);
            $this->returnData['nextstep'] = 5;
            
        }
        
        private function execStep3() {

            $this->res = $this->pkg->copy();

            $dest = \fpcm\model\files\ops::removeBaseDir(\fpcm\classes\baseconfig::$baseDir);
            $from = \fpcm\model\files\ops::removeBaseDir($this->pkg->getExtractPath());

            if ($this->res === true) {
                $this->syslog('Moved update package content successfully from '.$from.' to '.$dest);
                $this->returnData['nextstep'] = 4;
                return true;
            }

            $this->syslog('Error while moving update package content from '.$from.' to '.$dest);
            $this->syslog(implode('<br>', $this->pkg->getCopyErrorPaths()));
            $this->returnData['nextstep'] = 5;

        }
        
        private function execStep4() {

            $finalizer = new \fpcm\model\updater\finalizer();
            $this->res = $finalizer->runUpdate();
            $this->returnData['nextstep'] = $this->forceStep ? 6 : 5;

            if ($this->res === true) {
                $this->syslog('Run final update steps successfully!');
                return true;
            }

            $this->syslog('Error while running final update steps!');

        }
        
        private function execStep5() {

            if ($this->canConnect) {

                if (method_exists($this->pkg, 'getProtocol')) {
                    $list = $this->pkg->getProtocol();
                }
                else {                    
                    $this->pkg->loadPackageFileListFromTemp();
                    $list = $this->pkg->getFiles();
                }

                $this->pkglog($this->pkg->getKey().' '.$this->pkg->getVersion(), $list);
            }

            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            $this->cache->cleanup();

            $this->res = true;
            $this->returnData['nextstep'] = 6;

        }
        
        private function execStep6() {

            $this->returnData['newver']   = $this->config->system_version;
            $this->res = true;

            if ($this->versionDataFile->exists()) {
                $this->versionDataFile->delete();
            }

        }
        
        private function syslog($data) {

            if (function_exists('fpcmLogSystem')) {
                return fpcmLogSystem($data);
            }
            
            return \fpcm\classes\logs::syslogWrite($data);
            
        }
        
        private function pkglog($packageName, $data) {

            if (function_exists('fpcmLogPackages')) {
                return fpcmLogPackages($packageName, $data);
            }
            
            return \fpcm\classes\logs::pkglogWrite($packageName, $data);
            
        }
    }
?>