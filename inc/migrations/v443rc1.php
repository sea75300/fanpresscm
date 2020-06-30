<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v4.4.3-rc1
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since FPCM 4.4.3-rc1
 * @see migration
 */
class v443rc1 extends migration {

    /**
     * Update inedit data for articles
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {
        $name = 'session_userdata';

        if (count($this->getDB()->getTableStructure($name))) {
            return true;
        }

        $query  = 'SELECT sess.id as sess_id, usr.id as usr_id, sess.sessionid as sess_sessionid, sess.userid as sess_userid, sess.login as sess_login, sess.logout as sess_logout, sess.lastaction as sess_lastaction, sess.ip as sess_ip, sess.external as sess_external, sess.useragent as sess_useragent, usr.displayname as usr_displayname, usr.email as usr_email, usr.registertime as usr_registertime, usr.username as usr_username, usr.passwd as usr_passwd, usr.roll as usr_roll, usr.disabled as usr_disabled, usr.usrmeta as usr_usrmeta, usr.usrinfo as usr_usrinfo, usr.authtoken as usr_authtoken, usr.changetime as usr_changetime, usr.changeuser as usr_changeuser ';
        $query .= 'FROM '.$this->getDB()->getTablePrefixed(\fpcm\classes\database::tableAuthors).' usr ';
        $query .= 'JOIN '.$this->getDB()->getTablePrefixed(\fpcm\classes\database::tableSessions).' sess ON (sess.userid = usr.id)';
        return $this->getDB()->createView($name, $query);
    }

    /**
     * Returns a list of migrations which have to be executed before
     * @return array
     */
    protected function required(): array
    {
        return ['442'];
    }

    
}