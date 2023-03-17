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
final class row implements \JsonSerializable {
    
    use \fpcm\model\traits\jsonSerializeReturnObject;

    /**
     * Row columns
     * @var int
     */
    protected $columns      = [];

    /**
     * Row class
     * @var int
     */
    protected $class        = '';

    /**
     * Row is headline
     * @var bool
     */
    protected $isheadline   = false;

    /**
     * Row contains not found
     * @var bool
     */
    protected $isNotFound   = false;
    
    /**
     * Konstruktor
     * @param array $columns
     * @param string $class
     * @param bool $isheadline
     * @param bool $isNotFound
     */
    public function __construct(array $columns, $class = '', $isheadline = false, $isNotFound = false)
    {
        $this->columns      = $columns;
        $this->class        = $class;
        $this->isheadline   = (bool) $isheadline;
        $this->isNotFound   = (bool) $isNotFound;
    }

}
