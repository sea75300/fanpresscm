<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.2.1-rc1
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.2.1-rc1
 * @see migration
 */
class v521rc1 extends migration {

    protected function updateFileSystem(): bool {

        fpcmLogSystem('Cleanuop file system for outdated files...');

        $dirs_list = file( \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_CONFIG . DIRECTORY_SEPARATOR . 'dirslist_v521.txt') );
        if (!is_array($dirs_list)) {
            trigger_error('Failed to read dirslist_v521.txt', E_USER_ERROR);
            return false;
        }

        $base = \fpcm\classes\dirs::getFullDirPath(DIRECTORY_SEPARATOR);

        $dirs = array_filter($dirs_list, function ($fp) use ($base) {
            $p = str_replace('fanpress/', $base, trim($fp));
            return is_dir($p) && is_writable($p);
        });

        if (!count($dirs)) {
            trigger_error('Invalid dir count data', E_USER_ERROR);
            return false;
        }

        $files = file_get_contents( \fpcm\model\packages\update::getFilesListPath() );
        if ($files === false) {
            trigger_error('Failed to read files.txt', E_USER_ERROR);
            return false;
        }

        foreach ($dirs as $dir) {

            $dir = trim($dir);

            $expected = [];

            $regex = sprintf("/%s\/[\w\_\-\ ]*\.[\w\.]{2,}/i", addcslashes($dir, '/'));

            if ( preg_match_all($regex, $files, $expected) === false ) {
                trigger_error(sprintf('Failed to match files.txt content for %s', $regex), E_USER_ERROR);
                continue;
            }

            $lookupPath = str_replace('fanpress/', $base, $dir);

            $glob = glob( $lookupPath . '/*.*' );
            if ( !is_array($glob) ) {
                trigger_error(sprintf('Failed to check files %s', $lookupPath), E_USER_ERROR);
                continue;
            }

            $found = array_map(function ($gfp) {
                return 'fanpress/' . \fpcm\model\files\ops::removeBaseDir($gfp);
            }, $glob);

            $diff = array_diff($found,$expected[0]);

            if (!count($diff)) {
                continue;
            }
            
            $diff = array_map(function ($f) use ($base) {
                return str_replace('fanpress/', $base, $f);
            }, $diff);     

            fpcmLogSystem(sprintf(
                "Old files found in %s\n%s",
                $lookupPath,
                implode(PHP_EOL, $diff)
            ));

            array_map(function ($f) {
                
                if ( !file_exists($f) || !is_writable($f) ) {
                    return false;
                }

                return unlink($f);
            }, $diff);            

            return true;
        }

    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return parent::getNewVersion();
        //return '5.2.1-rc1';
    }

}