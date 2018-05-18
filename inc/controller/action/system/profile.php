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
    protected $author;

    /**
     *
     * @var \Sonata\GoogleAuthenticator\GoogleAuthenticator
     */
    protected $gAuth;

    /**
     * @see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath()
    {
        return 'system/profile';
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        $this->author = $this->session->getCurrentUser();
        
        if ($this->config->system_2fa_auth) {
            include_once \fpcm\classes\loader::libGetFilePath('sonata-project'.DIRECTORY_SEPARATOR.'GoogleAuthenticator');
            $this->gAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        }

        $this->uploadImage($this->author);

        if (($this->buttonClicked('profileSave') || $this->buttonClicked('resetProfileSettings')) && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
        }

        $this->deleteImage($this->author);

        $this->reloadSite = 0;
        if ($this->buttonClicked('resetProfileSettings') && $this->checkPageToken) {
            $this->author->setUserMeta([]);
            $this->author->disablePasswordSecCheck();

            if ($this->author->update() === false) {
                $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
            } else {
                $this->view->addNoticeMessage('SAVE_SUCCESS_RESETPROFILE');
                $this->reloadSite = 1;
            }
        }

        if ($this->buttonClicked('profileSave') && $this->checkPageToken) {
            $this->author->setEmail($this->getRequestVar('email'));
            $this->author->setDisplayName($this->getRequestVar('displayname'));

            $metaData = $this->getRequestVar('usermeta');
            $this->author->setUserMeta($metaData);
            $this->author->setUsrinfo($this->getRequestVar('usrinfo'));

            $newpass = $this->getRequestVar('password');
            $newpass_confirm = $this->getRequestVar('password_confirm');

            $save = true;
            if ($newpass && $newpass_confirm) {
                if (md5($newpass) == md5($newpass_confirm)) {
                    $this->author->setPassword($newpass);
                } else {
                    $save = false;
                    $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_MATCH');
                }
            } else {
                $this->author->disablePasswordSecCheck();
            }

            $confirmCode = $this->getRequestVar('authCodeConfirm');
            $authSecret = $this->getRequestVar('authSecret');
            if ($this->config->system_2fa_auth && trim($confirmCode) && trim($authSecret) && $this->gAuth->checkCode($authSecret, $confirmCode)) {
                $this->author->setAuthtoken($authSecret);
            }

            if ($save) {
                $res = $this->author->update();

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

        $this->view->assign('author', $this->author);
        $this->view->assign('avatar', \fpcm\model\users\author::getAuthorImageDataOrPath($this->author, false));

        return true;
    }

    /**
     * Controller-Processing
     * @return boolean
     */
    public function process()
    {
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
        $this->view->assign('twoFaAuth', $this->config->system_2fa_auth);
        
        if ($this->config->system_2fa_auth) {
            if (!$this->author->getAuthtoken()) {
                $secret = $this->gAuth->generateSecret();
                $this->view->assign('qrCode', \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($this->author->getEmail(), $secret, $this->lang->translate('HEADLINE')));
                $this->view->assign('secret', $secret);
            }
            else {
                $this->view->assign('qrCode', false);
                $this->view->assign('secret', false);
            }
        }

        $this->view->addJsLangVars(['SAVE_FAILED_PASSWORD_MATCH']);
        $this->view->addJsVars(array(
            'dtMasks' => $this->getDateTimeMasks(),
            'reloadPage' => $this->reloadSite,
            'jqUploadInit' => 0
        ));

        $this->view->assign('articleLimitList', \fpcm\model\system\config::getAcpArticleLimits());
        $this->view->assign('defaultFontsizes', \fpcm\model\system\config::getDefaultFontsizes());
        $this->view->assign('showDisableButton', false);
        $this->view->addJsFiles([
            \fpcm\classes\loader::libGetFileUrl('password-generator/password-generator.min.js'),
            'profile.js', 'fileuploader.js'
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
