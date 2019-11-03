<?php

/**
 * User edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

class useredit extends userbase {

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'users/useredit';
    }

    /**
     * 
     * @return bool
     */
    public function request()
    {
        $this->userId = $this->getRequestVar('userid', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        if (!$this->userId) {
            $this->redirect('users/list');
            return false;
        }
        
        $this->initActionObjects();
        parent::request();

        if (!$this->user->exists()) {
            $this->view = new \fpcm\view\error('LOAD_FAILED_USER', 'users/list');
            $this->view->render();
            exit;
        }
        
        if ($this->config->system_2fa_auth) {
            include_once \fpcm\classes\loader::libGetFilePath('sonata-project'.DIRECTORY_SEPARATOR.'GoogleAuthenticator');
            $this->gAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
        }
        
        $this->view->setFormAction($this->user->getEditLink(), [], true);

        if (!$this->checkPageToken) {
            return true;
        }

        $this->uploadImage($this->user);
        $this->deleteImage($this->user);

        if ($this->buttonClicked('resetProfileSettings')) {
            if ($this->user->resetProfileSettings() === false) {
                $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
            } else {
                $this->view->addNoticeMessage('SAVE_SUCCESS_RESETPROFILE');
                $this->view->assign('reloadSite', true);
            }
        }

        if ($this->buttonClicked('resetDashboardSettings')) {
            if ($this->user->resetDashboard() === false) {
                $this->view->addErrorMessage('SAVE_FAILED_USER_RESETDASHCONTAINER');
            } else {
                $this->view->addNoticeMessage('SAVE_SUCCESS_RESETDASHCONTAINER');
            }
        }

        return true;
    }

    public function process()
    {
        parent::process();

        $userList = new \fpcm\model\users\userList();
        $showDisableButton = (!$this->user->getDisabled() && ($this->userId == $this->session->getUserId() || $userList->countActiveUsers() == 1)) ? false : true;
        
        $this->twoFactorAuthForm();
        $this->view->assign('showDisableButton', $showDisableButton);
        $this->view->assign('showExtended', true);
        $this->view->assign('showImage', true);
        $this->view->assign('avatar', \fpcm\model\users\author::getAuthorImageDataOrPath($this->user, false));

        $buttons = [
            (new \fpcm\view\helper\saveButton('userSave'))->setClass('fpcm-ui-button-primary'),
            (new \fpcm\view\helper\submitButton('resetProfileSettings'))->setText('GLOBAL_RESET')->setIcon('undo')
        ];
        
        if ($this->userId != $this->session->getUserId()) {
            $buttons[] = (new \fpcm\view\helper\checkbox('data[passInfoUser]'))->setText('USERS_PASSWORD_SENDUSERINFO');
        }

        $this->view->addButtons($buttons);
        
        $this->view->addJsFiles(['users/edit.js']);
        $this->view->render();
    }

}

?>
