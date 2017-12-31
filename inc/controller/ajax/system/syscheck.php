<?php
    /**
     * AJAX syscheck controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\system;
    
    /**
     * AJAX-Controller - System Check
     * 
     * @package fpcm\controller\ajax\system\syscheck
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */  
    class syscheck extends \fpcm\controller\abstracts\ajaxController {
        
        use \fpcm\controller\traits\system\syscheck;
        
        /**
         * Controller-View
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         *
         * @var bool
         */
        protected $installer;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
        }

        public function request() {

            if (!\fpcm\classes\baseconfig::installerEnabled() && \fpcm\classes\baseconfig::dbConfigExists() && !$this->session->exists()) {
                return false;
            }

            if ($this->getRequestVar('sendstats')) {
                $this->submitStatsData();
                return false;
            }
            
            return true;
        }

        
        /**
         * Controller-Processing
         */
        public function process() {

            $view = new \fpcm\model\view\ajax('syscheck', 'system');
            $view->setExcludeMessages(true);
            $view->initAssigns();
            $view->assign('checkOptions', $this->getCheckOptions());
            $view->render();

        }
        
        /**
         * System-Check-Optionen ermitteln
         * @return array
         */
        private function getCheckOptions() {
            $checkOptions     = [];            
            
            $updater = new \fpcm\model\updater\system();
            $updater->checkUpdates();
            
            $remoteVersion = $updater->getRemoteData('version');
            
            $checkOptions[$this->lang->translate('SYSTEM_OPTIONS_SYSCHECK_FPCMVERSION')]    = array(
                'current'   => $this->config->system_version,
                'recommend' => $remoteVersion ? $remoteVersion : $this->lang->translate('GLOBAL_NOTFOUND'),
                'result'    => version_compare($this->config->system_version, $remoteVersion, '>='),
                'helplink'  => 'https://nobody-knows.org/download/fanpress-cm/',
                'actionbtn' => array('link' => $this->getControllerLink('package/sysupdate'), 'description' => 'PACKAGES_UPDATE'),
                'isFolder'  => 0
            );      
            
            if (!$this->permissions->check(array('system' => 'update'))) {
                unset($checkOptions[$this->lang->translate('SYSTEM_OPTIONS_SYSCHECK_FPCMVERSION')]['actionbtn']);
            }
            
            $checkOptions = array_merge($checkOptions, $this->getCheckOptionsSystem());

            return $this->events->runEvent('runSystemCheck', $checkOptions);
        }
        
        private function submitStatsData() {

            $data = array_slice($this->processCli(), 0, 18);
            
            $text  = 'Statistical data '.hash(\fpcm\classes\security::defaultHashAlgo, \fpcm\classes\baseconfig::$rootPath).PHP_EOL.PHP_EOL;
            
            foreach ($data as $key => $value) {
                
                if (!trim($key)) {
                    continue;
                }

                $text .= '- '.str_pad(trim($key), 40, '.').': '.$value['current'].PHP_EOL;
            }

            $text .= PHP_EOL;
            
            $stats = new \fpcm\model\dashboard\sysstats();
            $data  = explode(PHP_EOL, strip_tags($stats->getContent()));

            foreach ($data as $value) {
                $value = explode(':', $value);
                
                if (!isset($value[0]) || !isset($value[1])) {
                    continue;
                }

                $text .= '- '.str_pad(trim($value[0]), 40, '.').': '.$value[1].PHP_EOL;
            }
            
            $email = new \fpcm\classes\email('sea75300@yahoo.de', 'FanPress CM Stats', $text);
            $email->submit();
        }

        public function processCli() {
            
            $checkOptions     = [];            
            
            $updater = new \fpcm\model\updater\system();
            $updater->checkUpdates();
            
            $remoteVersion = $updater->getRemoteData('version');

            $versionCheckresult = version_compare($this->config->system_version, $remoteVersion, '>=');
            $checkOptions[$this->lang->translate('SYSTEM_OPTIONS_SYSCHECK_FPCMVERSION')] = array(
                'current'   => $this->config->system_version,
                'recommend' => $remoteVersion ? $remoteVersion : $this->lang->translate('GLOBAL_NOTFOUND'),
                'result'    => $versionCheckresult,
                'notice'    => !$versionCheckresult ? 'You may run       : php '.\fpcm\classes\baseconfig::$baseDir.'fpcmcli.php pkg --upgrade system' : ''
            );      

            $checkOptions = array_merge($checkOptions, $this->getCheckOptionsSystem());

            return $this->events->runEvent('runSystemCheck', $checkOptions);
            
        }
        
    }
?>