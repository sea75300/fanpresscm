<?php

/**
 * FanPress CM 4.x
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
            trigger_error('Login failed for username ' . $param['username'] . '! User not found. Request was made by ' . \fpcm\classes\http::getIp());
            return false;
        }

        if ($user->getDisabled()) {
            trigger_error('Login failed for username ' . $param['username'] . '! User is disabled. Request was made by ' . \fpcm\classes\http::getIp());
            return \fpcm\model\users\author::AUTHOR_ERROR_DISABLED;
        }
        
        if (! ( new \fpcm\model\users\userRoll($user->getRoll()) )->exists() ) {
            trigger_error('Login failed for username ' . $param['username'] . '! User roll id '.$user->getRoll().' does not exists. Request was made by ' . \fpcm\classes\http::getIp());
            return \fpcm\model\users\author::AUTHOR_ERROR_DISABLED;            
        }

        if (!password_verify("{$param['password']}", "{$user->getPasswd()}" )) {
            trigger_error('Login failed for username ' . $param['username'] . '! Invalid password given, check simple hash. Request was made by ' . \fpcm\classes\http::getIp());
            
            if (defined('FPCM_DEBUG') && FPCM_DEBUG && defined('FPCM_DEBUG_LOGIN')) {
                trigger_error('Password hash information:  ');
                trigger_error(print_r(password_get_info($user->getPasswd()), true));
            }

            trigger_error('Login failed for username ' . $param['username'] . '! Invalid password given, check simple hash. Request was made by ' . \fpcm\classes\http::getIp());
            if (!hash_equals($user->getPasswd(), md5($param['password']))) {
                trigger_error('Login failed for username ' . $param['username'] . '! Invalid password given. Request was made by ' . \fpcm\classes\http::getIp());
                return false;                
            }
        }

        if (!$user->getAuthtoken() || !$this->config->system_2fa_auth || (isset($param['external']) && $param['external'])) {
            return $user->getId();
        }

        include_once \fpcm\classes\loader::libGetFilePath('sonata-project'.DIRECTORY_SEPARATOR.'GoogleAuthenticator');
        if (!isset($param['authcode']) || !(new \Sonata\GoogleAuthenticator\GoogleAuthenticator())->checkCode($user->getAuthtoken(), $param['authcode'])) {
            trigger_error('Login failed for username ' . $param['username'] . '! Invalid auth token given. Request was made by ' . \fpcm\classes\http::getIp());
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


}

?>