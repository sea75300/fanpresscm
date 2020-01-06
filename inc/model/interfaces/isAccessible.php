<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * New permission system interface for models
 * 
 * @package fpcm\controller\interfaces\isAccessible
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.4
 */
interface isAccessible {

    /**
     * Must return true, if object is accessible
     * @return bool
     */
    public function isAccessible() : bool;
}

?>