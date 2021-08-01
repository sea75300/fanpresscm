<?php

/**
 * User edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

class userbase extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

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
     * @var bool
     */
    protected $showExtended = false;

    public function isAccessible(): bool
    {
        return $this->permissions->system->users;
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
    protected function initActionObjects()
    {
        $this->user = new \fpcm\model\users\author($this->userId);
        $this->user->overrideConfig();
        return true;
    }

    /**
     * 
     * @return bool
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
        $this->initTabs();
        
        $userRolls = new \fpcm\model\users\userRollList();
        $this->view->assign('userRolls', $userRolls->getUserRollsTranslated());
        $this->view->assign('languages', array_flip($this->language->getLanguages()));

        $this->view->assign('timezoneAreas', $this->getTimeZonesAreas());
        $this->view->assign('externalSave', true);
        $this->view->assign('articleLimitList', \fpcm\model\system\config::getAcpArticleLimits());
        $this->view->assign('defaultFontsizes', \fpcm\model\system\config::getDefaultFontsizes());
        $this->view->assign('filemanagerViews', \fpcm\components\components::getFilemanagerViews());
        $this->view->assign('inProfile', false);

        $this->view->addJsFiles([ \fpcm\classes\loader::libGetFileUrl('nkorg/passgen/passgen.js'), ]);

        $this->view->addJsVars([
            'dtMasks' => $this->getDateTimeMasks()
        ]);

        $this->view->addJsLangVars(['SAVE_FAILED_PASSWORD_MATCH', 'SAVE_FAILED_PASSWORD_SECURITY', 'SAVE_FAILED_PASSWORD_SECURITY_PWNDPASS']);
    }

    /**
     * 
     * @return bool
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

        if ($this->request->fromPOST('disable2Fa', [\fpcm\model\http\request::FILTER_CASTINT]) === 1) {
            $this->user->setAuthtoken('');
        }

        if ($save) {
            $res = ( $this->userId ? $this->user->update() : $this->user->save() );
            
            if (isset($data['passInfoUser']) && $data['password'] && $data['password_confirm'] && filter_var($this->user->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $msg = (new \fpcm\classes\email(
                    $this->user->getEmail(),
                    $this->language->translate('PASSWORD_NEWPASSWORDSET_SUBJECT'),
                    $this->language->translate('PASSWORD_NEWPASSWORDSET_TEXT', [
                        'username' => $this->user->getUsername(),
                        'newpass' => $data['password']
                    ]),
                    false,
                    true
                ));

                $msg->submit();
            }

            if ($res === false) {
                $this->view->addErrorMessage('SAVE_FAILED_USER');
                fpcmLogSystem('Failed to save changes made to user '.$this->user->getUsername().'.');
            } elseif ($res === true) {
                fpcmLogSystem('Changes made to user '.$this->user->getUsername().' successful.');
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
        $data = $this->request->fromPOST('data');
        if (!isset($data['username'])) {
            return [];
        }

        $userMeta = $this->request->fromPOST('usermeta');
        if (!is_array($userMeta)) {
            $userMeta = [];
        }
        
        $this->user->setUserName($data['username']);
        $this->user->setEmail($data['email']);
        $this->user->setDisplayName($data['displayname']);
        $this->user->setRoll($data['roll']);
        $this->user->setUserMeta($userMeta);
        $this->user->setUsrinfo(isset($data['usrinfo']) ? $data['usrinfo'] : '');
        $this->user->setDisabled(isset($data['disabled']) ? $data['disabled'] : 0);
        $this->user->setChangeTime(time());
        $this->user->setChangeUser((int) $this->session->getUserId());

        return $data;
    }

}

?>
