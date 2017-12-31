<?php
    /**
     * AJAX logs reload controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\logs;
    
    /**
     * AJAX-Controller zum Reload der Systemloads
     * 
     * @package fpcm\controller\ajax\logs\relaod
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class reload extends \fpcm\controller\abstracts\ajaxController {
        
        /**
         * System-Logs-Typ
         * @var int
         */
        protected $log;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {
            
            if (!$this->session->exists()) {
                return false;
            }
            
            if (!$this->permissions->check(array('system' => 'logs'))) {
                return false;
            }
            
            if ($this->getRequestVar('log') === null) return false;
            $this->log = $this->getRequestVar('log');
            
            return true;
        }
        
        /**
         * Controller-Processing
         */ 
        public function process() {

            if (!parent::process()) return false;
            
            if (method_exists($this, 'loadLog'.$this->log)) {
                call_user_func(array($this, 'loadLog'.$this->log));
            }
            else {
                $this->events->runEvent('reloadSystemLog', $this->log);
            }

            
            $this->events->runEvent('reloadSystemLogs');

        }
        
        /**
         * Lädt Sessions-Log (Typ 0)
         */
        private function loadLog0() {
            $userList = new \fpcm\model\users\userList();
            
            $view = new \fpcm\model\view\ajax('sessions', 'logs');
            $view->setExcludeMessages(true);
            $view->initAssigns();
            $view->assign('userList', $userList->getUsersAll());
            $view->assign('sessionList', $this->session->getSessions());
            $view->render();            
        }
        
        /**
         * Lädt System-Log (Typ 1)
         */
        private function loadLog1() {
            $this->readStandard('system', 'systemLogs');
        }
        
        /**
         * Lädt PHP-Error-Log (Typ 2)
         */        
        private function loadLog2() {           
            $this->readStandard('errors', 'errorLogs');
        }
        
        /**
         * Lädt Datenbank-Log (Typ 3)
         */
        private function loadLog3() {
            $this->readStandard('database', 'databaseLogs');
        }
        
        /**
         * Lädt Cronjob-Log (Typ 4)
         */
        private function loadLog4() {
            $this->readStandard('packages', 'packagesLogs');
        }
        
        /**
         * Lädt Cronjob-Log (Typ 5)
         */
        private function loadLog5() {
            $this->readStandard('cronjobs', 'cronjobLogs');
        }

        /**
         * Logdatei in standardmäßigem Weg behandeln
         * @param string $tpl
         * @param string $varName
         */
        private function readStandard($tpl, $varName) {
            $logFile = new \fpcm\model\files\logfile($this->log);            
            $view = new \fpcm\model\view\ajax($tpl, 'logs');
            $view->assign($varName, $logFile->fetchData());
            $view->setExcludeMessages(true);
            $view->initAssigns();
            $view->render();  
        }

    }
?>
