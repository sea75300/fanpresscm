<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Imput view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class input extends helper {

    use traits\iconHelper,
        traits\valueHelper,
        traits\typeHelper,
        traits\labelFieldSize;

    /**
     * Maximum input lenght
     * @var int
     */
    protected $maxlenght = 255;

    /**
     * Use label text as placeholder
     * @var string
     */
    protected $placeholder = false;

    /**
     * Enables default browser autocompletion
     * @var string
     * @since FPCM 4.1
     */
    protected $autocomplete = true;

    /**
     * Pattern for client side validation
     * @var string
     * @since FPCM 4.4
     */
    protected $pattern = '';

    /**
     * Column with if input uses an icon
     * @var string
     */
    protected $colWidth = '12';

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        $wrapperStart = '';
        $wrapperEnd = '';

        if ($this->useWrapper) {
            $this->wrapperClass .= ' fpcm-ui-input-wrapper-'.$this->type;
            $wrapperStart = "<div class=\"fpcm-ui-input-wrapper col-{$this->colWidth} {$this->wrapperClass} fpcm-ui-padding-none-lr\"><div class=\"fpcm-ui-input-wrapper-inner\">";
            $wrapperEnd = "</div></div>";
        }
        else {
            $this->class .= ' fpcm-ui-field-input-nowrapper-general'.$this->getFieldSize();
        }
        
        $input = "<input type=\"{$this->type}\"maxlength=\"{$this->maxlenght}\" {$this->getAttributeStrings()}>";
        if (!$this->text) {
            return $wrapperStart . $input . $wrapperEnd;
        }

        $description = $this->placeholder !== true ? $this->getDescriptionTextString() : '';
        if ($this->getIconString() || trim($description)) {
            $description = "<label class=\"fpcm-ui-field-label-general {$this->labelClass}{$this->getLabelSize()}\" for=\"{$this->id}\">{$this->getIconString()}{$description}</label>";
        }

        return $wrapperStart . $description . $input . $wrapperEnd;
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'fpcm-ui-input';
        $this->labelClass = 'align-self-center';
    }

    /**
     * Set max lenght
     * @param int $maxlenght
     * @return $this
     */
    public function setMaxlenght($maxlenght)
    {
        $this->maxlenght = (int) $maxlenght;
        return $this;
    }

    /**
     * Use label text as placeholder
     * @param bool|string $placeholder
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * Set column with if input uses an icon
     * @param mixed $colWidth
     * @return $this
     */
    public function setInputColWidth($colWidth)
    {
        $this->colWidth = $colWidth;
        return $this;
    }

    /**
     * Enables/disables browser autocompletion
     * @param bool $autocomplete
     * @return $this
     * @since FPCM 4.1
     */
    public function setAutocomplete($autocomplete)
    {
        $this->autocomplete = (bool) $autocomplete;
        return $this;
    }

    /**
     * Pattern for client side validation
     * @param string $pattern
     * @return $this
     * @since FPCm 4.4
     */
    public function setPattern(string $pattern)
    {
        $this->pattern = ltrim($pattern, '/');
        return $this;
    }

    /**
     * Placeholder string
     * @return string
     */
    protected function getPlaceholderString()
    {
        if ($this->placeholder === true) {
            return "placeholder=\"{$this->text}\"";
        }

        if (is_string($this->placeholder) && trim($this->placeholder)) {
            return "placeholder=\"{$this->placeholder}\"";
        }

        return '';
    }

    /**
     * Browser autocomplete string
     * @return string
     * @since FPCM 4.1
     */
    protected function getAutocompleteString()
    {
        return ($this->autocomplete ? '' : "autocomplete=\"off\"");
    }

    /**
     * Browser autocomplete string
     * @return string
     * @since FPCM 4.1
     */
    protected function getPatternString()
    {
        if (!trim($this->pattern)) {
            return '';
        }
        
        return 'pattern="'. trim($this->pattern).'"';
    }

    /**
     * Fetch string for element attributes
     * @return string
     */
    private function getAttributeStrings()
    {
        return implode(' ', [
            $this->getNameIdString(),
            $this->getClassString(),
            $this->getValueString(),
            $this->getReadonlyString(),
            $this->getAutocompleteString(),
            $this->getAutoFocusedString(),
            $this->getPlaceholderString(),
            $this->getPatternString(),
            $this->getDataString()
        ]);
    }

}

?>