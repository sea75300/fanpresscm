<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\interfaces;

/**
 * Controller base interface
 * 
 * @package fpcm\controller\interfaces\isAccessible
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
interface controller {

    /**
     * Request processing,
     * false prevent execution of @see process()
     * @return bool
     */
    public function request();

    /**
     * Access check processing,
     * false prevent execution of @see request() @see process()
     * @return bool
     */
    public function hasAccess();

    /**
     * Controller-Processing
     * @return bool
     */
    public function process();
}

?>