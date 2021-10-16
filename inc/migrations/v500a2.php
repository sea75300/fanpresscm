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
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_THEME, 'navigation.css'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_THEME, 'forms.css'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'articles/times.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'categories/editor.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'comments/commentedit.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'filemanager/searchform.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'ips/ipadd.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'logs/overview.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'modules/list.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'smileys/editor.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'templates/article_templates.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'users/useradd.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'users/useredit.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'users/userlist_dialogs.php'),
            \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'wordban/editor.php'),
        ];
        
        array_map(function ($filename) {
            
            if (!file_exists($filename)) {
                return false;
            }
            
            return unlink($filename);

        }, $files);
        
        
        \fpcm\model\files\ops::deleteRecursive(\fpcm\classes\loader::libGetFilePath('jquery-ui'));

        return true;
    }
    
}