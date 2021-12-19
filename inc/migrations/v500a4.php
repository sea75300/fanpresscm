<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.0.0-a4
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.0.0-a4
 * @see migration
 */
class v500a4 extends migration {

    /**
     * Update tables
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {
        return $this->getDB()->update(\fpcm\classes\database::tableRoll, ['is_system'], [1], 'id <= 3');
    }
    
}