<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\auth;

/**
 * HTML authProvider class 
 * 
 * @package fpcm\model\auth
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class htmlLogin extends \fpcm\model\abstracts\authProvider {

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->table = \fpcm\classes\database::tableAuthors;
    }

    /**
     * Execute authentication
     * @param array $param
     * @return bool
     */
    public function authenticate(array $param)
    {
        /* @var $user \fpcm\model\users\author */
        $user = \fpcm\classes\loader::getObject('\fpcm\model\users\userList')->getUserByUsername($param['username']);
        if (!$user || !$user->exists() ) {
            $this->triggerError('User ' . $param['username'] . ' not found!');
            return false;
        }

        if ($user->getDisabled()) {
            $this->triggerError('User ' . $param['username'] . '! is disabled.');
            return \fpcm\model\users\author::AUTHOR_ERROR_DISABLED;
        }
        
        if (! ( new \fpcm\model\users\userRoll($user->getRoll()) )->exists() ) {
            $this->triggerError('User roll ' . $user->getRoll() . ' does not exists of user ' . $param['username'] . '!');
            return \fpcm\model\users\author::AUTHOR_ERROR_DISABLED;            
        }

        $success = password_verify("{$param['password']}", "{$user->getPasswd()}");
        if (!$success && !hash_equals($user->getPasswd(), md5($param['password'])) ) {
            $this->triggerError('Login failed for username ' . $param['username'] . ', wrong password given!');
            return false;            
        }

        if (!$user->getAuthtoken() || !$this->config->system_2fa_auth || (isset($param['external']) && $param['external'])) {
            return $user->getId();
        }

        include_once \fpcm\classes\loader::libGetFilePath('sonata-project'.DIRECTORY_SEPARATOR.'GoogleAuthenticator');
        if (!isset($param['authcode']) || !(new \Sonata\GoogleAuthenticator\GoogleAuthenticator())->checkCode($user->getAuthtoken(), $param['authcode'])) {
            $this->triggerError('Login failed for username ' . $param['username'] . ', invalid auth token given!');
            return false;
        }

        return $user->getId();
    }

    /**
     * Return template for active auth provider
     * @return string
     */
    public function getLoginTemplate()
    {
        return 'system/login';
    }

    /**
     * Error message
     * @param string $str
     */
    private function triggerError(string $str)
    {
        trigger_error( $str.' Request was made by ' . \fpcm\classes\loader::getObject('\fpcm\model\http\request')->getIp() );
    }


}

?>