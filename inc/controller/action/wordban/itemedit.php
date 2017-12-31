<?php
    /**
     * Wordban item edit controller
     * @item Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\wordban;
    
    class itemedit extends \fpcm\controller\abstracts\controller {
        
        protected $view;

        /**
         *
         * @var \fpcm\model\wordban\item
         */
        protected $item;

        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('system' => 'wordban');
            $this->view = new \fpcm\model\view\acp('itemedit', 'wordban');
            
        }

        public function request() {

            if (is_null($this->getRequestVar('itemid'))) {
                $this->redirect('wordban/list');
            }
            
            $this->item = new \fpcm\model\wordban\item($this->getRequestVar('itemid', array(9)));
            
            if (!$this->item->exists()) {
                $this->view->setNotFound('LOAD_FAILED_WORDBAN', 'wordban/list');
                return true;
            }

            if ($this->buttonClicked('wbitemSave') && !$this->checkPageToken()) {
                $this->view->addErrorMessage('CSRF_INVALID');
                return true;
            }

            if ($this->buttonClicked('wbitemSave')) {
                $data = $this->getRequestVar('wbitem');
                
                if (!trim($data['searchtext']) || !trim($data['replacementtext'])) {
                    $this->view->addErrorMessage('SAVE_FAILED_WORDBAN');
                }
                else {
                    $this->item->setSearchtext($data['searchtext']);
                    $this->item->setReplacementtext($data['replacementtext']);
                    $this->item->setReplaceTxt(isset($data['replacetxt']) ? $data['replacetxt'] : 0);
                    $this->item->setLockArticle(isset($data['lockarticle']) ? $data['lockarticle'] : 0);
                    $this->item->setCommentApproval(isset($data['commentapproval']) ? $data['commentapproval'] : 0);

                    $res = $this->item->update();

                    if ($res === false) $this->view->addErrorMessage('SAVE_FAILED_WORDBAN');
                    if ($res === true) $this->redirect ('wordban/list', array('edited' => 1));                    
                }
            }
            
            return true;
            
        }
        
        public function process() {
            if (!parent::process()) return false;

            $this->view->addJsVars(array(
                'fpcmNavigationActiveItemId' => 'submenu-itemnav-item-wordban',
                'fpcmFieldSetAutoFocus'      => 'wbitemsearchtext'
            ));

            $this->view->assign('item', $this->item);
            $this->view->setHelpLink('hl_options');
            $this->view->render();

        }

    }
?>