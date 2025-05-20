<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.2.5-a1
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.2.5-a1
 * @see migration
 */
class v525a1 extends migration {

    protected function updateFileSystem(): bool {

        fpcmLogSystem('Cleanup file system for outdated files...');
        
        try {
            $fscheck = new \fpcm\model\files\filesIndexCheck();
            $fscheck->prepareIndex();
            $fscheck->checkFiles();
            $fscheck->cleanup();
        } catch (\Exception $e) {
            trigger_error($e->getMessage());
            return false;
        }
        
        return true;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.2.5-a1';
    }

}
