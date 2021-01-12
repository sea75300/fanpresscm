<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v4.3.0-rc4
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 4.3
 * @see migration
 */
class v450rc3 extends migration {

    /**
     * Update inedit data for articles
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {
        $changes = [
            \fpcm\classes\database::tableFiles => ['alttext'],
            \fpcm\classes\database::tableRoll => ['codex'],
        ];

        $res = true;
        foreach ($changes as $tab => $fields) {

            $this->output('Convert charset for '.$this->getDB()->getTablePrefixed($tab).' to utf8mb4_general_ci...');
            
            $tabStruct = $this->getDB()->getTableStructure($tab);
            
            foreach ($fields as $fieldName) {

                $struct = $tabStruct[$fieldName] ?? false;
                if (!$struct) {
                    trigger_error('field '.$fieldName.' not found!');
                    continue;
                }

                if ($struct['charset'] === 'utf8mb4_general_ci') {
                    continue;
                }
                
                $typeStr =  $struct['length'] ? $struct['type'].'('.$struct['length'].')' : $struct['type'];
                $res = $res && $this->getDB()->alter($tab, 'CHANGE', $fieldName, "`{$fieldName}` {$typeStr} COLLATE 'utf8mb4_general_ci'");
            }

        }
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '4.5.0-rc3';
    }

    /**
     * Only run on MariaDB
     * @return array
     * @since 4.5.0-rc3
     */
    protected function onDatabase(): array
    {
        return [\fpcm\classes\database::DBTYPE_MYSQLMARIADB];
    }

    
}