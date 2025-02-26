<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.2.3-b1
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.2.3-b1
 * @see migration
 */
class v523b1 extends migration {

    protected function updateFileSystem(): bool {

        fpcmLogSystem('Cleanup file system for outdated files...');
        
        $isPath = \fpcm\classes\dirs::getIncDirPath('controller/interfaces/isAccessible.php');
        if (file_exists($isPath) && !unlink($isPath)) {
            trigger_error(sprintf('Unable to remove outdated file %s', $isPath), E_USER_ERROR);
        }
        
        return true;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.2.3-b1';
    }

}
