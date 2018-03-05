<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\components\dataView;

/**
 * Data view column component
 * 
 * @package fpcm\drivers\dataView
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

    public function __construct($name, $descr)
    {
        $this->name  = $name;
        $this->descr = \fpcm\classes\loader::getObject('\fpcm\classes\language')->translate($descr);
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * 
     * @return string
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * 
     * @return string
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * 
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 
     * @param string $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * 
     * @param string $descr
     * @return $this
     */
    public function setDescr($descr)
    {
        $this->descr = \fpcm\classes\loader::getObject('\fpcm\classes\language')->translate($descr);
        return $this;
    }

    /**
     * 
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
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
