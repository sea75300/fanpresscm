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
        traits\typeHelper;

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
            $wrapperStart = "<div class=\"fpcm-ui-input-wrapper col-{$this->colWidth} {$this->wrapperClass} fpcm-ui-padding-none-lr\"><div class=\"fpcm-ui-input-wrapper-inner\">";
            $wrapperEnd = "</div></div>";
        }
        
        $this->class .= ' fpcm-ui-input-text-'.$this->type;

        $input = "<input type=\"{$this->type}\" {$this->getNameIdString()}{$this->getClassString()} {$this->getValueString()} {$this->getReadonlyString()} maxlength=\"{$this->maxlenght}\" {$this->getAutocompleteString()}  {$this->getAutoFocusedString()} {$this->getPlaceholderString()}  {$this->getDataString()}>";
        if (!$this->text) {
            return $wrapperStart . $input . $wrapperEnd;
        }

        $description = $this->placeholder !== true ? $this->getDescriptionTextString() : '';        
        $description = "<label class=\"{$this->labelClass}\" for=\"{$this->id}\">{$this->getIconString()}{$description}</label>";

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


}

?>