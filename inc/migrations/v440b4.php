<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v4.4-b3
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since FPCM 4.4
 * @see migration
 */
class v440b4 extends migration {

    /**
     * Update inedit data for articles
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {
        
        $modules = (new \fpcm\module\modules)->getInstalledDatabase();
        if (!count($modules)) {
            return true;
        }

        /* @var $module \fpcm\module\module */
        foreach ($modules as $module) {
            
            $prefix = $module->getFullPrefix();

            $res = $this->getDB()->update(
                \fpcm\classes\database::tableConfig,
                [ 'modulekey' ],
                [ $module->getKey(), $prefix.'%' ],
                'config_name '.$this->getDB()->dbLike().' ?'
            );

            if (!$res) {
                trigger_error('Failed to update module config options for "'.$prefix.'"!');
            }
            
        }

        return true;
    }

    
}