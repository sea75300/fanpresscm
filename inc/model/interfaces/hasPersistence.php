<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * Interface to store objects as persitent data
 *
 * @package fpcm\model\interfaces
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
interface hasPersistence {

    /**
     * Return data
     * @return int|string
     */
    public function getPersistentData(): int|string;

}
