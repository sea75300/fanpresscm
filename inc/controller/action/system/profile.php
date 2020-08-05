<?php

/**
 * Pofil controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

class profile extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

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
     *
     * @var \fpcm\components\fileupload\htmlupload
     */
    protected $uploader;

    /**
     * @see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'system/profile';
    }

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->profile;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->uploader = \fpcm\components\components::getFileUploader('\\fpcm\\components\\fileupload\\htmlupload');
        $this->view->setViewVars($this->uploader->getViewVars());
        
        $this->user = $this->session->getCurrentUser();
        
        if ($this->config->system_2fa_auth) {
            include_once \fpcm\classes\loader::libGetFilePath('sonata-project'.DIRECTORY_SEPARATOR.'GoogleAuthenticator');
            $this->gAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        }

        $this->uploadImage($this->user);

        if (($this->buttonClicked('profileSave') || $this->buttonClicked('resetProfileSettings')) && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
        }

        $this->reloadSite = 0;

        $this->deleteImage($this->user);
        $this->resetProfileSettings();
        $this->resetDashboardSettings();
        $this->saveProfile();

        $this->view->assign('author', $this->user);
        $this->view->assign('avatar', \fpcm\model\users\author::getAuthorImageDataOrPath($this->user, false));
        return true;
    }
    
    /**
     * Reset profile settings
     * @return bool
     */
    private function resetProfileSettings() : bool
    {
        if (!$this->buttonClicked('resetProfileSettings') || !$this->checkPageToken) {
            return false;
        }

        if ($this->user->resetProfileSettings() === false) {
            $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
            return false;
        }

        $this->view->addNoticeMessage('SAVE_SUCCESS_RESETPROFILE');
        $this->reloadSite = 1;
        return true;
    }
    
    /**
     * Reset dashboard container positions
     * @return bool
     */
    private function resetDashboardSettings() : bool
    {
        if (!$this->buttonClicked('resetDashboardSettings') || !$this->checkPageToken) {
            return false;
        }

        if ($this->user->resetDashboard() === false) {
            $this->view->addErrorMessage('SAVE_FAILED_USER_RESETDASHCONTAINER');
            return false;
        }

        $this->view->addNoticeMessage('SAVE_SUCCESS_RESETDASHCONTAINER');
        return true;
    }
    
    /**
     * Execute save process
     * @return bool
     */
    private function saveProfile() : bool
    {
        if (!$this->buttonClicked('profileSave') || !$this->checkPageToken) {
            return true;
        }

        $saveData = $this->request->fromPOST('data');
        if ($saveData['email'] !== $this->user->getEmail() && !$this->checkCurrentPass($saveData['current_pass'])) {
            return false;
        }
        
        $this->user->setEmail($saveData['email']);
        $this->user->setDisplayName($saveData['displayname']);

        $metaData = $this->request->fromPOST('usermeta');
        $this->user->setUserMeta($metaData);
        $this->user->setUsrinfo($saveData['usrinfo']);
        $this->user->setChangeTime(time());
        $this->user->setChangeUser((int) $this->session->getUserId());

        $save = true;
        if ($saveData['password'] && $saveData['password_confirm']) {

            if (!$this->checkCurrentPass($saveData['current_pass'])) {
                return false;
            }

            if (\fpcm\classes\tools::getHash($saveData['password']) !==
                \fpcm\classes\tools::getHash($saveData['password_confirm'])) {
                $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_MATCH');
                return false;
            }

            $this->user->setPassword($saveData['password']);
        } else {
            $this->user->disablePasswordSecCheck();
        }

        if ($this->request->fromPOST('disable2Fa', [\fpcm\model\http\request::FILTER_CASTINT]) === 1) {
            $this->user->setAuthtoken('');
        }

        if ($this->config->system_2fa_auth &&
            !empty($saveData['authCodeConfirm']) && trim($saveData['authCodeConfirm']) &&
            !empty($saveData['authSecret']) && trim($saveData['authSecret']) &&
            $this->gAuth->checkCode($saveData['authSecret'], $saveData['authCodeConfirm'])) {
            $this->user->setAuthtoken($saveData['authSecret']);
        }

        $res = $this->user->update();
        if ($res === false) {
            $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
            return false;
        }

        if ($res === \fpcm\model\users\author::AUTHOR_ERROR_PASSWORDINSECURE) {
            $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_SECURITY');
            return false;
        }

        if ($res === \fpcm\model\users\author::AUTHOR_ERROR_NOEMAIL) {
            $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILEEMAIL');
            return false;
        }

        $this->reloadSite = ($metaData['system_lang'] != $this->config->system_lang ? 1 : 0);
        $this->view->addNoticeMessage('SAVE_SUCCESS_EDITUSER_PROFILE');        
        return true;
    }

    /**
     * Validates current password
     * @param string $currentPass
     * @return bool
     */
    private function checkCurrentPass(string $currentPass) : bool
    {
        if (password_verify("{$currentPass}", "{$this->user->getPasswd()}") ||
            hash_equals($this->user->getPasswd(), md5($currentPass) ) ) {
            return true;
        }

        $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_MATCH_CURRENT');
        return false;
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

        $this->view->addJsLangVars(['SAVE_FAILED_PASSWORD_MATCH', 'SAVE_FAILED_PASSWORD_SECURITY', 'SAVE_FAILED_PASSWORD_SECURITY_PWNDPASS']);
        $this->view->addJsVars(array_merge( [
            'dtMasks' => $this->getDateTimeMasks(),
            'reloadPage' => $this->reloadSite,
        ], $this->uploader->getJsVars() ));

        $this->view->setActiveTab($this->getActiveTab());
        $this->view->assign('articleLimitList', \fpcm\model\system\config::getAcpArticleLimits());
        $this->view->assign('defaultFontsizes', \fpcm\model\system\config::getDefaultFontsizes());
        $this->view->assign('filemanagerViews', \fpcm\components\components::getFilemanagerViews());
        $this->view->assign('showDisableButton', false);
        $this->view->addJsFiles(array_merge([
            \fpcm\classes\loader::libGetFileUrl('nkorg/passgen/passgen.js'),
            'users/profile.js', 'users/edit.js'
        ], $this->uploader->getJsFiles() ));

        $this->view->addButtons([
            (new \fpcm\view\helper\saveButton('profileSave'))->setClass('fpcm-ui-button-primary'),
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
