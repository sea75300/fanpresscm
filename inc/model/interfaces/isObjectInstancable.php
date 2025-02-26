<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * Interface for objects with build in cache loader
 * 
 * @package fpcm\model\interfaces
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
interface isObjectInstancable {

    /**
     * Returns object of current class, caches instance for future use
     * @return object
     */
    public static function getInstance();
    
}
