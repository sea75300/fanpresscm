<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.2.0-rc5
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.2.0-rc5
 * @see migration
 */
class v520rc5 extends migration {

    protected function updateFileSystem(): bool {

        fpcmLogSystem('Cleanuop file system for outdated files...');
        
        $files = [
            'core/js/articlelist.js',
            'core/js/backups.js',
            'core/js/backups.min.js',
            'core/js/comments.js',
            'core/js/comments_editor.js',
            'core/js/dataview.js',
            'core/js/editor.js',
            'core/js/editor_codemirror.js',
            'core/js/editor_filemanager.js',
            'core/js/editor_tinymce.js',
            'core/js/editor_tinymce4.js',
            'core/js/editor_tinymce5.js',
            'core/js/editor_videolinks.js',
            'core/js/filemanager.js',
            'core/js/fileuploader.js',
            'core/js/help.js',
            'core/js/ipadresses.js',
            'core/js/ipadresses.min.js',
            'core/js/login.js',
            'core/js/logs.js',
            'core/js/logs.min.js',
            'core/js/moduleinstaller.js',
            'core/js/modulelist.js',
            'core/js/notifications.js',
            'core/js/profile.js',
            'core/js/script.php',
            'core/js/smileys.js',
            'core/js/smileys.min.js',
            'core/js/templates.js',
            'core/js/templates_articles.js',
            'core/js/texts.js',
            'core/js/texts.min.js',
            'core/js/ui.js',
            'core/js/ui_navigation.js',
            'core/js/useredit.js',
            'core/js/users.js',
            'core/js/editor/editor_tinymce4.js',
            'core/js/files/jqupload.js',
            'core/theme/bgmain.svg',
            'core/theme/style.php',
            'core/views/articles/buttons.php',
            'core/views/filemanager/forms/jqupload.php',
            'core/views/system/testing.php',
            'core/views/users/rollslist.php',
            'data/share/default/googleplus.png',
            'inc/classes/http.php',
            'inc/components/fileupload/jqupload.php',
            'inc/controller/ajax/files/jqupload.php',
            'inc/controller/ajax/system/cronasync.php',
            'inc/controller/ajax/system/croninterval.php',
            'inc/controller/ajax/system/dashboard.php',
            'inc/controller/traits/modules/tools.php',
            'inc/migrations/v500a1.php',
            'inc/migrations/v500a2.php',
            'inc/migrations/v500a3.php',
            'inc/migrations/v500a4.php',
            'inc/migrations/v500rc2.php',
            'inc/model/files/cacheFile.php',
            'inc/model/system/permissions.php',
            'lib/chart-js/chart.min.js',
            'lib/codemirror/AUTHORS.txt',
            'lib/jquery/jquery-3.6.0.min.js',
            'lib/jqupload',
            'lib/selectize_js',
            'lib/tinymce5/changelog.md',
            'lib/tmhoauth',
        ];


        $files = array_filter($files, fn ($f) => file_exists( \fpcm\classes\dirs::getFullDirPath('/', $f) ) );
        if (!count($files)) {
            return true;
        }

        array_map(function ($f) {

            $p = \fpcm\classes\dirs::getFullDirPath('/', $f);
            if (is_dir($p)) {
                \fpcm\model\files\ops::deleteRecursive($p);
                return true;
            }

            return unlink($p);
        }, $files);
        
        return true;

    }

}