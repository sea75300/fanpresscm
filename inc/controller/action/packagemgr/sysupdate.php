<?php
    /**
     * System updater controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\packagemgr;
    
    class sysupdate extends \fpcm\controller\abstracts\controller {

        use \fpcm\controller\traits\packagemgr\initialize;
        
        /**
         * Controller-View
         * @var \fpcm\model\view\acp
         */
        protected $view;
        
        /**
         * Update-Prüfung aktiv
         * @var bool
         */
        protected $updateCheckEnabled = false;
        
        /**
         * Auszuführender Schritt
         * @var mixed
         */
        protected $forceStep = false;
        
        /**
         * Auszuführender Schritt
         * @var bool
         */
        protected $legacy = false;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();

            $this->checkPermission  = array('system' => 'update');
            $this->view             = new \fpcm\model\view\acp('sysupdater', 'packagemgr');
        }
        
        /**
         * Request-Handler
         * @return bool
         */
        public function request() {
            if ($this->getRequestVar('step')) {
                $this->forceStep = $this->getRequestVar('step');
            }
            
            if ($this->getRequestVar('file')) {
                $tmpFile = new \fpcm\model\files\tempfile('forceUpdateFile', $this->getRequestVar('file'));
                $tmpFile->save();
            }
            
            if (!$this->forceStep) {
                \fpcm\classes\baseconfig::enableAsyncCronjobs(false);
            }
            
            return parent::request();
        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;

            $this->config->setMaintenanceMode(false);
            
            $updater = new \fpcm\model\updater\system();            
            $updater->checkUpdates();
            $remoteFilePath = $updater->getRemoteData('filepath');
            
            $params = $this->initPkgManagerData();
            $params['fpcmUpdaterStartStep'] = ($this->forceStep
                                            ? $this->forceStep
                                            : (\fpcm\classes\baseconfig::canConnect()
                                                    ? \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD
                                                    : \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB));
            
            $params['fpcmUpdaterForce'] = $this->forceStep ? 1 : 0;
            $params['fpcmUpdaterMessages'][\fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD.'_START'] = $this->lang->translate('PACKAGES_RUN_DOWNLOAD', ['{{pkglink}}' => is_array($remoteFilePath) ? '' : $remoteFilePath]);
            $params['fpcmUpdaterMessages']['EXIT_1']  = $this->lang->translate('UPDATES_SUCCESS');
            $params['fpcmUpdaterStepMap'] = [
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_DOWNLOAD   => 1,
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_EXTRACT    => 2,
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_CHECKFILES => 3,
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_COPY       => 4,
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_UPGRADEDB  => 4,
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_CLEANUP    => 6,
                \fpcm\model\packages\package::FPCMPACKAGE_STEP_FINISH     => 7
            ];
            $params['fpcmUpdaterMaxStep']   = count($params['fpcmUpdaterStepMap']);
            
            $this->view->addJsVars($params);
            $this->view->setViewJsFiles(['updater.js']);
            $this->view->render();
        }

    }