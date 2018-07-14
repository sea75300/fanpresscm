<?php

/**
 * Login controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

class userlist extends \fpcm\controller\abstracts\controller {

    /**
     *
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     *
     * @var \fpcm\model\users\userRollList
     */
    protected $rollList;

    /**
     *
     * @var \fpcm\model\articles\articlelist
     */
    protected $articleList;

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'users/userlist';
    }

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
        $this->userList     = new \fpcm\model\users\userList();
        $this->rollList     = new \fpcm\model\users\userRollList();
        $this->articleList  = new \fpcm\model\articles\articlelist();
        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function request()
    {
        if ($this->getRequestVar('added') == 1) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ADDUSER');
        } elseif ($this->getRequestVar('added') == 2) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ADDROLL');
        }

        if ($this->getRequestVar('edited') == 1) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_EDITUSER');
        } elseif ($this->getRequestVar('edited') == 2) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_EDITROLL');
        }

        if (($this->buttonClicked('disableUser') ||
                $this->buttonClicked('enableUser') ||
                $this->buttonClicked('deleteUser') ||
                $this->buttonClicked('deleteRoll') ) && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $userIds = $this->getRequestVar('userids');
        if ($this->buttonClicked('disableUser') && $userIds) {
            $this->disableUsers();
        }

        if ($this->buttonClicked('enableUser') && $userIds) {
            $this->enableUsers();
        }

        if ($this->buttonClicked('deleteUser') && $userIds) {
            $this->deleteUser();
        }

        $rollId = $this->getRequestVar('rollids', [\fpcm\classes\http::FILTER_CASTINT]);
        if ($this->buttonClicked('deleteRoll') && $rollId) {
            $roll = new \fpcm\model\users\userRoll($rollId);
            if (!$roll->delete()) {
                $this->view->addErrorMessage('DELETE_FAILED_ROLL');
                return true;
            }

            $this->view->addNoticeMessage('DELETE_SUCCESS_ROLL');
        }

        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function process()
    {
        $rollsPerm = $this->permissions->check(['system' => 'rolls']);
        
        $this->view->assign('usersListSelect', $this->userList->getUsersNameList());
        $this->view->assign('rollPermissions', $rollsPerm);
        $this->view->addJsFiles(['users.js']);
        $this->view->addJsLangVars(['USERS_ARTICLES_SELECT', 'HL_OPTIONS_PERMISSIONS']);
        $this->view->setFormAction('users/list');

        $buttons = [
            (new \fpcm\view\helper\linkButton('addUser'))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/add'))->setText('USERS_ADD')->setClass('fpcm-loader fpcm-ui-maintoolbarbuttons-tab1')->setIcon('user-plus'),
            (new \fpcm\view\helper\submitButton('disableUser'))->setText('GLOBAL_DISABLE')->setClass('fpcm-ui-maintoolbarbuttons-tab1 fpcm-ui-button-confirm')->setIcon('user-slash'),
            (new \fpcm\view\helper\submitButton('enableUser'))->setText('GLOBAL_ENABLE')->setClass('fpcm-ui-maintoolbarbuttons-tab1 fpcm-ui-button-confirm')->setIcon('user-check'),
            (new \fpcm\view\helper\deleteButton('deleteUser'))->setClass('fpcm-ui-maintoolbarbuttons-tab1')            
        ];
        
        if ($rollsPerm) {
            $buttons[] = (new \fpcm\view\helper\linkButton('addRoll'))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/addroll'))->setText('USERS_ROLL_ADD')->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden')->setIcon('users');
            $buttons[] = (new \fpcm\view\helper\deleteButton('deleteRoll'))->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden fpcm-ui-button-confirm');
        }

        $this->view->addButtons($buttons);
        $this->createUsersView();
        
        if ($rollsPerm) {
            $this->createRollsView();
        }

        $this->view->render();
        return true;
    }

    /**
     * Benutzer-Dataview erzeugen
     * @return boolean
     */
    private function createUsersView()
    {
        $usersInGroups = $this->userList->getUsersAll(true);
        $userGroups    = $this->rollList->getUserRollsByIds(array_keys($usersInGroups));
        
        $rolls = $this->rollList->getUserRollsTranslated();
        
        $dataView = new \fpcm\components\dataView\dataView('userlist');
        
        $dataView->addColumns([
            (new \fpcm\components\dataView\column('select', ''))->setSize('05')->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(2)->setAlign('center'),
            (new \fpcm\components\dataView\column('username', 'GLOBAL_USERNAME'))->setSize(3),
            (new \fpcm\components\dataView\column('email', 'GLOBAL_EMAIL'))->setSize(3),
            (new \fpcm\components\dataView\column('registered', 'USERS_REGISTEREDTIME'))->setSize(2)->setAlign('center'),
            (new \fpcm\components\dataView\column('metadata', ''))->setAlign('center'),
        ]);

        $articleCount = $this->articleList->countArticlesByUsers();

        $descr = $this->language->translate('USERS_ROLL');
        foreach($usersInGroups AS $rollId => $users) {
            
            $title  = $descr.': '.isset($userGroups[$rollId])
                    ? $this->language->translate($userGroups[$rollId]->getRollName())
                    : 'GLOBAL_NOTFOUND';
            
            $dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('select', '', '', 'd-none d-lg-block'),
                    new \fpcm\components\dataView\rowCol('button', '', 'd-none d-lg-block'),
                    new \fpcm\components\dataView\rowCol('username', $title),
                    new \fpcm\components\dataView\rowCol('email', '', 'd-none d-lg-block'),
                    new \fpcm\components\dataView\rowCol('registered', '', 'd-none d-lg-block'),
                    new \fpcm\components\dataView\rowCol('metadata', '', 'd-none d-lg-block'),
                ],
                'fpcm-ui-dataview-rowcolpadding ui-widget-header ui-corner-all ui-helper-reset',
                true
            ));
            
            $currentUser = $this->session->getUserId();
            
            /* @var $user \fpcm\model\users\author */
            foreach ($users as $userId => $user) {

                $noRb   = $user->getId() == $currentUser ? true : false;

                $metadata = [
                    (new \fpcm\view\helper\badge('art'.$userId))->setValue(isset($articleCount[$userId]) ? $articleCount[$userId] : 0)->setText('USERS_ARTICLE_COUNT')->setIcon('book')->setClass('fpcm-ui-badge-userarticles'),
                    (new \fpcm\view\helper\icon('user-slash fa-inverse'))->setText('USERS_DISABLED')->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-' . $user->getDisabled())->setStack('square')
                ];
                
                $buttons = [
                    '<div class="fpcm-ui-controlgroup">',
                    (new \fpcm\view\helper\editButton('useredit'.$userId))->setUrlbyObject($user),
                    (new \fpcm\view\helper\linkButton('usermail'.$userId))->setUrl('mailto:'.$user->getEmail())->setIcon('envelope')->setIconOnly(true)->setText('GLOBAL_WRITEMAIL'),
                    '</div>'
                ];

                $dataView->addRow(
                    new \fpcm\components\dataView\row([
                        new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\radiobutton('userids', 'userids'.$userId))->setValue($userId)->setReadonly($noRb), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                        new \fpcm\components\dataView\rowCol('button', implode('', $buttons), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                        new \fpcm\components\dataView\rowCol('username', new \fpcm\view\helper\escape($user->getDisplayname()) ),
                        new \fpcm\components\dataView\rowCol('email', new \fpcm\view\helper\escape($user->getEmail())),
                        new \fpcm\components\dataView\rowCol('registered', new \fpcm\view\helper\dateText($user->getRegistertime())),
                        new \fpcm\components\dataView\rowCol('metadata', implode('', $metadata), 'fpcm-ui-metabox fpcm-ui-dataview-align-center', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    ]
                ));

            }
            
        }

        $this->view->addDataView($dataView);
        return true;
    }

    /**
     * Benutzer-Dataview erzeugen
     * @return boolean
     */
    private function createRollsView()
    {
        $rolls = $this->rollList->getUserRollsTranslated();
        
        $dataView = new \fpcm\components\dataView\dataView('rollslist');
        
        $dataView->addColumns([
            (new \fpcm\components\dataView\column('select', ''))->setSize('05')->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(2)->setAlign('center'),
            (new \fpcm\components\dataView\column('title', 'USERS_ROLLS_NAME'))->setSize('auto'),
        ]);

        foreach($rolls AS $descr => $rollId) {

            $readonly = ($rollId <= 3 ? true : false);
            
            $buttons = [
                '<div class="fpcm-ui-controlgroup">',
                (new \fpcm\view\helper\editButton('rollEditBtn'.$rollId))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/editroll', [
                    'id' => $rollId
                ]))->setReadonly($readonly),
                (new \fpcm\view\helper\linkButton('rollPermBtn'.$rollId))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/permissions', [
                    'id' => $rollId
                ]))->setIcon('key')->setIconOnly(true)->setText('USERS_ROLLS_PERMISSIONS')->setClass('fpcm-ui-rolllist-permissionedit'),
                '</div>'
            ];

            $dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\radiobutton('rollids', 'rollids'.$rollId))->setValue($rollId)->setReadonly($readonly), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    new \fpcm\components\dataView\rowCol('button', implode('', $buttons), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    new \fpcm\components\dataView\rowCol('title', new \fpcm\view\helper\escape($descr) ),
                ]
            ));

        }

        $this->view->addDataView($dataView);
        return true;
    }

    /**
     * Benutzer deaktivieren
     * @return void
     */
    private function disableUsers()
    {
        $userId = $this->getRequestVar('userids', [\fpcm\classes\http::FILTER_CASTINT]);
        
        if ($this->userList->countActiveUsers() == 1) {
            $this->view->addErrorMessage('SAVE_FAILED_USER_DISABLE_LAST');
            return;
        }

        if ($userId == $this->session->getUserId()) {
            $this->view->addErrorMessage('SAVE_FAILED_USER_DISABLE_OWN');
            return;
        }

        $user = new \fpcm\model\users\author($userId);
        if ($user->disable()) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_USER_DISABLE');
            return;
        }

        $this->view->addErrorMessage('SAVE_FAILED_USER_DISABLE');
    }

    /**
     * Benutzer aktivieren
     * @return void
     */
    private function enableUsers()
    {
        $userId = $this->getRequestVar('userids', [\fpcm\classes\http::FILTER_CASTINT]);
        if ($userId == $this->session->getUserId()) {
            return;
        }

        $user = new \fpcm\model\users\author($userId);
        if ($user->enable()) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_USER_ENABLE');
            return;
        }

        $this->view->addErrorMessage('SAVE_FAILED_USER_ENABLE');
    }

    /**
     * Benutzer löschen
     * @return bool
     */
    private function deleteUser()
    {
        $params = $this->getRequestVar();
        
        $userId         = (int) $params['userids'];
        $articlesParams = $params['articles'];
        
        if ($this->userList->countActiveUsers() == 1) {
            $this->view->addErrorMessage('DELETE_FAILED_USERS_LAST');
            return;
        }

        if ($userId == $this->session->getUserId()) {
            $this->view->addErrorMessage('DELETE_FAILED_USERS_OWN');
            return;
        }

        $user = new \fpcm\model\users\author($userId);
        if (is_array($articlesParams) && !isset($articlesParams['action']) && !isset($articlesParams['user'])) {

            if ($user->delete()) {
                $this->view->addNoticeMessage('DELETE_SUCCESS_USERS');
            } else {
                $this->view->addErrorMessage('DELETE_FAILED_USERS');
            }
        }

        if ($articlesParams['action'] === 'move' && $userId === (int) $articlesParams['user']) {
            $this->view->addErrorMessage('DELETE_FAILED_USERSARTICLES');
            return;
        }

        if (!$user->delete()) {
            $this->view->addErrorMessage('DELETE_FAILED_USERS');
            return false;
        }

        $articleList = new \fpcm\model\articles\articlelist();
        switch ($articlesParams['action']) {
            case 'move' :
                $articleList->moveArticlesToUser($userId, (int) $articlesParams['user']);
                break;
            case 'delete' :
                $articleList->deleteArticlesByUser($userId);
                break;
        }

        $this->view->addNoticeMessage('DELETE_SUCCESS_USERS');
    }

}

?>
