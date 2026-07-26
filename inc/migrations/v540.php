<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.4.0-a1
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.4.0-a1
 * @see migration
 */
class v540a1 extends migration {

    /**
     * Update database
     * @return bool
     */
    protected function alterTablesAfter(): bool
    {
        fpcmLogSystem('Remove smiley manager table...');
        
        $res = $this->getDB()->drop('smileys');
        
        fpcmLogSystem(printf('Remove smiley manager table: %c', $res));

        return true;
    }

    /**
     * Updateb file system
     * @return bool
     */
    protected function updateFileSystem(): bool
    {
        fpcmLogSystem('Remove smiley manager folder...');
        
        $path = \fpcm\classes\dirs::getDataDirPath('smileys');
        if (!file_exists($path)) {
            return true;
        }
        
        return \fpcm\model\files\ops::deleteRecursive($path);
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.4.0-a1';
    }

}