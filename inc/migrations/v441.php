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
        fpcmLogSystem('Covnert charset for '.$this->getDB()->getTablePrefixed(\fpcm\classes\database::tableArticles).' to utf8mb4_general_ci...');

        $fieldName = 'content';
        $struct = $this->getDB()->getTableStructure(\fpcm\classes\database::tableArticles, $fieldName)[$fieldName] ?? false;        
        if (!$struct) {
            trigger_error('field '.$fieldName.' not found!');
            return false;
        }

        return $this->getDB()->alter(\fpcm\classes\database::tableArticles, 'CHANGE', $fieldName, "`{$fieldName}` {$struct['type']} COLLATE 'utf8mb4_general_ci'");
    }

    /**
     * Returns a list of migrations which have to be executed before
     * @return array
     */
    protected function required(): array
    {
        return ['440b4'];
    }

    /**
     * Returns a list of database driver names the migration should be executed to,
     * default is MySQL/ MariaDB and Postgres
     * @return array
     * @since FPCM 4.4.1
     */
    protected function onDatabase(): array
    {
        return [\fpcm\classes\database::DBTYPE_MYSQLMARIADB];
    }

    
}