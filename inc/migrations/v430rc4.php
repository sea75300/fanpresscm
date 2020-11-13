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
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 4.3
 * @see migration
 */
class v430rc4 extends migration {

    /**
     * Update inedit data for articles
     * @return bool
     */
    protected function alterTablesAfter() : bool
    {
        return $this->getDB()->update(\fpcm\classes\database::tableArticles, ['inedit'], ['']);
    }

    /**
     * Returns new version, e. g. from version.txt
     * @return string
     */
    protected function getNewVersion() : string
    {
        return '4.3.0-rc4';
    }

    
}