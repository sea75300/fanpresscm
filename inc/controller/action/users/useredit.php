<?php
    /**
     * User edit controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\users;
    
    class useredit extends \fpcm\controller\abstracts\controller {
        
        use \fpcm\controller\traits\common\timezone,
            \fpcm\controller\traits\users\authorImages;
        
        /**
         *
         * @var \fpcm\model\view\acp
         */
        protected $view;
        
        /**
         *
         * @var int
         */
        protected $userId;
        
        /**
         *
         * @var bool
         */
        protected $userEnabled;

        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = ['system' => 'users'];

            $this->view = new \fpcm\model\view\acp('useredit', 'users');
        }

        public function request() {
            
            if (is_null($this->getRequestVar('userid'))) {
                $this->redirect('users/list');
            }

            $this->userId = $this->getRequestVar('userid', [9]);
            
            $author = new \fpcm\model\users\author($this->userId);
            
            if (!$author->exists()) {
                $this->view->setNotFound('LOAD_FAILED_USER', 'users/list');                
                return true;
            }
            
            $this->uploadImage($author);

            $checkPageToken = $this->checkPageToken();
            if (($this->buttonClicked('userSave') || $this->buttonClicked('resetProfileSettings')) && !$checkPageToken) {
                $this->view->addErrorMessage('CSRF_INVALID');
            }
            
            $this->deleteImage($author);

            if ($this->buttonClicked('resetProfileSettings') && $checkPageToken) {
                $author->setUserMeta([]);
                $author->disablePasswordSecCheck();
                if ($author->update() === false) {
                    $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
                }
                else {
                    $this->view->addNoticeMessage('SAVE_SUCCESS_RESETPROFILE');
                    $this->view->assign('reloadSite', true);
                }
            }

            if ($this->buttonClicked('userSave') && $checkPageToken) {
                $author->setUserName($this->getRequestVar('username'));
                $author->setEmail($this->getRequestVar('email'));
                $author->setDisplayName($this->getRequestVar('displayname'));
                $author->setRoll($this->getRequestVar('roll', [9]));
                $author->setUserMeta($this->getRequestVar('usermeta'));                
                $author->setUsrinfo($this->getRequestVar('usrinfo'));
                
                if ($this->getRequestVar('disabled') !== null) {
                    $author->setDisabled($this->getRequestVar('disabled', [9]));
                }

                $newpass         = $this->getRequestVar('password');
                $newpass_confirm = $this->getRequestVar('password_confirm');

                $save = true;
                if ($newpass && $newpass_confirm) {
                    if (md5($newpass) == md5($newpass_confirm)) {
                        $author->setPassword($newpass);
                    } else {
                        $save = false;
                        $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_MATCH');
                    }                    
                } else {
                    $author->disablePasswordSecCheck();
                }
                
                if ($save) {
                    $res = $author->update();

                    if ($res === false) {
                        $this->view->addErrorMessage('SAVE_FAILED_USER');
                    }
                    elseif ($res === true) {
                        $this->redirect ('users/list', array('edited' => 1));
                    }
                    elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_PASSWORDINSECURE) {
                        $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_SECURITY');
                    }
                    elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_EXISTS) {
                        $this->view->addErrorMessage('SAVE_FAILED_USER_EXISTS');
                    }
                    elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_NOEMAIL) {
                        $this->view->addErrorMessage('SAVE_FAILED_USER_EMAIL');
                    }
                }                
            }
            
            $this->userEnabled = $author->getDisabled();
            
            $this->view->assign('author', $author);
            $this->view->assign('avatar', \fpcm\model\users\author::getAuthorImageDataOrPath($author, false));
            
            return true;
            
        }
        
        public function process() {
            if (!parent::process()) return false;
            
            $userRolls = new \fpcm\model\users\userRollList();            
            $this->view->assign('userRolls', $userRolls->getUserRollsTranslated());
            $this->view->assign('languages', array_flip($this->lang->getLanguages()));
                        
            $timezones = [];
            
            foreach ($this->getTimeZones() as $area => $zones) {
                foreach ($zones as $zone) {
                    $timezones[$area][$zone] = $zone;
                }
            }
            
            $this->view->assign('timezoneAreas', $timezones);
            $this->view->assign('externalSave', true);
            $this->view->assign('articleLimitList', \fpcm\model\system\config::getAcpArticleLimits());
            $this->view->assign('defaultFontsizes', \fpcm\model\system\config::getDefaultFontsizes());
            $this->view->assign('showExtended', true);
            $this->view->assign('showImage', true);
            $this->view->setHelpLink('hl_options');
            
            $userList = new \fpcm\model\users\userList();
            $showDisableButton = (!$this->userEnabled && ($this->userId == $this->session->getUserId() || $userList->countActiveUsers() == 1))
                               ? false
                               : true;
            
            $this->view->assign('showDisableButton', $showDisableButton);
            $this->view->setViewJsFiles([
                \fpcm\classes\loader::libGetFileUrl('password-generator', 'password-generator.min.js'),
                'fileuploader.js'
            ]);
            $this->view->addJsVars([
                'fpcmNavigationActiveItemId' => 'submenu-itemnav-item-users',
                'fpcmDtMasks'                => $this->getDateTimeMasks(),
                'fpcmFieldSetAutoFocus'      => 'username',
                'fpcmJqUploadInit'           => 0
            ]);
            
            $this->view->render();            
        }

    }
?>
