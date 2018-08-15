<?php

/**
 * User edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

class userbase extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\common\timezone,
        \fpcm\controller\traits\users\authorImages;

    /**
     *
     * @var int
     */
    protected $userId = null;

    /**
     *
     * @var \fpcm\model\users\author
     */
    protected $user;

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'users'];
    }

    /**
     * 
     * @return string
     */
    protected function getActiveNavigationElement()
    {
        return 'submenu-itemnav-item-users';
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'HL_OPTIONS_USERS';
    }

    /**
     * 
     * @return boolean
     */
    protected function initActionObjects()
    {
        $this->user = new \fpcm\model\users\author($this->userId);
        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function request()
    {
        $this->initFormData();
        if (($this->buttonClicked('userSave') || $this->buttonClicked('resetProfileSettings')) && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            $this->view->assign('author', $this->user);
            return true;
        }

        $this->save();
        $this->view->assign('author', $this->user);
        return true;
    }

    /**
     * Konstruktor
     */
    public function process()
    {
        $userRolls = new \fpcm\model\users\userRollList();
        $this->view->assign('userRolls', $userRolls->getUserRollsTranslated());
        $this->view->assign('languages', array_flip($this->language->getLanguages()));

        $this->view->assign('timezoneAreas', $this->getTimeZonesAreas());
        $this->view->assign('externalSave', true);
        $this->view->assign('articleLimitList', \fpcm\model\system\config::getAcpArticleLimits());
        $this->view->assign('defaultFontsizes', \fpcm\model\system\config::getDefaultFontsizes());
        $this->view->assign('filemanagerViews', \fpcm\components\components::getFilemanagerViews());
        $this->view->assign('inProfile', false);

        $this->view->addJsFiles([
            \fpcm\classes\loader::libGetFileUrl('password-generator/password-generator.min.js'),
            'fileuploader.js'
        ]);

        $this->view->addJsVars([
            'dtMasks' => $this->getDateTimeMasks(),
            'jqUploadInit' => 0
        ]);

        $this->view->addJsLangVars(['SAVE_FAILED_PASSWORD_MATCH']);
        $this->view->setFieldAutofocus('username');
    }

    /**
     * 
     * @return boolean
     */
    protected function save()
    {
        if (!$this->buttonClicked('userSave')) {
            return true;
        }

        $data = $this->initFormData();
        if (!$this->userId) {
            $this->user->setRegistertime(time());
        }

        $save = true;
        if ($data['password'] && $data['password_confirm']) {
            if (md5($data['password']) == md5($data['password_confirm'])) {
                $this->user->setPassword($data['password']);
            } else {
                $save = false;
                $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_MATCH');
            }
        } else {
            $this->user->disablePasswordSecCheck();
            $this->user->setPassword(null);
        }

        if ($this->getRequestVar('disable2Fa', [\fpcm\classes\http::FILTER_CASTINT]) === 1) {
            $this->user->setAuthtoken('');
        }

        if ($save) {
            $res = ( $this->userId ? $this->user->update() : $this->user->save() );

            if ($res === false) {
                $this->view->addErrorMessage('SAVE_FAILED_USER');
                fpcmLogSystem('Failed to save changes made to user '.$this->user->getUsername().'.');
            } elseif ($res === true) {
                fpcmLogSystem('Changes made to user '.$this->user->getUsername().' successfull.');
                $this->redirect('users/list', array('edited' => 1));
            } elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_PASSWORDINSECURE) {
                fpcmLogSystem('Failed to save changes made to user '.$this->user->getUsername().' due to insecure password.');
                $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_SECURITY');
            } elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_EXISTS) {
                fpcmLogSystem('Failed to save user '.$this->user->getUsername().', username already exists.');
                $this->view->addErrorMessage('SAVE_FAILED_USER_EXISTS');
            } elseif ($res === \fpcm\model\users\author::AUTHOR_ERROR_NOEMAIL) {
                fpcmLogSystem('Failed to save changes made to user '.$this->user->getUsername().' due to invalid e-mail address.');
                $this->view->addErrorMessage('SAVE_FAILED_USER_EMAIL');
            }
        }
        
        return true;
    }

    /**
     * 
     * @return array
     */
    protected function initFormData() : array
    {
        $data = $this->getRequestVar('data');
        if (!isset($data['username'])) {
            return [];
        }

        $this->user->setUserName($data['username']);
        $this->user->setEmail($data['email']);
        $this->user->setDisplayName($data['displayname']);
        $this->user->setRoll($data['roll']);
        $this->user->setUserMeta(isset($data['usermeta']) ? $data['usermeta'] : []);
        $this->user->setUsrinfo(isset($data['usrinfo']) ? $data['usrinfo'] : '');
        $this->user->setDisabled(isset($data['disabled']) ? $data['disabled'] : 0);
        $this->user->setChangeTime(time());
        $this->user->setChangeUser((int) $this->session->getUserId());

        return $data;
    }

}

?>
