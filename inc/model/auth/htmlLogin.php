<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
/**
 * Session object
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\auth;

/**
 * Session Objekt
 * 
 * @package fpcm\model\auth
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
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
     * 
     * @param array $param
     * @return boolean
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

        if (!password_verify($param['password'], $user->getPasswd())) {
            trigger_error('Login failed for username ' . $param['username'] . '! Invalid password given, check simple hash. Request was made by ' . \fpcm\classes\http::getIp());
            if (!md5($param['password']) !== $user->getPasswd()) {
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