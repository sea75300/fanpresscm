<?php
    /**
     * User roll add controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\users;
    
    class rolledit extends \fpcm\controller\abstracts\controller {

        protected function getPermissions()
        {
            return ['system' => 'users', 'system' => 'rolls'];
        }

        protected function getViewPath()
        {
            return 'users/rolledit';
        }

        public function request() {
            if (is_null($this->getRequestVar('id'))) {
                $this->redirect('users/list');
            }           
            
            $userRoll = new \fpcm\model\users\userRoll($this->getRequestVar('id'));            
            
            if (!$userRoll->exists()) {
                $this->view->setNotFound('LOAD_FAILED_ROLL', 'users/list');                
                return true;
            }
            
            if ($this->buttonClicked('saveRoll')) {    
                $userRoll->setRollName($this->getRequestVar('rollname'));
                if ($userRoll->update()) {
                    $this->redirect ('users/list', array('edited' => 2));
                } else {
                    $this->view->addErrorMessage('SAVE_FAILED_ROLL');
                }            
            }
            
            $this->view->assign('userRoll', $userRoll);
            $this->view->setFieldAutofocus('rollname');
            
            return true;
            
        }

        protected function getHelpLink()
        {
            return 'hl_options';
        }

        protected function getActiveNavigationElement()
        {
            return 'submenu-itemnav-item-users';
        }

    }
?>
