<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions;

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

        array_map([$this, 'assign'], $values);
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

    /**
     * Assigns value to object
     * @param int $value
     * @param string $index
     */
    private function assign($value, $index)
    {
        $this->{$index} = $value;
    }

}
