<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\dataView;

/**
 * Data view row column component
 * 
 * @package fpcm\components\dataView
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class rowCol implements \JsonSerializable {
    
    use \fpcm\model\traits\jsonSerializeReturnObject;

    const COLTYPE_VALUE     = 1;
    const COLTYPE_ELEMENT   = 2;

    /**
     * Column name
     * @var string
     */
    protected $name     = '';

    /**
     * Column value
     * @var mixed
     */
    protected $value    = '';

    /**
     * Column class
     * @var int
     */
    protected $class    = '';

    /**
     * Column type, rowCol::COLTYPE_VALUE or rowCol::COLTYPE_ELEMENT
     * @var int
     */
    protected $type     = 0;
    
    /**
     * Konstruktor
     * @param string $name
     * @param mixed $value
     * @param string $class
     * @param string $type
     */
    public function __construct($name, $value = '', $class = '', $type = self::COLTYPE_VALUE)
    {
        $this->name  = $name;
        $this->value = (is_object($value) ? (string) $value : $value);
        $this->class = $class;
        $this->type  = (int) $type;
    }

}
