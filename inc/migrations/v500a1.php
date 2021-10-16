<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.0.0-a1
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.0.0-a1
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

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.0.0-a1';
    }

    
}