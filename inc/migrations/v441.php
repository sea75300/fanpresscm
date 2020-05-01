<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v4.4.1
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since FPCM 4.4.1
 * @see migration
 */
class v441 extends migration {

    /**
     * Update inedit data for articles
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {
        
        if ($this->getDB()->getDbtype() === \fpcm\classes\database::DBTYPE_POSTGRES) {
            fpcmLogSystem('Skip this migration on postgres...');
            return true;
        }

        $struct = $this->getDB()->getTableStructure(\fpcm\classes\database::tableArticles, 'content');       
        $this->getDB()->alter(\fpcm\classes\database::tableArticles, 'CHANGE', "`content`", "`content` {$struct['type']} COLLATE 'utf8mb4_general_ci'");
        return true;
    }

    
}