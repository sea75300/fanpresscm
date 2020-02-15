<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\interfaces;

/**
 * Controller base interface
 * 
 * @package fpcm\controller\interfaces\isAccessible
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
interface controller {

    /**
     * Request processing
     * @return bool, false prevent execution of @see process()
     */
    public function request();

    /**
     * Access check processing
     * @return bool, false prevent execution of @see request() @see process()
     */
    public function hasAccess();

    /**
     * Controller-Processing
     * @return bool
     */
    public function process();
}

?>