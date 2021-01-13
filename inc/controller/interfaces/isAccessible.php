<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\interfaces;

/**
 * New controller access permission interface
 * 
 * @package fpcm\controller\interfaces
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
 */
interface isAccessible {

    /**
     * Must return true, if controller is accessible
     * @return bool
     */
    public function isAccessible() : bool;
}

?>