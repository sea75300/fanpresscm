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
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.1.4
 */
class fileindex extends \fpcm\model\abstracts\cron {

    /**
     * Auszuführender Cron-Code
     */
    public function run()
    {
        $session = \fpcm\model\system\session::getInstance();
        $user_id = $session->exists() ? $session->getUserId() : 0;

        try {
            $imageList = new \fpcm\model\files\imagelist();
            $imageList->updateFileIndex($user_id);
            $imageList->createFilemanagerThumbs();
        } catch (\Throwable $e) {
            trigger_error($e->getMessage());
        }

        try {
            $mediaList = new \fpcm\model\files\medialist();
            $mediaList->updateFileIndex($user_id);
        } catch (\Throwable $e) {
            trigger_error($e->getMessage());
        }

        return true;
    }

}
