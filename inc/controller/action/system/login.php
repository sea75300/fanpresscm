<?php

/**
 * Login controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

class login extends \fpcm\controller\abstracts\controller {

    /**
     * aktuelle Anzahl an Fehler-Logins
     * @var int
     */
    protected $currentAttempts = 0;

    /**
     * ist Login gespeerrt
     * @var bool
     */
    protected $loginLocked = false;

    /**
     * wann wurde Login gesperrt
     * @var int
     */
    protected $loginLockedDate = 0;

    /**
     * Sperrzeit
     * @var int
     */
    protected $loginLockedExpire = 600;

    /**
     * 
     * @return string
     */
    protected function getViewPath()
    {
        return \fpcm\components\components::getAuthProvider()->getLoginTemplate();
    }

    /**
     * 
     * @return boolean
     */
    public function hasAccess()
    {
        if (\fpcm\classes\baseconfig::installerEnabled() || !\fpcm\classes\baseconfig::dbConfigExists()) {
            return false;
        }

        if (!$this->maintenanceMode(false) && !$this->session->exists()) {
            return false;
        }

        if ($this->getIpLockedModul() && ($this->ipList->ipIsLocked($this->getIpLockedModul()) || $this->ipList->ipIsLocked('nologin'))) {
            return false;
        }

        return true;
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        if ($this->session->exists()) {
            return $this->redirect('system/dashboard');
        }

        if ($this->getRequestVar('nologin')) {
            $this->view->addErrorMessage('LOGIN_REQUIRED');
        }

        session_start();

        $this->loginLocked();
        $this->showLockedForm();

        $this->checkPageToken();

        $doReset = $this->buttonClicked('reset');
        $doLogin = $this->buttonClicked('login');
        
        if (!$this->checkPageToken && ($doReset || $doLogin)) {
            $this->view->addErrorMessage('CSRF_INVALID');
        }
        
        $data = $this->getRequestVar('login');
        if ($doLogin && is_array($data) && $this->checkPageToken) {

            $data = $this->events->trigger('session\loginBefore', $data);

            $session = new \fpcm\model\system\session();
            $loginRes = $session->authenticate($data);

            if ($loginRes === \fpcm\model\users\author::AUTHOR_ERROR_DISABLED) {
                $this->view = new \fpcm\view\error('LOGIN_FAILED_DISABLED',null, 'sign-in-alt');
                $this->view->render();
                exit;
            }

            if ($loginRes === true && $session->save() && $session->setCookie()) {
                session_destroy();
                $this->redirect('system/dashboard');
                return true;
            }

            $this->currentAttempts++;

            \fpcm\classes\http::setSessionVar('loginAttempts', $this->currentAttempts);
            if ($this->currentAttempts == $this->config->system_loginfailed_locked) {
                $this->loginLocked();
                $this->showLockedForm();
            }

            $this->view->addErrorMessage('LOGIN_FAILED');

        }

        $username = $this->getRequestVar('username');
        $email = $this->getRequestVar('email');
        if ($doReset && $username && $email) {

            $userList = new \fpcm\model\users\userList();
            $id = $userList->getUserIdByUsername($username);
            if (!$id) {
                $this->redirect();
                return true;
            }

            $user = new \fpcm\model\users\author($id);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) && $user->getEmail() == $email && $user->resetPassword()) {
                $this->view->addNoticeMessage('LOGIN_PASSWORD_RESET');
                return true;
            }
            
            fpcmLogSystem("Passwort reset for user id {$user->getUsername()} failed.");
            $this->view->addErrorMessage('LOGIN_PASSWORD_RESET_FAILED');
            return true;
        }

        if ($this->currentAttempts >= $this->config->system_loginfailed_locked) {
            return false;
        }

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $reset = $this->getRequestVar('reset') === null ? false : true;
        $this->view->assign('userNameField', $reset ? 'username' : 'login[username]');
        $this->view->assign('resetPasswort', $reset);
        $this->view->assign('noFullWrapper', true);

        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->addJsFiles(['login.js']);
        $this->view->setFormAction('system/login');
        $this->view->render();
    }

    /**
     * Prüft, ob Login gesperrt ist
     */
    protected function loginLocked()
    {
        if (!\fpcm\classes\http::getSessionVar('loginAttempts')) {
            \fpcm\classes\http::setSessionVar('loginAttempts', $this->currentAttempts);
        } else {
            $this->currentAttempts = \fpcm\classes\http::getSessionVar('loginAttempts');
        }

        if (\fpcm\classes\http::getSessionVar('lockedTime')) {
            $this->loginLockedDate = \fpcm\classes\http::getSessionVar('lockedTime');
        }

        if ($this->currentAttempts >= $this->config->system_loginfailed_locked) {
            $this->loginLocked = true;

            if (!$this->loginLockedDate) {
                $this->loginLockedDate = time();
                \fpcm\classes\http::setSessionVar('lockedTime', $this->loginLockedDate);
            }
        }

        if ($this->loginLocked && $this->loginLockedDate + $this->loginLockedExpire <= time()) {
            $this->loginLocked = false;
            $this->loginLockedDate = 0;
            $this->currentAttempts = 0;

            session_destroy();
        }
    }

    /**
     * 
     * @return boolean
     */
    private function showLockedForm()            
    {        
        if (!$this->loginLocked) {
            return true;
        }

        $this->view = new \fpcm\view\error(
            $this->lang->translate('LOGIN_ATTEMPTS_MAX', array(
                '{{logincount}}' => $this->currentAttempts,
                '{{lockedtime}}' => $this->loginLockedExpire / 60,
                '{{lockeddate}}' => date($this->config->system_dtmask, $this->loginLockedDate)
            )),
            null,
            'sign-in-alt'
        );

        $this->view->render();

        exit;

    }

}

?>
