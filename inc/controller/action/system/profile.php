<?php
    /**
     * Pofil controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class profile extends \fpcm\controller\abstracts\controller {
        
        use \fpcm\controller\traits\common\timezone,
            \fpcm\controller\traits\users\authorImages;
        
        /**
         *
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         *
         * @var bool
         */
        protected $reloadSite;

        /**
         * Controller-Processing
         */
        public function __construct() {
            parent::__construct();
            $this->view = new \fpcm\model\view\acp('profile', 'system');
        }

        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            if (!$this->session->exists()) {
                $this->redirectNoSession();
                return false;
            }

            $author = $this->session->getCurrentUser();

            $this->uploadImage($author);

            $pageTokenCheck = $this->checkPageToken();
            if (($this->buttonClicked('profileSave') || $this->buttonClicked('resetProfileSettings')) && !$pageTokenCheck) {
                $this->view->addErrorMessage('CSRF_INVALID');
            }

            $this->deleteImage($author);

            $this->reloadSite = 0;
            if ($this->buttonClicked('resetProfileSettings') && $pageTokenCheck) {
                $author->setUserMeta([]);
                $author->disablePasswordSecCheck();

                if ($author->update() === false) {
                    $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
                }
                else {
                    $this->view->addNoticeMessage('SAVE_SUCCESS_RESETPROFILE');
                    $this->reloadSite = 1;
                }

            }
            
            if ($this->buttonClicked('profileSave') && $pageTokenCheck) {
                $author->setEmail($this->getRequestVar('email'));
                $author->setDisplayName($this->getRequestVar('displayname'));
                
                $metaData = $this->getRequestVar('usermeta');
                $author->setUserMeta($metaData);
                $author->setUsrinfo($this->getRequestVar('usrinfo'));

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
                        $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
                    }
                    elseif ($res === true) {
                        $this->reloadSite = ($metaData['system_lang'] != $this->config->system_lang ? 1 : 0);
                        $this->view->addNoticeMessage('SAVE_SUCCESS_EDITUSER_PROFILE');
                    }
                    elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_PASSWORDINSECURE) {
                        $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_SECURITY');
                    }
                    elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_NOEMAIL) {
                        $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILEEMAIL');
                    }
                }                
            }
            
            $this->view->assign('author', $author);
            $this->view->assign('avatar', \fpcm\model\users\author::getAuthorImageDataOrPath($author, false));
            
            return true;
            
        }
        
        /**
         * Controller-Processing
         * @return boolean
         */
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
            $this->view->assign('inProfile', true);
            $this->view->assign('showExtended', true);
            $this->view->assign('showImage', true);
            $this->view->setHelpLink('hl_profile');
            
            $this->view->addJsVars(array(
                'fpcmDtMasks'       => $this->getDateTimeMasks(),
                'fpcmReloadPage'    => $this->reloadSite,
                'fpcmJqUploadInit'  => 0
            ));

            $this->view->assign('articleLimitList', \fpcm\model\system\config::getAcpArticleLimits());
            $this->view->assign('defaultFontsizes', \fpcm\model\system\config::getDefaultFontsizes());
            $this->view->assign('showDisableButton', false);
            $this->view->setViewJsFiles([
                \fpcm\classes\loader::libGetFileUrl('password-generator', 'password-generator.min.js'),
                'profile.js', 'fileuploader.js'
            ]);

            $this->view->render();            
        }

    }
?>
