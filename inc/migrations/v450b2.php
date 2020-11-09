<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v4.5.0-b2
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 4.5.0-b2
 * @see migration
 */
class v450b2 extends migration {
    
    /**
     * Update system configs
     * @return bool
     */
    protected function updateSystemConfig(): bool
    {
        return true;
    }

    /**
     * Returns a list of migrations which have to be executed before
     * @return array
     */
    protected function required() : array
    {
        return ['450b1'];
    }
    
}