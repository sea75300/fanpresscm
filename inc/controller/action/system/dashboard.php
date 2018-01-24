<?php
    /**
     * Dashboard controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class dashboard extends \fpcm\controller\abstracts\controller {

        /**
         * Get view path for controller
         * @return string
         */
        protected function getViewPath() {
            return 'dashboard/index';
        }
        
        /**
         * Controller-Processing
         * @return boolean
         */
        public function process() {
            $this->view->addJsLangVars(['dashboard_loading' => $this->lang->translate('DASHBOARD_LOADING')]);
            $this->view->addJsFiles(['dashboard.js']);
            $this->view->setHelpLink('hl_dashboard');
            $this->view->render();            
        }
    }
?>