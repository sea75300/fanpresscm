<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Imput view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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
     * @since 4.1
     */
    protected $autocomplete = true;

    /**
     * Pattern for client side validation
     * @var string
     * @since 4.4
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
        
        if (!trim($this->labelSize) && !trim($this->fieldSize)) {
            $this->setDisplaySizesDefault();
        }

        $isFloating = $this->isFloating();

        $wrapperStart = '';
        $wrapperEnd = '';

        $wrapperStart = "<div class=\"{$this->labelType} mb-3\">";
        $wrapperEnd = "</div>";
        
        $mlstr = $this->maxlenght ? "maxlength=\"{$this->maxlenght}\"" : '';

        $input = "<input type=\"{$this->type}\" {$mlstr} {$this->getAttributeStrings()}>";
        $this->appendItems($input);

        if (!$this->text) {
            return $wrapperStart . $input . $wrapperEnd;
        }

        $description = $this->placeholder !== true ? $this->getDescriptionTextString($isFloating ? '' : 'ps-1') : '';
        if ( ($this->getIconString() || trim($description)) ) {
            $descrCss = !$isFloating ? 'col-form-label pe-3 ' . $this->getLabelSize() : '';
            $description = "<label title=\"{$this->text}\" class=\"{$descrCss}\" for=\"{$this->id}\">{$this->getIconString()}{$description}</label>";
        }

        return $wrapperStart . (!$isFloating ? $description : '' )  . $input . ($isFloating ? $description : '' )  . $wrapperEnd;
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'fpcm-ui-input form-control';
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
     * @since 4.1
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
     * @since 4.4
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
     * @since 4.1
     */
    protected function getAutocompleteString()
    {
        return ($this->autocomplete ? '' : "autocomplete=\"off\"");
    }

    /**
     * Browser autocomplete string
     * @return string
     * @since 4.1
     */
    protected function getPatternString()
    {
        if (!trim($this->pattern)) {
            return '';
        }
        
        return 'pattern="'. trim($this->pattern).'"';
    }

    /**
     * Return required string
     * @return string
     * @since 5.0.0-a3
     */
    protected function getRequiredString()
    {
        return $this->requ ? 'required' : '';
    }

    /**
     * 
     * @param string $str
     * @return bool
     * @since 4.5.2
     */
    protected function appendItems(string &$str) : bool
    {
        return true;
    }

    /**
     * Append attributes to input element
     * @return string
     * @since 5.0-dev
     */
    protected function appendAttributes() : string
    {
        return '';
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
            $this->getRequiredString(),
            $this->getDataString(),
            $this->appendAttributes()
        ]);
    }

}

?>