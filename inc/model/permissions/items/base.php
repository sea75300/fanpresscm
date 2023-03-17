<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions\items;

/**
 * Base permissions object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
 */
abstract class base implements \JsonSerializable {

    /**
     * Constructor
     * @param mixed $values
     */
    public function __construct($values)
    {
        if ($values === null) {
            return;
        }
        
        if (!is_array($values)) {
            $values = json_decode($values, true);
        }

        foreach ($values as $key => $value) {
            $this->{$key} = (int) $value;
        }

    }

    /**
     * Prepares JSON encode values
     * @return array
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize() : mixed
    {
        return $this->getObjectVars();
    }

    /**
     * Retruns values from object
     * @return array
     */
    final protected function getObjectVars() : array
    {
        return array_map('intval', get_object_vars($this));
    }

    /**
     * Returns an array for a default permission set
     * @return array
     */
    public function getDefault() : array
    {
        return $this->getObjectVars();
    }

    /**
     * Returns an array for an empty permission set
     * @return array
     */
    final public function getAllFalse() : array
    {
        return array_map( function($value) {
            return 0;
        }, $this->getObjectVars());
    }

}
