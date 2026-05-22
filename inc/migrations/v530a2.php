<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.3.0-a2
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.3.0-a2
 * @see migration
 */
class v530a2 extends migration {

    protected function alterTablesAfter(): bool {

        fpcmLogSystem('Cleanup article categories...');

        $res = $this->getDB()->delete(
            table: \fpcm\classes\database::tableArticleCategories,
            where: sprintf("article_id NOT IN (SELECT id FROM %s)", $this->getDB()->getTablePrefixed(\fpcm\classes\database::tableArticles))
        );

        fpcmLogSystem('Cleanup article categories finished...');
        return $res;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.3.0-a2';
    }

}
