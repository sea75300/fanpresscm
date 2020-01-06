<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli users module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5.1
 */
final class users extends \fpcm\model\abstracts\cli {

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {
        if ($this->funcParams[0] === self::PARAM_LISTROLLS) {
            $this->output('Fetch user rolls from system...');

            /* @var $roll \fpcm\model\users\userRoll */
            foreach ((new \fpcm\model\users\userRollList())->getUserRolls() as $roll) {
                $this->output($roll->getRollNameTranslated(). ' (ID: '.$roll->getId().')');
            }

            return true;
        }

        if ($this->funcParams[0] === self::PARAM_LIST) {
            $this->output('Fetch users from system...');

            /* @var $user \fpcm\model\users\author */
            foreach ((new \fpcm\model\users\userList())->getUsersAll() as $user) {
                $this->output('ID           : '.$user->getId());
                $this->output('Username     : '.$user->getUsername());
                $this->output('Display name : '.$user->getDisplayname());
                $this->output('Roll id      : '.$user->getRoll());
                $this->output('Is active    : '.$this->boolText($user->getDisabled()));
                $this->output('Created on   : '. date('Y-m-d H:i:s', $user->getRegistertime()) );
                $this->output('----------'.PHP_EOL);
            }

            return true;
        }
        
        
        if (!isset($this->funcParams[1])) {
            $this->output('Invalid params, no user id set', true);
        }

        $userId = (int) $this->funcParams[1];
        $user = new \fpcm\model\users\author($userId);

        if (!$user->exists()) {
            $this->output('No user found for given id ' . $userId, true);
        }

        switch ($this->funcParams[0]) {

            case self::PARAM_PASSWD :

                $this->output('Create new password for user ' . $user->getUsername() . '...');
                $success = $user->resetPassword(true);

                if (!$success['updateOk']) {
                    $this->output('Unable to reset password! Check system logs for further details.', true);
                }

                $this->output('Password set to ' . $success['password']);

                break;

            case self::PARAM_ENABLE :

                $this->output('Enable user ' . $user->getUsername() . '...');
                if ($user->enable()) {
                    $this->output('User successfuly enabled!');
                } else {
                    $this->output('Failed to enable user!');
                }

                break;

            case self::PARAM_DISABLE :

                $this->output('Disable user ' . $user->getUsername() . '...');
                if ($user->disable()) {
                    $this->output('User successfuly disabled!');
                } else {
                    $this->output('Failed to disable user!');
                }

                break;

            case self::PARAM_CHGROLL :

                $gid = (int) $this->input('Enter new roll id for ' . $user->getUsername() . ':');
                $roll = new \fpcm\model\users\userRoll($gid);
                if (!$roll->exists()) {
                    $this->output('Failed to change user roll to '.$gid.', roll does not exists.', true);
                }
                
                $user->setRoll($gid);
                if ($user->update()) {
                    $this->output('Change of user roll to '.$roll->getRollNameTranslated().' was successful!');
                } else {
                    $this->output('Failed to change user roll to '.$roll->getRollNameTranslated(), true);
                }

                break;

            case self::PARAM_REMOVE :

                $this->output('Delete user ' . $user->getUsername() . '...');

                $delArticles = $this->input('Delete articles? (y/n)') === 'y' ? true : false;
                $moveArticles = false;

                if (!$delArticles && $this->input('Move articles to another user? (y/n)') === 'y') {
                    $moveArticles = true;
                    $newUserId = (int) $this->input('User id articles should be moved to?');
                }

                if (!$user->delete()) {
                    $this->output('Failed to delete user!', true);
                }

                if ($delArticles) {
                    $this->output('Remove articles for user ' . $user->getUsername() . '...');
                    $articleList = new \fpcm\model\articles\articlelist();
                    $articleList->deleteArticlesByUser($userId);
                }

                if ($moveArticles && $newUserId) {
                    $this->output('Move articles for user ' . $user->getUsername() . ' to user id ' . $newUserId . '...');
                    $articleList = new \fpcm\model\articles\articlelist();
                    $articleList->moveArticlesToUser($userId, $newUserId);
                }

                $this->output('User deleted!');

                break;

            default:
                break;
        }

        return true;
    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {
        $lines = [];
        $lines[] = '> Users:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php users <action> <user_id>';
        $lines[] = '';
        $lines[] = '    Action params:';
        $lines[] = '';
        $lines[] = '      --passwd      resets the password for the selected user';
        $lines[] = '      --enable      enable the selected user';
        $lines[] = '      --disable     disable the selected user';
        $lines[] = '      --remove      delete the selected user';
        $lines[] = '      --chgroll     change user roll of selected user';
        $lines[] = '      --remove      delete the selected user';
        $lines[] = '';
        $lines[] = '      --list        list all users in system, includes disabled users, no user_id param required';
        $lines[] = '      --listrolls   list all user-rolls in system, no user_id param required';
        return $lines;
    }

}
