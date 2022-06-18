<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper\traits;

/**
 * Escape elemtn value helper
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.0-dev
 */
trait minMaxHelper {

    /**
     * Minimum value
     * @var mixed
     */
    protected $max = null;

    /**
     * Maximum value
     * @var mixed
     */
    protected $min = null;

    /**
     * Step value
     * @var mixed
     */
    protected $step = null;

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
     * Set input minimum
     * @param int|float $min
     * @return $this
     */
    public function setStep($step)
    {
        $this->step = $step;
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

        if ($this->step !== null) {
            $str .= ' step="'.$this->step.'"';
        }

        return $str . ' ';
    }

}

?>