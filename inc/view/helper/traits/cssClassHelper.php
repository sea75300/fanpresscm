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
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait cssClassHelper {

    /**
     * CSS class string
     * @var string
     */
    protected $class = '';

    /**
     * Set additional css class
     * @param string $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->class .= ' ' . $class;
        return $this;
    }

    /**
     * Return class string
     * @return string
     */
    protected function getClassString()
    {
        $this->class = array_unique(explode(' ', $this->class));
        $this->class = implode(' ', $this->class);
        return "class=\"{$this->class}\"";
    }

}

?>