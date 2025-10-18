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
abstract class radiocheck extends helper
implements interfaces\jsDialogHelper, \JsonSerializable {

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
    public function setSwitch(bool $switch = true) {
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

        $wrapStart = '';
        $wrapEnd = '';

        if ($this->text && !$this->iconOnly) {
            $wrapStart = sprintf(
                '<div class="form-check %s %s %s">',
                $this->inline ? 'form-check-inline' : '',
                $this->switch ? 'form-switch' : '',
                $this->wrapperClass
            );

        }
        elseif ($this->switch) {
            $wrapStart = '<div class="form-check form-switch">';
        }

        if ($this->text && !$this->iconOnly || $this->switch) {
            $wrapEnd = '</div>';
        }

        $inEL = sprintf(
            '<input type="%s" %s %s %s %s %s %s>',
            $this->type,
            $this->getNameIdString(),
            $this->getValueString(),
            $this->getClassString(),
            $this->getReadonlyString(),
            $this->getDataString(),
            $this->getSelectedString()
        );
        
        $inLa = '';
        if ($this->text && !$this->iconOnly) {
            $descr = $this->iconOnly ? '' : $this->getDescriptionTextString();
            $inLa = sprintf('<label for="%s" class="%s">%s%s</label>', $this->id, $labelClass, $this->getIconString(), $descr);
        }

        return $wrapStart .$inEL . $inLa . $wrapEnd;

    }

}
