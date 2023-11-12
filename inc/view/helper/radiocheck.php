<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Text input view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class radiocheck extends helper {

    use traits\iconHelper,
        traits\valueHelper,
        traits\typeHelper,
        traits\selectedHelper;

    /**
     * Inline element
     * @var bool
     * @since 5.0-dev
     */
    protected $inline = false;

    /**
     * Switch element
     * @var bool
     * @since 5.0-dev
     */
    protected $switch = false;

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
     * Set check to inline element
     * @param bool $inline
     * @return $this
     * @since 5.0-dev
     */
    public function setInline(bool $inline)
    {
        $this->inline = $inline;
        return $this;
    }

    /**
     * Set switch element
     * @param bool $inline
     * @return $this
     * @since 5.0-dev
     */
    public function setSwitch(bool $switch) {
        $this->switch = $switch;
        return $this;
    }
    
    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        $labelClass = ' form-check-label '.$this->labelClass;
        
        $wrapStart = $this->text && !$this->iconOnly ? '<div class="form-check '. ( $this->inline ? 'form-check-inline' : '') . ($this->switch ? 'form-switch' : '').' ' . $this->wrapperClass . '  ">' : '';
        $wrapEnd   = $this->text && !$this->iconOnly ? '</div>' : '';

        $inEL = "<input type=\"{$this->type}\" {$this->getNameIdString()} {$this->getClassString()} {$this->getReadonlyString()} {$this->getValueString()} {$this->getDataString()} {$this->getSelectedString()}>";

        $inLa = '';
        if ($this->text && !$this->iconOnly) {
            $inLa = "<label for=\"{$this->id}\" class=\"{$labelClass}\">" .
                    $this->getIconString() .
                    ( $this->iconOnly ? '' : $this->getDescriptionTextString() ) .
                   "</label>";
        }
        
        return $wrapStart .$inEL . $inLa . $wrapEnd;

    }

}
