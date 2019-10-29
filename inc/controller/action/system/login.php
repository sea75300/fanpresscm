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
     * @var \fpcm\model\abstracts\spamCaptcha
     */
    protected $captcha;

    /**
     * Two factor authentication
     * @var bool
     */
    protected $twoFaAuth = false;

    /**
     * Reset password flag
     * @var bool
     */
    protected $reset = false;

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return \fpcm\components\components::getAuthProvider()->getLoginTemplate();
    }

    /**
     * 
     * @return bool
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
     * 
     * @return bool
     */
    protected function initActionObjects()
    {
        $this->captcha = \fpcm\components\components::getChatptchaProvider();
        return true;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        if ($this->session->exists()) {
            $this->view = null;
            return $this->redirect('system/dashboard');
        }

        if ($this->getRequestVar('nologin')) {
            $this->view->addErrorMessage('LOGIN_REQUIRED');
        }
        
        $this->reset = $this->getRequestVar('reset') !== null || $this->getRequestVar('username') !== null ? true : false;

        session_start();
        
        $this->loginLocked();
        $this->showLockedForm();
        
        $this->resetPassword();
        $this->processLogin();

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->view->assign('userNameField', $this->reset ? 'username' : 'login[username]');
        $this->view->assign('resetPasswort', $this->reset);
        $this->view->assign('twoFactorAuth', $this->twoFaAuth);
        $this->view->assign('captcha', $this->captcha);
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->setFormAction('system/login');
        $this->view->render();
    }

    /**
     * 
     * @return bool
     */
    private function processLogin() : bool
    {
        if (!$this->buttonClicked('login')) {
            return false;
        }

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }        

        $data = $this->getRequestVar('login');
        if (!is_array($data) || !count($data)) {
            $this->view->addErrorMessage('LOGIN_FAILED');
            return false;
        }
        
        if ($this->showTwoFactorForm($data)) {
            return true;
        }

        $data = $this->events->trigger('session\loginBefore', $data);

        $session = new \fpcm\model\system\session();
        $loginRes = $session->authenticate($data);

        if ($loginRes === \fpcm\model\users\author::AUTHOR_ERROR_DISABLED) {
            $this->view = new \fpcm\view\error('LOGIN_FAILED_DISABLED', null, 'sign-in-alt');
            $this->view->render();
            exit;
        }

        if ($loginRes === true && $session->save() && $session->setCookie()) {
            $this->redirect('system/dashboard');
            return true;
        }

        $this->currentAttempts++;

        \fpcm\classes\http::setSessionVar('loginAttempts', $this->currentAttempts);
        if ($this->currentAttempts >= $this->config->system_loginfailed_locked) {
            $this->loginLocked();
            $this->showLockedForm();
            return true;
        }

        $this->view->addErrorMessage('LOGIN_FAILED');
        return true;
    }

    /**
     * 
     * @param array $data
     * @return bool
     */
    private function showTwoFactorForm(array $data) : bool
    {
        if ($this->reset || !$this->config->system_2fa_auth || isset($data['authcode'])) {
            return false;
        }
            
        $user = (new \fpcm\model\users\userList())->getUserByUsername($data['username']);
        if (!$user->getAuthtoken()) {
            return false;
        }

        $this->twoFaAuth = true;
        $this->view->assign('username', $data['username']);
        $this->view->assign('password', $data['password']);
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function resetPassword() : bool
    {
        if (!$this->buttonClicked('reset')) {
            return false;
        }

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }
        
        $username = $this->getRequestVar('username');
        $email = $this->getRequestVar('email');
        if (!trim($username) || !trim($email) || !$this->captcha->checkAnswer()) {
            fpcmLogSystem("Passwort reset for user id {$user->getUsername()} failed, empty data or captcha failed.");
            $this->view->addErrorMessage('LOGIN_PASSWORD_RESET_FAILED');
            return false;
        }

        /* @var $user \fpcm\model\users\author */
        $user = \fpcm\classes\loader::getObject('\fpcm\model\users\userList')->getUserByUsername($username);
        if (!$user || !$user->exists()) {
            fpcmLogSystem("Passwort reset for user id {$user->getUsername()} failed, user not found.");
            $this->redirect();
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $user->getEmail() !== $email) {
            fpcmLogSystem("Passwort reset for user id {$user->getUsername()} failed, invalid e-mail address.");
            $this->view->addErrorMessage('LOGIN_PASSWORD_RESET_FAILED');
            return false;
        }

        if (!$user->resetPassword()) {
            fpcmLogSystem("Passwort reset for user id {$user->getUsername()} failed.");
            $this->view->addErrorMessage('LOGIN_PASSWORD_RESET_FAILED');
            return false;
        }

        $this->view->addNoticeMessage('LOGIN_PASSWORD_RESET');
        return true;
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
        }
    }

    /**
     * 
     * @return bool
     */
    private function showLockedForm()
    {
        if (!$this->loginLocked) {
            return true;
        }

        $this->view = new \fpcm\view\error(
                $this->language->translate('LOGIN_ATTEMPTS_MAX', array(
                    '{{logincount}}' => $this->currentAttempts,
                    '{{lockedtime}}' => $this->loginLockedExpire / 60,
                    '{{lockeddate}}' => date($this->config->system_dtmask, $this->loginLockedDate)
                )), null, 'sign-in-alt'
        );

        $this->view->render();

        exit;
    }

}

?>
