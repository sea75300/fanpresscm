<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Text input view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class numberInput extends input {

    private $max = null;

    private $min = null;


    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->type = 'number';
    }

    /**
     * Set input maximum
     * @param int|float $max
     * @return $this
     */
    public function setMax($max)
    {
        $this->max = $max;
        return $this;
    }

    /**
     * Set input minimum
     * @param int|float $min
     * @return $this
     */
    public function setMin($min)
    {
        $this->min = $min;
        return $this;
    }

    /**
     * Append attributes to input element
     * @return string
     * @since 5.0-dev
     * @see input::appendAttributes
     */
    protected function appendAttributes(): string
    {
        $str = '';
        if ($this->max !== null) {
            $str .= ' max="'.$this->max.'"';
        }

        if ($this->min !== null) {
            $str .= ' min="'.$this->min.'"';
        }

        return $str . ' ';
    }


}

?>