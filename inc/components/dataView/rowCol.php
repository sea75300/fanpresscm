<?php

/**
 * FanPress CM 3.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\dataView;

/**
 * Data view row column component
 * 
 * @package fpcm\drivers\mysql
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class rowCol implements \JsonSerializable {

    /**
     * Column name
     * @var string
     */
    protected $name     = '';

    /**
     * Column value
     * @var int
     */
    protected $value    = '';

    /**
     * Column class
     * @var int
     */
    protected $class    = '';

    /**
     * 
     * @param type $name
     * @param type $value
     * @param type $class
     */
    public function __construct($name, $value = '', $class = '')
    {
        $this->name  = $name;
        $this->value = $value;
        $this->class = $class;
    }

    /**
     * 
     * @return array
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}
