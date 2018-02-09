<?php
    /**
     * Log view controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class logs extends \fpcm\controller\abstracts\controller {
        
        protected function getPermissions()
        {
            return ['system' => 'logs'];
        }

        protected function getViewPath()
        {
            return 'logs/overview';
        }
        
        /**
         * Controller-Processing
         */
        public function process() {

            $this->view->assign('customLogs', $this->events->runEvent('logsAddList', []));
            $this->view->assign('reloadBaseLink', \fpcm\classes\tools::getFullControllerLink('ajax/logs/reload', [
                'log' => ''
            ]));
            $this->view->addJsFiles(['logs.js']);
            $this->view->addJsLangVars(['LOGS_CLEARED_LOG_OK', 'LOGS_CLEARED_LOG_FAILED']);
            $this->view->addButton((new \fpcm\view\helper\button('fpcm-logs-clear_0'))->setType('button')->setText('LOGS_CLEARLOG')->setClass('fpcm-logs-clear fpcm-clear-btn')->setIcon('trash'));
            
            $this->view->render();
        }

        protected function getHelpLink()
        {
            return 'hl_options';
        }
        
    }
?>
