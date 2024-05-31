<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.2.0-rc3
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.2.0-rc3
 * @see migration
 */
class v520rc3 extends migration {

    protected function updateFileSystem(): bool {

        $files = [
            'action/users/useradd',
            'action/users/useredit',
            'action/users/rolladd',
            'action/users/rolledit',
            'action/articles/articleadd',
            'action/articles/articleedit',
            'action/articles/articlelistall',
            'action/articles/articlelistactive',
            'action/articles/articlelistarchive',
            'action/articles/articlelisttrash',
            'action/system/login',
            'action/system/logout',
            'action/system/options',
            'action/system/profile',
            'action/system/logs',
            'action/system/backups',
            'action/system/crons',
            'action/system/langedit',
            'action/system/import',
            'action/system/twitterAssistant',
            'action/categories/categoryadd',
            'action/categories/categoryedit',
            'action/categories/categorylist',
            'action/wordban/itemadd',
            'action/wordban/itemedit',
            'action/wordban/itemlist',
            'action/smileys/smileylist',
            'action/smileys/smileyadd',
            'action/smileys/smileyedit',
            'action/ips/iplist',
            'action/ips/ipadd',
            'action/ips/ipedit',
            'action/comments/commentedit',
            'action/comments/commentlist',
            'action/comments/commenttrash',
            'action/packagemgr/sysupdate',
            'action/packagemgr/moduleInstaller',
            'action/packagemgr/moduleUpdater',
            'action/system/testing'
        ];


        $files = array_filter($files, fn ($f) => file_exists( \fpcm\classes\dirs::getIncDirPath('controller/'.$f.'.php') ) );
        if (!count($files)) {
            return true;
        }

        array_map(function ($f) {
            unlink( \fpcm\classes\dirs::getIncDirPath('controller/'.$f.'.php') );
        }, $files);

        return true;
    }

}