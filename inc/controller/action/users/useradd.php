<?php
    /**
     * User add controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\users;
    
    class useradd extends \fpcm\controller\abstracts\controller {
        
        /**
         *
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         *
         * @var \fpcm\model\users\author
         */
        protected $author;

        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('system' => 'users');
            
            $this->view   = new \fpcm\model\view\acp('useradd', 'users');
            $this->author = new \fpcm\model\users\author();
        }

        public function request() {
            if (!$this->buttonClicked('userSave')) {
                return true;
            }
                
            if ($this->buttonClicked('userSave') && !$this->checkPageToken()) {
                $this->view->addErrorMessage('CSRF_INVALID');
                return true;
            }

            $this->author->setUserName($this->getRequestVar('username'));
            $this->author->setEmail($this->getRequestVar('email'));
            $this->author->setDisplayName($this->getRequestVar('displayname'));
            $this->author->setRoll($this->getRequestVar('roll', array(9)));
            $this->author->setUserMeta([]);
            $this->author->setUsrinfo($this->getRequestVar('usrinfo'));
            $this->author->setRegistertime(time());

            $newpass         = $this->getRequestVar('password');
            $newpass_confirm = $this->getRequestVar('password_confirm');

            $save = true;
            if ($newpass && $newpass_confirm && (md5($newpass) == md5($newpass_confirm))) {
                $this->author->setPassword($newpass);
            } else {
                $save = false;
                $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_MATCH');
            }

            if ($save) {
                $res = $this->author->save();

                if ($res === false) $this->view->addErrorMessage('SAVE_FAILED_USER');
                if ($res === true) $this->redirect ('users/list', array('added' => 1));
                if ($res === \fpcm\model\users\author::AUTHOR_ERROR_PASSWORDINSECURE) $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_SECURITY');
                if ($res === \fpcm\model\users\author::AUTHOR_ERROR_EXISTS) $this->view->addErrorMessage('SAVE_FAILED_USER_EXISTS');
                if ($res === \fpcm\model\users\author::AUTHOR_ERROR_NOEMAIL) $this->view->addErrorMessage('SAVE_FAILED_USER_EMAIL');
            }
            
            return true;
            
        }
        
        public function process() {
            if (!parent::process()) return false;
            
            $userRolls = new \fpcm\model\users\userRollList();         
            $this->view->setHelpLink('hl_options');
            $this->view->assign('userRolls', $userRolls->getUserRollsTranslated());            
            $this->view->assign('author', $this->author);
            $this->view->assign('avatar', false);
            $this->view->assign('showDisableButton', false);
            $this->view->assign('showExtended', true);
            $this->view->assign('showImage', false);
            $this->view->setViewJsFiles([\fpcm\classes\loader::libGetFileUrl('password-generator', 'password-generator.min.js')]);
            $this->view->addJsVars([
                'fpcmNavigationActiveItemId' => 'submenu-itemnav-item-users',
                'fpcmFieldSetAutoFocus'      => 'username'
            ]);

            $this->view->render();            
        }

    }
?>
