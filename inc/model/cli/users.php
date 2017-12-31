<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\cli;

    /**
     * FanPress CM cli users module
     * 
     * @package fpcm\model\cli
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5.1
     */
    final class users extends \fpcm\model\abstracts\cli {

        /**
         * Modul ausführen
         * @return void
         */
        public function process() {

            if (!isset($this->funcParams[1])) {
                $this->output('Invalid params, no user id set', true);
            }
            
            $userId = (int) $this->funcParams[1];
            $user   = new \fpcm\model\users\author($userId);
            
            if (!$user->exists()) {
                $this->output('No user foudn give id '.$userId, true);
            }
            
            switch ($this->funcParams[0]) {

                case self::FPCMCLI_PARAM_PASSWD :

                    $this->output('Create new password for user '.$user->getUsername().'...');
                    $success = $user->resetPassword(true);
                    
                    if (!$success['updateOk']) {
                        $this->output('Unable to reset password! Check system logs for further details.', true);
                    }

                    $this->output('Password set to '.$success['password']);

                    break;

                case self::FPCMCLI_PARAM_ENABLE :

                    $this->output('Enable user '.$user->getUsername().'...');
                    if ($user->enable()) {
                        $this->output('User successfully enabled!');
                    } else {
                        $this->output('Failed to enable user!');
                    }
                    
                    break;

                case self::FPCMCLI_PARAM_DISBALE :

                    $this->output('Disable user '.$user->getUsername().'...');
                    if ($user->disable()) {
                        $this->output('User successfully disabled!');
                    } else {
                        $this->output('Failed to disable user!');
                    }

                    break;

                case self::FPCMCLI_PARAM_REMOVE :

                    $this->output('Delete user '.$user->getUsername().'...');
                    
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
                        $this->output('Remove articles for user '.$user->getUsername().'...');
                        $articleList = new \fpcm\model\articles\articlelist();
                        $articleList->deleteArticlesByUser($userId);
                    }
                    
                    if ($moveArticles && $newUserId) {
                        $this->output('Move articles for user '.$user->getUsername().' to user id '.$newUserId.'...');
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
        public function help() {
            $lines   = [];
            $lines[] = '> Users:';
            $lines[] = '';
            $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php users <user_id> <user_id>';
            $lines[] = '';
            $lines[] = '    Action params:';
            $lines[] = '';
            $lines[] = '      --passwd      resets the password for the selected user';
            $lines[] = '      --enable      enable the selected user';
            $lines[] = '      --disable     disable the selected user';
            $lines[] = '      --remove      delete the selected user';
            return $lines;
        }

    }
