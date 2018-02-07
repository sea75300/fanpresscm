<?php
    /**
     * Smiley list controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\smileys;
    
    class smileylist extends \fpcm\controller\abstracts\controller {

        /**
         * Smiley-Liste
         * @var \fpcm\model\files\smileylist
         */
        protected $smileyList;

        public function getViewPath()
        {
            return 'smileys/list';
        }

        protected function getPermissions()
        {
            return ['system' => 'smileys'];
        }

        public function request() {

            $this->smileyList = new \fpcm\model\files\smileylist();

            if ($this->getRequestVar('added')) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_SMILEY');
            }
            
            if ($this->buttonClicked('configSave') && !$this->checkPageToken()) {
                $this->view->addErrorMessage('CSRF_INVALID');
                return true;
            }
            
            if ($this->buttonClicked('deleteSmiley') && $this->getRequestVar('smileyids')) {               
                $deleteItems = array_map('unserialize', array_map('base64_decode', $this->getRequestVar('smileyids')));
                if ($this->smileyList->deleteSmileys($deleteItems)) {
                    $this->view->addNoticeMessage('DELETE_SUCCESS_SMILEYS');
                } else {
                    $this->view->addErrorMessage('DELETE_FAILED_SMILEYS');
                }
                
                $this->cache->cleanup();
            }
            
            return true;            
        }
        
        public function process() {
            $list = $this->smileyList->getDatabaseList();
            $this->view->assign('list', $list);            
            $this->view->render();
        }

        protected function getHelpLink()
        {
            return 'hl_options';
        }

    }
?>
