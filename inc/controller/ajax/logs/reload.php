<?php
    /**
     * AJAX logs reload controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
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
         * Array mit zu prüfenden Berchtigungen
         * @var array
         */
        protected $checkPermission = ['system' => 'logs'];
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request()
        {
            $this->log = $this->getRequestVar('log');
            return $this->log === null ? false : true;
        }

        protected function getViewPath() {
            return 'logs/sessions';
        }

        /**
         * Controller-Processing
         */ 
        public function process()
        {
            $this->initView();

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
        private function loadLog0()
        {
            $userList = new \fpcm\model\users\userList();
            $this->view->assign('userList', $userList->getUsersAll());
            $this->view->assign('sessionList', $this->session->getSessions());
            $this->view->render();            
        }
        
        /**
         * Lädt System-Log (Typ 1)
         */
        private function loadLog1()
        {
            $this->readStandard('system', 'systemLogs');
        }
        
        /**
         * Lädt PHP-Error-Log (Typ 2)
         */        
        private function loadLog2()
        {           
            $this->readStandard('errors', 'errorLogs');
        }
        
        /**
         * Lädt Datenbank-Log (Typ 3)
         */
        private function loadLog3()
        {
            $this->readStandard('database', 'databaseLogs');
        }
        
        /**
         * Lädt Cronjob-Log (Typ 4)
         */
        private function loadLog4()
        {
            $this->readStandard('packages', 'packagesLogs');
        }
        
        /**
         * Lädt Cronjob-Log (Typ 5)
         */
        private function loadLog5()
        {
            $this->readStandard('cronjobs', 'cronjobLogs');
        }

        /**
         * Logdatei in standardmäßigem Weg behandeln
         * @param string $tpl
         * @param string $varName
         */
        private function readStandard($tpl, $varName)
        {
            $logFile = new \fpcm\model\files\logfile($this->log);
            $this->view->setViewPath('logs/'.$tpl);
            $this->view->assign($varName, $logFile->fetchData());
            $this->view->render();  
        }

    }
?>
