<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper\traits;

/**
 * View helper with value
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait selectedHelper {

    /**
     * Element value
     * @var string|array
     */
    protected $selected = '';

    /**
     * Select multiple options
     * @var boolean
     */
    protected $isMultiple = false;

    /**
     * Set preselected value
     * @param string|int|array $selected
     * @return $this
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;
        return $this;
    }

    /**
     * Enable multiple selection
     * @param bool $isMultiple
     * @return $this
     */    
    public function setIsMultiple($isMultiple)
    {
        $this->isMultiple = (bool) $isMultiple;
        return $this;
    }

    /**
     * Return selected string
     * @return string
     */
    protected function getSelectedString()
    {
        if ($this->isMultiple) {
            return in_array($this->value, $this->selected) ? 'selected' : '';
        }

        return $this->value == $this->selected ? 'selected' : '';
    }

}

?>