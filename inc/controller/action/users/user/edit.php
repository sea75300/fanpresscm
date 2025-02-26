<?php

/**
 * User edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users\user;

class edit
extends base
implements \fpcm\controller\interfaces\requestFunctions {

    use \fpcm\controller\traits\users\deleteDialog;
    
    /**
     *
     * @var bool
     */
    protected $showExtended = true;

    /**
     *
     * @return bool
     */
    public function request()
    {
        $this->userId = $this->request->getID();

        if (!$this->userId) {
            $this->redirect('users/list');
            return false;
        }
        
        $this->initActionObjects();
        $this->initUploader($this->user);

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

        return true;
    }

    public function process()
    {
        parent::process();

        $userList = new \fpcm\model\users\userList();
        $showDisableButton = (!$this->user->getDisabled() && ($this->userId == $this->session->getUserId() || $userList->countActiveUsers() == 1)) ? false : true;
        
        $this->initDeleteConfirmDialog($userList);

        $this->twoFactorAuthForm();
        $this->view->assign('showDisableButton', $showDisableButton);
        $this->view->assign('showExtended', true);
        $this->view->assign('showImage', true);
        $this->view->assign('avatar', \fpcm\model\users\author::getAuthorImageDataOrPath($this->user, false));

        $buttons = [
            (new \fpcm\view\helper\saveButton('userSave'))->setPrimary(),
            (new \fpcm\view\helper\submitButton('resetProfileSettings'))->setText('GLOBAL_RESET')->setIcon('undo')->setIconOnly(),
            (new \fpcm\view\helper\linkButton('usermail'))->setUrl('mailto:'.$this->user->getEmail())->setIcon('envelope')->setIconOnly()->setText('GLOBAL_WRITEMAIL')
        ];

        if ($this->userId != $this->session->getUserId()) {
            $buttons[] = (new \fpcm\view\helper\deleteButton('userDelete'))->setData([
                'oid' => $this->userId,
                'fn' => 'deleteUser',
                'dest' => 'moveDeleteArticles',
                'redirect' => \fpcm\classes\tools::getControllerLink('users/list')
            ]);
            $buttons[] = (new \fpcm\view\helper\checkbox('data[passInfoUser]'))->setText('USERS_PASSWORD_SENDUSERINFO')->setSwitch(true)->setWrapperClass('ms-2 mb-0 mt-1');
        }

        $this->view->addButtons($buttons);

        $chgUser = new \fpcm\model\users\author($this->user->getChangeUser());

        $this->view->assign('createInfo', new \fpcm\view\helper\dateText($this->user->getRegistertime()));

        $this->view->assign('changeInfo', $this->language->translate('GLOBAL_USER_ON_TIME', [
            '{{username}}' => $chgUser->exists() ? $chgUser->getDisplayname() : $this->language->translate('GLOBAL_NOTFOUND'),
            '{{time}}'     => new \fpcm\view\helper\dateText($this->user->getChangetime())
        ]));

        $this->view->addJsVars([
            'userImgRedir' => \fpcm\classes\tools::getFullControllerLink('users/edit', [
                'id' => $this->user->getId(),
                'rg' => 1
            ])
        ]);

        $this->view->addAjaxPageToken('users/actions');
        $this->view->addJsFiles(['users/functions.js', 'users/edit.js']);
        $this->view->render();
    }

    protected function initTabs()
    {
        $tabs = [];
        $tabs[] = (new \fpcm\view\helper\tabItem('edit'))->setText('USERS_EDIT')->setFile( $this->getViewPath() . '.php');

        if ($this->showExtended) {
            $tabs[] = (new \fpcm\view\helper\tabItem('extended'))->setText('GLOBAL_EXTENDED')->setFile('users/usereditor_extended.php');
        }

        $tabs[] = (new \fpcm\view\helper\tabItem('meta'))->setText('USERS_META_OPTIONS')->setFile('users/editormeta.php');

        $this->view->addTabs('users', $tabs, 'fpcm ui-tabs-autoinit', $this->getActiveTab());

    }

    protected function onResetProfileSettings()
    {
        if (!$this->checkPageToken) {
            return true;
        }

        if ($this->user->resetProfileSettings() === false) {
            $this->view->addErrorMessage('SAVE_FAILED_USER_PROFILE');
            return false;
        }

        $this->view->addNoticeMessage('SAVE_SUCCESS_RESETPROFILE');
        $this->view->assign('reloadSite', true);
        return true;
    }

}
