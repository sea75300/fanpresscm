<?php
    /**
     * AJAX cron controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\system;
    
    /**
     * AJAX-Controller - synchrone Ausführung von Cronjobs
     * 
     * @package fpcm\controller\ajax\system\cronasync
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */  
    class croninterval extends \fpcm\controller\abstracts\ajaxController {

        /**
         * Konstruktor
         */
        public function __construct() {
            
            $this->checkPermission  = array('system' => 'options');
            parent::__construct();

        }
        
        /**
         * Request-Handler
         * @return bool
         */
        public function request() {
            return $this->session->exists();
        }
        
        /**
         * Controller-Processing
         */
        public function process() {

            $cronjobId = $this->getRequestVar('cjId');
            $interval  = $this->getRequestVar('interval');
            
            if (!$cronjobId || $interval ===null) {
                return false;
            }
                
            $cjClassName = \fpcm\model\abstracts\cron::getCronNamespace($cronjobId);

            /* @var $cronjob \fpcm\model\abstracts\cron */
            $cronjob = new $cjClassName($cronjobId);

            if (!is_a($cronjob, '\fpcm\model\abstracts\cron')) {
                trigger_error("Cronjob class {$cronjobId} must be an instance of \"\fpcm\model\abstracts\cron\"!");
                return false;                
            }

            $cronjob->setExecinterval($interval);
            $cronjob->update();

            return true;
        }
        
    }
?>