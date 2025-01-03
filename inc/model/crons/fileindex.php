<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\crons;

/**
 * Cronjob file index rebuild
 * 
 * @package fpcm\model\crons
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.1.4
 */
class fileindex extends \fpcm\model\abstracts\cron {

    /**
     * Auszuführender Cron-Code
     */
    public function run()
    {

        /* @var $session \fpcm\model\system\session */
        $session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        $user_id = $session->exists() ? $session->getUserId() : 0;

        $imageList = new \fpcm\model\files\imagelist();
        $imageList->updateFileIndex($user_id);
        $imageList->createFilemanagerThumbs();

        return true;
    }

}
