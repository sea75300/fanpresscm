<?php
    /**
     * User roll add controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\users;
    
    class rolladd extends \fpcm\controller\abstracts\controller {
        
        protected function getPermissions()
        {
            return ['system' => 'users', 'system' => 'rolls'];
        }

        protected function getViewPath()
        {
            return 'users/rolladd';
        }

        public function request() {
            if ($this->buttonClicked('saveRoll')) {    
                
                $userRoll = new \fpcm\model\users\userRoll();
                
                $userRoll->setRollName($this->getRequestVar('rollname'));
                
                if ($userRoll->save()) {
                    $this->redirect ('users/list', array('added' => 2));
                } else {
                    $this->view->addErrorMessage('SAVE_FAILED_ROLL');
                }
            
            }
            
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
