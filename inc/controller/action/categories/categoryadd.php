<?php
    /**
     * Category add controller
     * @category Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\categories;
    
    class categoryadd extends \fpcm\controller\abstracts\controller {

        protected $category;
        
        public function getViewPath()
        {
            return 'categories/categoryadd';
        }

        protected function getPermissions()
        {
            return ['system' => 'categories'];
        }

        public function request() {

            $this->category         = new \fpcm\model\categories\category();

            if ($this->buttonClicked('categorySave')) {
                $data = $this->getRequestVar('category');
                
                if (!trim($data['name']) || empty($data['groups'])) {
                    $this->view->addErrorMessage('SAVE_FAILED_CATEGORY');
                    return true;
                }

                $groups = implode(';', array_map('intval', $data['groups']));
                $this->category->setGroups($groups);
                $this->category->setIconPath($data['iconpath']);
                $this->category->setName($data['name']);

                $res = $this->category->save();

                if ($res === false) $this->view->addErrorMessage('SAVE_FAILED_CATEGORY');
                if ($res === true) $this->redirect ('categories/list', array('added' => 1));
            }
            
            return true;
            
        }

        protected function getHelpLink()
        {
            return 'hl_options';
        }

        protected function getActiveNavigationElement()
        {
            return 'submenu-itemnav-item-categories';
        }
        
        public function process() {
            
            
            $userRolls = new \fpcm\model\users\userRollList();            
            $this->view->assign('userRolls', $userRolls->getUserRollsTranslated());               
            $this->view->assign('category', $this->category);
            $this->view->setFieldAutofocus('categoryname');
            
            $this->view->render();            
        }

    }
?>