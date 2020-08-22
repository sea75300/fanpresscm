<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v4.4.2
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 4.4.2
 * @see migration
 */
class v442 extends migration {

    /**
     * Update inedit data for articles
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {

        $changes = [
            \fpcm\classes\database::tableArticles => ['title', 'content'],
            \fpcm\classes\database::tableComments => ['text'],
        ];

        $res = true;
        foreach ($changes as $tab => $fields) {

            $this->output('Convert charset for '.$this->getDB()->getTablePrefixed($tab).' to utf8mb4_general_ci...');
            
            $tabStruct = $this->getDB()->getTableStructure($tab);
            
            foreach ($fields as $fieldName) {

                $struct = $tabStruct[$fieldName] ?? false;
                if (!$struct) {
                    trigger_error('field '.$fieldName.' not found!');
                    return false;
                }

                if ($struct['charset'] === 'utf8mb4_general_ci') {
                    continue;
                }
                
                $typeStr =  $struct['length'] ? $struct['type'].'('.$struct['length'].')' : $struct['type'];
                $res = $res && $this->getDB()->alter($tab, 'CHANGE', $fieldName, "`{$fieldName}` {$typeStr} COLLATE 'utf8mb4_general_ci'");
            }

        }

        return $res;
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
     * @since 4.4.1
     */
    protected function onDatabase(): array
    {
        return [\fpcm\classes\database::DBTYPE_MYSQLMARIADB];
    }

    
}