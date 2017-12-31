<?php
    /**
     * AJAX logs clear controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\logs;
    
    /**
     * AJAX-Controller zum leeren der Systemlogs
     * 
     * @package fpcm\controller\ajax\logs\clear
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class clear extends \fpcm\controller\abstracts\ajaxController {
        
        /**
         * System-Log-Typ
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
            
            if (is_numeric($this->log)) {
                
                if ($this->log < 1) {
                    $res = \fpcm\classes\baseconfig::$fpcmSession->clearSessions();
                }
                else {
                    $logfile = new \fpcm\model\files\logfile($this->log);
                    $res     = $logfile->clear();
                }

            }
            else {
                $res = $this->events->runEvent('clearSystemLog', $this->log);
            }
            

            $this->events->runEvent('clearSystemLogs');

            $this->returnData[] = array(
                'txt'  => $this->lang->translate($res ? 'LOGS_CLEARED_LOG_OK' : 'LOGS_CLEARED_LOG_FAILED'),
                'type' => $res ? 'notice' : 'error',
                'id'   => md5(uniqid()),
                'icon' => $res ? 'info-circle' : 'exclamation-triangle'
            );

            $this->getResponse();
            
        }

    }
?>
