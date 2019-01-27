<?php

/**
 * Pofil controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

class profile extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\common\timezone,
        \fpcm\controller\traits\users\authorImages;

    /**
     *
     * @var bool
     */
    protected $reloadSite;

    /**
     *
     * @var \fpcm\model\users\author
     */
    protected $user;

    /**
     * @see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'system/profile';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->user = $this->session->getCurrentUser();
        
        if ($this->config->system_2fa_auth) {
            include_once \fpcm\classes\loader::libGetFilePath('sonata-project'.DIRECTORY_SEPARATOR.'GoogleAuthenticator');
            $this->gAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        }

        $this->uploadImage($this->user);

        if (($this->buttonClicked('profileSave') || $this->buttonClicked('resetProfileSettings')) && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
        }

        $this->deleteImage($this->user);

        $this->reloadSite = 0;
        if ($this->buttonClicked('resetProfileSettings') && $this->checkPageToken) {
            if ($this->user->resetProfileSettings() === false) {
                $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
            } else {
                $this->view->addNoticeMessage('SAVE_SUCCESS_RESETPROFILE');
                $this->reloadSite = 1;
            }
        }

        if ($this->buttonClicked('resetDashboardSettings') && $this->checkPageToken) {
            if ($this->user->resetDashboard() === false) {
                $this->view->addErrorMessage('SAVE_FAILED_USER_RESETDASHCONTAINER');
            } else {
                $this->view->addNoticeMessage('SAVE_SUCCESS_RESETDASHCONTAINER');
            }
        }

        if ($this->buttonClicked('profileSave') && $this->checkPageToken) {
            
            $saveData = $this->getRequestVar('data');
            
            $this->user->setEmail($saveData['email']);
            $this->user->setDisplayName($saveData['displayname']);

            $metaData = $this->getRequestVar('usermeta');
            $this->user->setUserMeta($metaData);
            $this->user->setUsrinfo($saveData['usrinfo']);
            $this->user->setChangeTime(time());
            $this->user->setChangeUser((int) $this->session->getUserId());

            $save = true;
            if ($saveData['password'] && $saveData['password_confirm']) {
                if (md5($saveData['password']) == md5($saveData['password_confirm'])) {
                    $this->user->setPassword($saveData['password']);
                } else {
                    $save = false;
                    $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_MATCH');
                }
            } else {
                $this->user->disablePasswordSecCheck();
            }

            if ($this->getRequestVar('disable2Fa', [\fpcm\classes\http::FILTER_CASTINT]) === 1) {
                $this->user->setAuthtoken('');
            }

            if ($this->config->system_2fa_auth &&
                trim($saveData['authCodeConfirm']) &&
                trim($saveData['authSecret']) &&
                $this->gAuth->checkCode($saveData['authSecret'], $saveData['authCodeConfirm'])) {
                $this->user->setAuthtoken($saveData['authSecret']);
            }

            if ($save) {
                $res = $this->user->update();

                if ($res === false) {
                    $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
                } elseif ($res === true) {
                    $this->reloadSite = ($metaData['system_lang'] != $this->config->system_lang ? 1 : 0);
                    $this->view->addNoticeMessage('SAVE_SUCCESS_EDITUSER_PROFILE');
                } elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_PASSWORDINSECURE) {
                    $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_SECURITY');
                } elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_NOEMAIL) {
                    $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILEEMAIL');
                }
            }
        }

        $this->view->assign('author', $this->user);
        $this->view->assign('avatar', \fpcm\model\users\author::getAuthorImageDataOrPath($this->user, false));

        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $userRolls = new \fpcm\model\users\userRollList();
        $this->view->assign('userRolls', $userRolls->getUserRollsTranslated());
        $this->view->assign('languages', array_flip($this->language->getLanguages()));
        $this->twoFactorAuthForm();

        $this->view->assign('timezoneAreas', $this->getTimeZonesAreas());
        $this->view->assign('externalSave', true);
        $this->view->assign('inProfile', true);
        $this->view->assign('showExtended', true);
        $this->view->assign('showImage', true);

        $this->view->addJsLangVars(['SAVE_FAILED_PASSWORD_MATCH', 'SAVE_FAILED_PASSWORD_SECURITY']);
        $this->view->addJsVars(array(
            'dtMasks' => $this->getDateTimeMasks(),
            'reloadPage' => $this->reloadSite,
            'jqUploadInit' => 0,
            'activeTab' => $this->getActiveTab()
        ));

        $this->view->assign('activeTab', $this->getActiveTab());
        $this->view->assign('articleLimitList', \fpcm\model\system\config::getAcpArticleLimits());
        $this->view->assign('defaultFontsizes', \fpcm\model\system\config::getDefaultFontsizes());
        $this->view->assign('filemanagerViews', \fpcm\components\components::getFilemanagerViews());
        $this->view->assign('showDisableButton', false);
        $this->view->addJsFiles([
            \fpcm\classes\loader::libGetFileUrl('password-generator/password-generator.min.js'),
            'profile.js', 'fileuploader.js', 'useredit.js'
        ]);

        $this->view->addButtons([
            (new \fpcm\view\helper\saveButton('profileSave')),
            (new \fpcm\view\helper\submitButton('resetProfileSettings'))->setText('GLOBAL_RESET')->setIcon('undo')
        ]);

        $this->view->setFormAction('system/profile');

        $this->view->render();
    }

    protected function getHelpLink()
    {
        return 'hl_profile';
    }

}

?>
