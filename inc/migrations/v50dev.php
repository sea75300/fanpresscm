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
class v50dev extends migration {

    /**
     * Update inedit data for articles
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {
        $conf = $this->getConfig();

        $this->output('Convert old thumbnail setting to \fpcm\model\system\config::::file_thumb_size');
        
        $conf->setNewConfig([
            'file_thumb_size'   => ( $conf->file_img_thumb_height > $conf->file_img_thumb_width
                                ? $conf->file_img_thumb_height
                                : $conf->file_img_thumb_width )
        ]);

        $res = true;
        $res = $res && $conf->update();
        $res = $res && $conf->remove('file_img_thumb_height');
        $res = $res && $conf->remove('file_img_thumb_width');
        
        return $res;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '4.5.0-dev';
    }

    
}