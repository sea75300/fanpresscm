<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.3.0-b3
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.3.0-b3
 * @see migration
 */
class v530b3 extends migration {

    protected function alterTablesAfter(): bool {

        if (!$this->defaultAlterTables()) {
            fpcmLogSql('Updates tables failed!');
            return false;
        }

        fpcmLogSql('Check for cronjob "reminderMails"...');

        $count = $this->getDB()->count(
            \fpcm\classes\database::tableCronjobs,
            'id',
            'cjname = ?',
            ['reminderMails']
        );

        if ($count) {
            return true;
        }

        fpcmLogSql('Add cronjob "reminderMails" with migration...');

        $id = $this->getDB()->insert(\fpcm\classes\database::tableCronjobs, [
            'cjname' => 'reminderMails',
            'lastexec' => 0,
            'execinterval' => 21600,
            'modulekey' => '',
            'isrunning' => 0
        ]);

        fpcmLogSql('Add cronjob "reminderMails" with migration: ' . $id);

        return true;
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '5.3.0-b3';
    }

}