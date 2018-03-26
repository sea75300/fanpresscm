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
        $this->table = \fpcm\classes\database::tableAuthors;
    }

    /**
     * 
     * @param array $param
     * @return boolean
     */
    public function authenticate(array $param)
    {
        $userList = \fpcm\classes\loader::getObject('\fpcm\model\users\userList');

        $userid = $userList->getUserIdByUsername($param['username']);
        if (!$userid) {
            trigger_error('Login failed for username ' . $param['username'] . '! User not found. Request was made by ' . \fpcm\classes\http::getIp());
            return false;
        }

        $user = new \fpcm\model\users\author($userid);
        if ($user->getDisabled()) {
            trigger_error('Login failed for username ' . $param['username'] . '! User is disabled. Request was made by ' . \fpcm\classes\http::getIp());
            return \fpcm\model\users\author::AUTHOR_ERROR_DISABLED;
        }

        if (!password_verify($param['password'], $user->getPasswd())) {
            return false;
        }

        return $userid;
    }

    /**
     * Return template for active auth provider
     * @return string
     */
    public function getLoginTemplate()
    {
        return 'login/login';
    }

}

?>