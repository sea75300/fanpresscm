<?php
    /**
     * User roll add controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\users;
    
    class rolladd extends \fpcm\controller\abstracts\controller {
        
        /**
         *
         * @var \fpcm\model\view\acp
         */
        protected $view;

        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('system' => 'users', 'system' => 'rolls');
            
            $this->view   = new \fpcm\model\view\acp('rolladd', 'users');
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

            $this->view->addJsVars([
                'fpcmNavigationActiveItemId' => 'submenu-itemnav-item-users',
                'fpcmFieldSetAutoFocus'      => 'rollname'
            ]);

            return true;
            
        }
        
        public function process() {
            if (!parent::process()) return false;

            $this->view->setHelpLink('hl_options');
            $this->view->render();            
        }

    }
?>
