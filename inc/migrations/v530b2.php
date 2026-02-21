<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Migration to v5.3.0-b2
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.3.0-b2
 * @see migration
 */
class v530b2 extends migration {

    /**
     * Alter table data
     * @return bool
     */
    protected function alterTablesAfter(): bool
    {
        return $this->getDB()->delete(\fpcm\classes\database::tableReminders, 'obj_name = ?', ['fpcm\model\files\image']);
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
