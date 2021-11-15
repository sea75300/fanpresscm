<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.0.0-a3
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.0.0-a3
 * @see migration
 */
class v500a3 extends migration {

    /**
     * Update system config options
     * @return bool
     */
    protected function updateSystemConfig() : bool
    {
        if ($this->getConfig()->system_session_length === null) {
            return true;
        }

        return $this->getConfig()->remove('system_session_length');
    }
    
}