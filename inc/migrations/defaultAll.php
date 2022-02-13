<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\migrations;

/**
 * Default migration
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\migrations
 * @since 5.0.0-b1
 * @see migration
 */
final class defaultAll extends migration {

    /**
     * Forced executiton of this migration
     * @return string
     */
    protected function getNewVersion(): string
    {
        return '99999999999999999.9999';
    }

}