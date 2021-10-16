<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.0-dev
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.0-dev
 * @see migration
 */
class v500a1 extends migration {

    /**
     * Update inedit data for articles
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {
        $conf = $this->getConfig();

        if ($conf->file_img_thumb_height !== null && $conf->file_img_thumb_width !== null) {
            $this->output('Convert old thumbnail setting to \fpcm\model\system\config::file_thumb_size');

            $conf->setNewConfig([
                'file_thumb_size'   => ( $conf->file_img_thumb_height > $conf->file_img_thumb_width
                                    ? $conf->file_img_thumb_height
                                    : $conf->file_img_thumb_width )
            ]);
        }

        $res = true;
        $res = $res && $conf->update();
        $res = $res && $conf->remove('file_img_thumb_height');
        $res = $res && $conf->remove('file_img_thumb_width');
        $res = $res && $conf->remove('articles_imageedit_persistence');
        
        return $res;
    }
    
    protected function updateFileSystem(): bool
    {
        $files = [
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

        return true;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.0.0-a1';
    }

    
}