<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.3.0-b5
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.3.0-b5
 * @see migration
 */
class v530b5 extends migration {

    protected function alterTablesAfter(): bool {
        
        fpcmLogSystem('Cleanup readmore tags...');

        $db = $this->getDB();

        $tn = $db->getTablePrefixed(\fpcm\classes\database::tableArticles);

        $db->exec(sprintf(
                'UPDATE %s SET content = REPLACE(content, \'%s\', \'%s\')',
                $tn,
                '<readmore>',
                '<!-- pagebreak -->'
        ));

        $db->exec(sprintf('UPDATE %s SET content = REPLACE(content, \'%s\', \'\')', $tn, '</readmore>'));

        return true;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.3.0-b5';
    }

}