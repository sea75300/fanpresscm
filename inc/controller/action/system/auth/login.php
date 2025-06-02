<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system\auth;

/**
 * Login controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class login extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\requestFunctions {

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

        if ($this->request->fromGET('nologin')) {
            $this->view->addErrorMessage('LOGIN_REQUIRED');
        }
        
        $this->reset = $this->request->fromGET('reset') !== null || $this->request->fromPOST('username') !== null ? true : false;

        session_start();
        
        $this->loginLocked();
        $this->showLockedForm();

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
    protected function onLogin()
    {
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }        

        $data = $this->request->fromPOST('login');
        if (!is_array($data) || !count($data)) {
            $this->view->addErrorMessage('LOGIN_FAILED');
            return false;
        }
        
        if ($this->showTwoFactorForm($data)) {
            return true;
        }

        $ev = $this->events->trigger('session\loginBefore', $data);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event session\loginBefore failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return $data;
        }        
        
        $data = $ev->getData();

        if (!empty($data['formData'])) {
            $tmp = json_decode((new \fpcm\classes\crypt)->decrypt(base64_decode($data['formData'])), true);
            $data['username'] = $tmp['username'];
            $data['password'] = $tmp['password'];
            unset($data['formData']);
        }
        
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
        
        $this->setAttemptsCount();
        if ($this->currentAttempts >= $this->config->system_loginfailed_locked) {
            $this->loginLocked();
            $this->showLockedForm();
            return true;
        }

        if ($loginRes === \fpcm\model\abstracts\authProvider::AUTH_2FA_ERROR) {
            $this->view->addErrorMessage('LOGIN_FAILED_CODE');
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
        $this->view->assign('formData', base64_encode((new \fpcm\classes\crypt)->encrypt($data)) );
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function onReset()
    {
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }
        
        $username = $this->request->fromPOST('username');
        $email = $this->request->fromPOST('email');
        if (!trim($username) || !trim($email) || !$this->captcha->checkAnswer()) {
            fpcmLogSystem("Passwort reset for user id {$username} failed, empty data or captcha failed.");
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
        if (!$this->getAttemptsCount()) {
            $this->setAttemptsCount();
        }

        $this->getLockTime();

        if ($this->currentAttempts >= $this->config->system_loginfailed_locked) {
            $this->loginLocked = true;

            if (!$this->loginLockedDate) {
                $this->setLockTime();
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

    /**
     * 
     * @return bool
     */
    protected function initPermissionObject(): bool
    {
        return true;
    }

    /**
     * 
     * @return bool|int
     */
    private function getAttemptsCount()
    {
        $val = $_SESSION['loginAttempts'] ?? false;
        if ($val === false) {
            return $val;
        }

        $this->currentAttempts = $val;
        return $this->currentAttempts;
    }

    /**
     * 
     * @return bool
     */
    private function getLockTime()
    {
        $val = $_SESSION['lockedTime'] ?? false;
        if ($val === false) {
            return false;
        }

        $this->loginLockedDate = $val;
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function setAttemptsCount()
    {
        $_SESSION['loginAttempts'] = $this->currentAttempts;
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function setLockTime()
    {
        $this->loginLockedDate = time();
        $_SESSION['lockedTime'] = $this->loginLockedDate;
        return true;
    }

}
