<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;


/**
 * Trait for object vars within jsonSerialize function
 * 
 * @package fpcm\model\traits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
 */
trait jsonSerializeReturnObject {


    /**
     * JSON data
     * @return array
     * @ignore
     */
    public function jsonSerialize() : array
    {
        return get_object_vars($this);
    }
}

?>