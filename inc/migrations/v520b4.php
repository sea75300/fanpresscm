<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.2.0-b4
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.2.0-b4
 * @see migration
 */
class v520b4 extends migration {

    protected function alterTablesAfter(): bool {

        fpcmLogSql('Check for cronjob "unpinArticles"...');
        
        $count = $this->getDB()->count(
            \fpcm\classes\database::tableCronjobs,
            'id',
            'cjname = ?',
            ['unpinArticles']
        );
        
        if ($count) {
            return true;
        }
        
        fpcmLogSql('Add cronjob "unpinArticles" with migration...');
        
        $id = $this->getDB()->insert(\fpcm\classes\database::tableCronjobs, [
            'cjname' => 'unpinArticles',
            'lastexec' => 0,
            'execinterval' => 86400,
            'modulekey' => '',
            'isrunning' => 0            
        ]);

        fpcmLogSql('Add cronjob "unpinArticles" with migration: ' . $id);

        return true;
    }

}