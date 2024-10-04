<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * New permission system interface for models
 * 
 * @package fpcm\model\interfaces
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
 */
interface isAccessible {

    /**
     * Must return true, if object is accessible
     * @return bool
     */
    public function isAccessible() : bool;

}
