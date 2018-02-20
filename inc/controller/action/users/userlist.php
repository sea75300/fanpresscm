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

    public function getViewPath()
    {
        return 'users/userlist';
    }

    protected function getPermissions()
    {
        return ['system' => 'users'];
    }

    protected function getHelpLink()
    {
        return 'hl_options';
    }

    public function request()
    {
        $this->userList     = new \fpcm\model\users\userList();
        $this->rollList     = new \fpcm\model\users\userRollList();
        $this->articleList  = new \fpcm\model\articles\articlelist();

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
                $this->buttonClicked('deleteActive') ||
                $this->buttonClicked('deleteDisabled') ||
                $this->buttonClicked('deleteRoll') ) && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        if ($this->buttonClicked('disableUser') && !is_null($this->getRequestVar('useridsa'))) {
            $this->disableUsers($this->getRequestVar('useridsa', [9]));
        }

        if ($this->buttonClicked('enableUser') && !is_null($this->getRequestVar('useridsd'))) {
            $this->enableUsers($this->getRequestVar('useridsd', [9]));
        }

        if ($this->buttonClicked('deleteActive') && !is_null($this->getRequestVar('useridsa'))) {
            $params = $this->getRequestVar();
            $this->deleteUsers((int) $params['useridsa'], true, $params['articles']);
        }

        if ($this->buttonClicked('deleteDisabled') && !is_null($this->getRequestVar('useridsd'))) {
            $params = $this->getRequestVar();
            $this->deleteUsers((int) $params['useridsd'], false, $params['articles']);
        }

        if ($this->buttonClicked('deleteRoll') && !is_null($this->getRequestVar('rollids'))) {
            $roll = new \fpcm\model\users\userRoll($this->getRequestVar('rollids'));

            if ($roll->delete()) {
                $this->view->addNoticeMessage('DELETE_SUCCESS_ROLL');
            } else {
                $this->view->addErrorMessage('DELETE_FAILED_ROLL');
            }
        }

        return true;
    }

    public function process()
    {
        $translatedRolls = $this->rollList->getUserRollsTranslated();

        $this->view->assign('currentUser', $this->session->getUserId());
        $this->view->assign('usersActive', $this->userList->getUsersActive(true));
        $this->view->assign('usersListSelect', $this->userList->getUsersNameList());
        $this->view->assign('usersDisabled', $this->userList->getUsersDisabled(true));
        $this->view->assign('usersRollList', $translatedRolls);
        $this->view->assign('usersRolls', array_flip($translatedRolls));
        $this->view->assign('articleCounts', $this->articleList->countArticlesByUsers());
        $this->view->assign('rollPermissions', $this->permissions->check(['system' => 'rolls']));
        $this->view->addJsFiles(['users.js']);
        $this->view->addJsLangVars(['USERS_ARTICLES_SELECT', 'HL_OPTIONS_PERMISSIONS']);
        $this->view->setFormAction('users/list');

        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('addUser'))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/add'))->setText('USERS_ADD')->setClass('fpcm-loader fpcm-ui-maintoolbarbuttons-tab1')->setIcon('user-plus'),
            (new \fpcm\view\helper\submitButton('disableUser'))->setText('GLOBAL_DISABLE')->setClass('fpcm-ui-maintoolbarbuttons-tab1 fpcm-ui-button-confirm')->setIcon('toggle-off'),
            (new \fpcm\view\helper\deleteButton('deleteActive'))->setClass('fpcm-ui-maintoolbarbuttons-tab1'),            
            
            (new \fpcm\view\helper\submitButton('enableUser'))->setText('GLOBAL_ENABLE')->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden fpcm-ui-button-confirm')->setIcon('toggle-on'),
            (new \fpcm\view\helper\deleteButton('deleteDisabled'))->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden'),
            
            (new \fpcm\view\helper\linkButton('addUser'))->setUrl(\fpcm\classes\tools::getFullControllerLink('users/addroll'))->setText('USERS_ROLL_ADD')->setClass('fpcm-ui-maintoolbarbuttons-tab3 fpcm-ui-hidden')->setIcon('users'),
            (new \fpcm\view\helper\deleteButton('deleteRoll'))->setClass('fpcm-ui-maintoolbarbuttons-tab3 fpcm-ui-hidden fpcm-ui-button-confirm'),
            
        ]);
        
        $this->view->render();
    }

    /**
     * Benutzer deaktivieren
     * @param array $userId
     * @return void
     */
    private function disableUsers($userId)
    {
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
     * @param array $userId
     * @return void
     */
    private function enableUsers($userId)
    {
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
     * @param int $userId
     * @param bool $check
     * @param array $articlesParams
     * @return bool
     */
    private function deleteUsers($userId, $check = true, $articlesParams = false)
    {

        if ($check && $this->userList->countActiveUsers() == 1) {
            $this->view->addErrorMessage('DELETE_FAILED_USERS_LAST');
            return;
        }

        if ($check && $userId == $this->session->getUserId()) {
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
