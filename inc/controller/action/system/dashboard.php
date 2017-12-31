<?php
    /**
     * Dashboard controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class dashboard extends \fpcm\controller\abstracts\controller {
        
        /**
         * Controller-View
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            $this->view = new \fpcm\model\view\acp('index', 'dashboard');
        }
        
        /**
         * Controller-Processing
         * @return boolean
         */
        public function process() {
            if (!parent::process()) {
                return false;
            }

            $this->view->addJsLangVars(['dashboard_loading' => $this->lang->translate('DASHBOARD_LOADING')]);
            $this->view->setViewJsFiles(['dashboard.js']);
            $this->view->setHelpLink('hl_dashboard');
            $this->view->render();            
        }
    }
?>