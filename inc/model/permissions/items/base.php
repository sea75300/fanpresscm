<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions\items;

/**
 * Base permissions object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.4
 */
class base implements \JsonSerializable {

    /**
     * Constructor
     * @param mixed $values
     */
    public function __construct($values)
    {
        if (!is_object($values)) {
            $values = json_decode($values, true);
        }

        foreach ($values as $key => $value) {
            $this->{$key} = (bool) $value;
        }

    }

    /**
     * Prepares JSON encode values
     * @return array
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize() : array
    {
        return get_object_vars($this);
    }

}
