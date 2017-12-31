<?php
    /**
     * Wordban item list controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\wordban;
    
    class itemlist extends \fpcm\controller\abstracts\controller {
        
        protected $view;

        protected $list;

        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('system' => 'wordban');
            
            $this->view     = new \fpcm\model\view\acp('itemlist', 'wordban');
            $this->list     = new \fpcm\model\wordban\items();

        }

        public function request() {
            
            if ($this->getRequestVar('added')) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_WORDBAN');
            }
            
            if ($this->getRequestVar('edited')) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_WORDBAN');
            }
            
            if ($this->buttonClicked('delete') && !$this->checkPageToken()) {
                $this->view->addErrorMessage('CSRF_INVALID');
                return true;
            }

            $ids = $this->getRequestVar('ids');
            if ($this->buttonClicked('delete') && !is_null($ids)) {                
                if ($this->list->deleteItems($ids) ) {
                    $this->view->addNoticeMessage('DELETE_SUCCESS_WORDBAN');
                } else {
                    $this->view->addErrorMessage('DELETE_FAILED_WORDBAN');
                }
            }  
            
            return true;            
        }
        
        public function process() {
            if (!parent::process()) return false;
            
            $itemList = $this->list->getItems();
            $this->view->assign('itemList', $itemList);
            $this->view->setHelpLink('hl_options');
            
            $this->view->render();
        }

    }
?>
