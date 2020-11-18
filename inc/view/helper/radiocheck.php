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
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class radiocheck extends helper {

    use traits\iconHelper,
        traits\valueHelper,
        traits\typeHelper,
        traits\selectedHelper;

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'fpcm-ui-input-radiocheck';
        $this->value = 1;
    }

    /**
     * Return selected string
     * @return string
     */
    protected function getSelectedString()
    {
        return $this->selected && $this->value == $this->selected ? 'checked' : '';
    }

    /**
     * Return class string
     * @return string
     */
    protected function getReadonlyString()
    {
        return $this->readonly ? "disabled" : '';
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        $labelClass = (strpos($this->class, 'fpcm-ui-hidden') !== false) ? $this->getClassString() : '';
        
        if (!$labelClass && trim($this->labelClass)) {
            $labelClass = " class=\"{$this->labelClass}\"";
        }

        if ($this->iconOnly) {            
            return implode(' ', [
                "<input type=\"{$this->type}\"",
                $this->getNameIdString(),
                $this->getClassString(),
                $this->getReadonlyString(),
                $this->getValueString(),
                $this->getDataString(),
                $this->getSelectedString(),
                ">",
                "<label for=\"{$this->id}\" title=\"{$this->text}\" {$labelClass}>",
                $this->getIconString(),
                "</label>"
            ]);
        }
        
        return implode(' ', [
            "<input type=\"{$this->type}\"",
            $this->getNameIdString(),
            $this->getClassString(),
            $this->getReadonlyString(),
            $this->getValueString(),
            $this->getDataString(),
            $this->getSelectedString(),
            "><label for=\"{$this->id}\" {$labelClass}>",
            $this->getIconString(),
            $this->getDescriptionTextString(),
            "</label>",
        ]);
    }

}

?>