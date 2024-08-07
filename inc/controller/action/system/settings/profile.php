<?php

/**
 * Pofil controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system\settings;

class profile extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\common\timezone,
        \fpcm\controller\traits\users\authorImages,
        \fpcm\controller\traits\users\settings;

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
        return 'users/usereditor';
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

        $this->user = $this->session->getCurrentUser();
        $this->initUploader($this->user);

        $this->deleteImage($this->user);
        $this->uploadImage($this->user);

        if ($this->config->system_2fa_auth) {
            include_once \fpcm\classes\loader::libGetFilePath('sonata-project'.DIRECTORY_SEPARATOR.'GoogleAuthenticator');
            $this->gAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        }

        if (($this->buttonClicked('profileSave') || $this->buttonClicked('resetProfileSettings')) && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
        }

        $this->reloadSite = 0;

        $this->resetProfileSettings();
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

        $metaData = $this->user->getUserMeta();
        $metaData->mergeData($this->request->fromPOST('usermeta'));
        
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
        $this->settingsToView();
        $this->twoFactorAuthForm();

        $this->view->assign('externalSave', true);
        $this->view->assign('inProfile', true);
        $this->view->assign('showExtended', true);
        $this->view->assign('showImage', true);

        $this->view->addJsLangVars(['SAVE_FAILED_PASSWORD_MATCH', 'SAVE_FAILED_PASSWORD_SECURITY', 'SAVE_FAILED_PASSWORD_SECURITY_PWNDPASS']);
        $this->view->addJsVars([
            'dtMasks' => $this->getDateTimeMasks(),
            'reloadPage' => $this->reloadSite,
        ]);

        $this->view->assign('showDisableButton', false);
        $this->view->addJsFiles([ \fpcm\classes\loader::libGetFileUrl('nkorg/passgen/passgen.js'), 'users/profile.js', 'users/edit.js' ]);

        $this->view->addButtons([
            (new \fpcm\view\helper\saveButton('profileSave'))->setPrimary(),
            (new \fpcm\view\helper\submitButton('resetProfileSettings'))->setText('GLOBAL_RESET')->setIcon('undo')
        ]);  

        $this->view->addTabs('profile', [
           (new \fpcm\view\helper\tabItem('user'))->setText('HL_PROFILE')->setFile($this->getViewPath()),
           (new \fpcm\view\helper\tabItem('extended'))->setText('GLOBAL_EXTENDED')->setFile('users/usereditor_extended.php'),
           (new \fpcm\view\helper\tabItem('meta'))->setText('USERS_META_OPTIONS')->setFile('users/editormeta.php'),
        ], 'fpcm ui-tabs-function-autoinit', $this->getActiveTab());

        $this->view->setFormAction('system/profile');

        $this->view->render();
    }

    protected function getHelpLink()
    {
        return 'hl_profile';
    }

}
