<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.2.3-b2
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.2.3-b2
 * @see migration
 */
class v523b2 extends migration {
    
    protected function alterTablesAfter(): bool {

        fpcmLogSystem('Update path hash column...');
        
        $db = $this->getDB();
        
        $query  = $db->getDbtype() === \fpcm\classes\database::DBTYPE_POSTGRES
                ? "encode(sha256(filename::bytea), 'hex')"
                : "SHA2(filename, 256)";
        
        $res = $db->exec(sprintf(
            "UPDATE %s SET pathhash = %s WHERE pathhash = ''",
            $db->getTablePrefixed(\fpcm\classes\database::tableFiles),
            $query
        ));
        
        return $res;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.2.3-b2';
    }

}
