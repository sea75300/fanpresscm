<?php
    /**
     * FanPress CM file index rebuild Cronjob
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.1.4
     */

    namespace fpcm\model\crons;
    
    /**
     * Cronjob file index rebuild
     * 
     * @package fpcm\model\crons
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.1.4
     */
    class fileindex extends \fpcm\model\abstracts\cron {

        /**
         * Auszuführender Cron-Code
         */
        public function run() {

            /*@var $session \fpcm\model\system\session */
            $session = \fpcm\classes\baseconfig::$fpcmSession;
            $user_id = $session->exists() ? $session->getUserId() : 0;
            
            $imageList = new \fpcm\model\files\imagelist();
            $imageList->updateFileIndex($user_id);

            return true;
        }
    }
