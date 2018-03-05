<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\dataView;

/**
 * Data view row column component
 * 
 * @package fpcm\drivers\dataView
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class row implements \JsonSerializable {

    /**
     * Column value
     * @var int
     */
    protected $columns      = [];

    /**
     * Column class
     * @var int
     */
    protected $class        = '';

    /**
     * Column class
     * @var bool
     */
    protected $isheadline   = false;

    /**
     * Konstruktor
     * @param array $columns
     * @param string $class
     */
    public function __construct(array $columns, $class = '', $isheadline = false)
    {
        $this->columns      = $columns;
        $this->class        = $class;
        $this->isheadline   = $isheadline;
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
