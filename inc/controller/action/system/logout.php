<?php
    /**
     * Logout controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class logout extends \fpcm\controller\abstracts\controller {

        /**
         * Frontend-Redirect
         * @var bool
         */
        protected $redirectFE = false;

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

            if (!$this->session->exists()) $this->redirect('system/login');
            if (!is_null($this->getRequestVar('redirect'))) $this->redirectFE = true;
            
            $this->session->setLogout(time());
            $this->session->update();            
            $this->session->deleteCookie();
            
            return true;            
        }
        
        /**
         * Controller-Processing
         * @return type
         */
        public function process() {

            if ($this->redirectFE) {
                header('Location: '.$this->config->system_url);
                return;
            }
            
            $this->redirect('system/login');
            
        }

    }
?>
