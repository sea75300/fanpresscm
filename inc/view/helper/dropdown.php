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
class dropdown extends helper {

    use traits\iconHelper,
        traits\labelFieldSize,
        traits\selectedHelper;

    /**
     * Select options
     * @var array
     */
    protected $options = [];

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
        $btnId = $this->id . 'Button';
        
        $options = $this->getOptionsString($this->options);
        
        return implode(' ', [
            "<div",
            $this->getIdString(),
            $this->getClassString(),
            $this->getDataString(),
            "role=\"group\" >",
            (new button($btnId))->setText($this->text)->setClass('dropdown-toggle')->setData([ 'bs-toggle' => 'dropdown' ])->setAria(['expanded' => 'false'])
                ,
            "   <ul class=\"dropdown-menu\" aria-labelledby=\"{$btnId}\" id=\"{$this->id}List\">",
                $options,
            "   </ul>",
            "</div>",
        ]);
        
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'dropdown';
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
     * Create options string
     * @param array $options
     * @return string|void
     */
    protected function getOptionsString($options)
    {
        if (!count($options)) {
            return '';
        }

        $this->value = '';
        
        if (!is_array($options)) {
            return '';
        }

        foreach ($options as $key => $value) {
            $this->value = $this->escapeVal($value, ENT_QUOTES);
            $key = $this->escapeVal($key, ENT_QUOTES);
            
            $class = '';
            if ($this->value == $this->selected) {
                $class = 'active';
                $this->text = $key;
            }            

            $this->returnString[] = "<li><a class=\"dropdown-item {$class}\">{$this->language->translate($key)}</a></li>";
        }

        return;
    }

}

?>