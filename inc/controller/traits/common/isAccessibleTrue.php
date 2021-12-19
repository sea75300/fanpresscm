<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * isAccessible true trait for actions without permissions
 * 
 * @package fpcm\controller\traits\common
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait isAccessibleTrue {

    /**
     * 
     * @return bool
     */
    public function isAccessible() : bool
    {
        return true;
    }

}
