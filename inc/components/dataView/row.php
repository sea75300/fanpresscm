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
final class row implements \JsonSerializable {

    /**
     * Column value
     * @var int
     */
    protected $columns  = [];

    /**
     * Column class
     * @var int
     */
    protected $class    = '';

    /**
     * Konstruktor
     * @param array $columns
     * @param string $class
     */
    public function __construct(array $columns, $class = '')
    {
        $this->columns  = $columns;
        $this->class    = $class;
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
