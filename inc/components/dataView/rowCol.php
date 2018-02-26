<?php

/**
 * FanPress CM 4.x
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

    const COLTYPE_VALUE     = 1;
    const COLTYPE_ELEMENT   = 2;

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
     * Column class
     * @var int
     */
    protected $type     = 0;

    /**
     * 
     * @param type $name
     * @param type $value
     * @param type $class
     */
    public function __construct($name, $value = '', $class = '', $type = self::COLTYPE_VALUE)
    {
        $this->name  = $name;
        $this->value = $value;
        $this->class = $class;
        $this->type  = (int) $type;
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
