<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Select menu view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class select extends helper {
    
    /* @var Auto-add empty first option */
    const FIRST_OPTION_EMPTY = -1;

    /* @var Auto-add first action with please select text */
    const FIRST_OPTION_PLEASESELECT = -2;

    /* @var Do not auto-add first option */
    const FIRST_OPTION_DISABLED = -3;

    use traits\iconHelper,
        traits\valueHelper,
        traits\selectedHelper,
        traits\labelFieldSize;

    /**
     * Select options
     * @var array
     */
    protected $options = [];

    /**
     * Is first option auto added
     * @var int
     */
    protected $firstOption;

    /**
     * Select includes opt groups
     * @var boolean
     */
    protected $hasOptGroup = false;

    /**
     * Prepend label to selectbox
     * @var boolean
     */
    protected $prependLabel = false;

    /**
     * Option return string
     * @var string
     */
    protected $returnString = [];

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        if (trim($this->text) && substr($this->text, 0, strlen(self::TEXT_DEFAULT_LABEL)) !== self::TEXT_DEFAULT_LABEL) {
            $this->prependLabel();
        }
        
        if (!trim($this->labelSize) && !trim($this->fieldSize)) {
            $this->setDisplaySizesDefault();
        }
        
//        if ($this->isMultiple) {
//            $this->class = str_replace('fpcm-ui-input-select', 'fpcm-ui-input-select-multiple', $this->class);        
//        }

        $label = '';
        if ($this->prependLabel) {
            $label = "<div class=\"input-group mb-3\"><label title=\"{$this->text}\" class=\"input-group-text {$this->labelClass}{$this->getLabelSize()}\" for=\"{$this->id}\">{$this->getIconString()}{$this->getDescriptionTextString()}</label>";
        }
        
        return $label.implode(' ', [
            "<select".($this->isMultiple ? ' multiple' : ''),
            $this->getNameIdString(),
            $this->getClassString(),
            $this->getReadonlyString(),
            $this->getDataString(),
            ">",
            $this->hasOptGroup ? $this->getOptionsGroupsString() : $this->getOptionsString($this->options),
            "</select>",
        ]) . ($this->prependLabel ? '</div>' : '');
        
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'form-select';
        $this->firstOption = self::FIRST_OPTION_PLEASESELECT;
    }

    /**
     * Set options for selectbox
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Select auto-added first option, values are fpcm\view\helper\select::FIRST_OPTION_EMPTY, fpcm\view\helper\select::FIRST_OPTION_PLEASESELECT, fpcm\view\helper\select::FIRST_OPTION_DISABLED
     * @param int $firstOption
     * @return $this
     */
    public function setFirstOption($firstOption)
    {
        $this->firstOption = (int) $firstOption;
        return $this;
    }

    /**
     * Enables opt group for select element
     * @param bool $hasGroup
     * @return $this
     */
    public function setOptGroup($hasGroup)
    {
        $this->hasOptGroup = (bool) $hasGroup;
        return $this;
    }

    /**
     * Set with of select menu
     * @param int $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->data['width'] = (int) $width;
        return $this;
    }

    /**
     * Prepend label to select menu
     * @return $this
     * @since 4.5
     */
    public function prependLabel() {
        $this->prependLabel = true;
        return $this;
    }

    /**
     * Return first option string
     * @return bool
     */
    protected function getFirstOption()
    {
        switch ($this->firstOption) {
            case self::FIRST_OPTION_EMPTY :
                $this->returnString[] = "<option {$this->getValueString()} {$this->getSelectedString()}></option>";
                break;
            case self::FIRST_OPTION_PLEASESELECT :
                $this->returnString[] = "<option {$this->getValueString()} {$this->getSelectedString()}>{$this->language->translate('GLOBAL_SELECT')}</option>";
                break;
        }

        return true;
    }

    /**
     * Create options string
     * @param array $options
     * @return string|void
     */
    protected function getOptionsString($options)
    {
        if (!count($options)) {
            return '';
        }

        if (!$this->hasOptGroup) {
            $this->getFirstOption();
        }

        $this->value = '';
        
        if (!is_array($options)) {
            return '';
        }

        foreach ($options as $key => $value) {
            $this->value = $this->escapeVal($value, ENT_QUOTES);
            $key = $this->escapeVal($key, ENT_QUOTES);
            $this->returnString[] = "<option {$this->getValueString()} {$this->getSelectedString()}>{$this->language->translate($key)}</option>";
        }

        if (!$this->hasOptGroup) {
            return implode(PHP_EOL, $this->returnString);
        }

        return;
    }

    /**
     * Create options string
     * @return string
     */
    protected function getOptionsGroupsString()
    {
        if (!count($this->options)) {
            return '';
        }

        $this->value = '';
        foreach ($this->options as $label => $options) {

            if (!count($options)) {
                continue;
            }

            $this->returnString[] = "<optgroup label=\"{$this->language->translate($label)}\">";
            $this->getOptionsString($options);
            $this->returnString[] = "</optgroup>";
        }

        return implode(PHP_EOL, $this->returnString);
    }

}

?>