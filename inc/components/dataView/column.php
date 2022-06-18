<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\dataView;

/**
 * Data view column component
 * 
 * @package fpcm\components\dataView
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class column implements \JsonSerializable {

    /**
     * Column name
     * @var string
     */
    protected $name     = '';

    /**
     * Column width/size
     * @var int
     */
    protected $size     = '';

    /**
     * Column description
     * @var string
     */
    protected $descr    = '';

    /**
     * Column align
     * @var string
     */
    protected $align    = 'left';

    /**
     * CSS class
     * @var string
     */
    protected $class    = '';

    /**
     * Konstruktor
     * @param string $name
     * @param string $descr
     * @param string $class
     */
    public function __construct($name, $descr, $class = '')
    {
        $this->name  = $name;
        $this->descr = \fpcm\classes\loader::getObject('\fpcm\classes\language')->translate($descr);
        $this->class = $class;
    }

    /**
     * Returns column name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns column size
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Returns column Description
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Returns column aligment
     * @return string
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * Set column name
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set column size
     * @param string $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Set column description
     * @param string $descr
     * @return $this
     */
    public function setDescr($descr)
    {
        $this->descr = \fpcm\classes\loader::getObject('\fpcm\classes\language')->translate($descr);
        return $this;
    }

    /**
     * Set column alignment
     * @param string $align
     * @return $this
     */
    public function setAlign($align)
    {
        $this->align = $align;
        return $this;
    }

    /**
     * 
     * @return array
     * @ignore
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
