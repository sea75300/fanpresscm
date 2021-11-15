<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.0.0-a2
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.0.0-a2
 * @see migration
 */
class v500a2 extends migration {

    /**
     * Delete removed files in FPCM 4.5.4 and older
     * @return bool
     */
    protected function updateFileSystem(): bool
    {
        $files = [
            \fpcm\classes\loader::libGetFilePath('bootstrap/bootstrap-grid.min.css'),
            \fpcm\classes\loader::libGetFilePath('bootstrap/bootstrap-grid.min.css.map'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'articles/revisions.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'editor/editor_tinymce5.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'modules/info.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'ui/navigation.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'ui/navigation.min.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'categories.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'crons.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'permissions.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'options.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, 'langedit.js'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_THEME, 'navigation.css'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_THEME, 'dataview.css'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_THEME, 'forms.css'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'articles/times.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'comments/commentedit.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'filemanager/searchform.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'logs/overview.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'modules/list.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'templates/article_templates.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'users/useradd.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'users/useredit.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'users/userlist_dialogs.php'),
        ];
        
        array_map(function ($filename) {
            
            if (!file_exists($filename)) {
                return false;
            }
            
            return unlink($filename);

        }, $files);
        
        
        $dirs = [
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'categories'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'ips'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'smileys'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'wordban'),
            \fpcm\classes\loader::libGetFilePath('jquery-ui')
        ];
        
        array_map(function ($filename) {

            if (!file_exists($filename)) {
                return false;
            }
            
            return \fpcm\model\files\ops::deleteRecursive($filename);

        }, $dirs);

        return true;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.0.0-a2';
    }
    
}