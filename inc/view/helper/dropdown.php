<?php

/**
 * FanPress CM 5
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
        traits\selectedHelper,
        traits\escapeHelper;

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
     * 
     * @var string
     * @since 5.0.0-b5
     */
    protected $ddType = '';

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {        
        $btnId = $this->id . 'Button';
        
        $options = $this->getOptionsString($this->options);
        
        $btn = (new button($btnId))->setText($this->text)->setClass('dropdown-toggle')->setData([ 'bs-toggle' => 'dropdown' ])->setAria(['expanded' => 'false']);
        if (trim($this->icon)) {
            $btn->setIcon($this->icon);
        }
        
        return implode(' ', [
            "<div",
            $this->getIdString(),
            $this->getClassString(),
            $this->getDataString(),
            "role=\"group\" >",
            $btn,
            "   <ul class=\"dropdown-menu {$this->ddType}\" aria-labelledby=\"{$btnId}\" id=\"{$this->id}List\">",
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
     * Set dropdown type
     * @param string $ddType
     * @return $this
     * @since 5.0.0-b5
     */
    public function setDdType(string $ddType)
    {
        $this->ddType = 'dropdown-menu-' . $ddType;
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
            
            if (! $value instanceof dropdownItem) {
                $value = (new dropdownItem(md5(uniqid('ddi').$key.$value) ))->setText($key)->setValue($value);
            }         

            if ($value->getValue() == $this->selected) {
                $value->setClass('active');
                $this->text = $value->getText();
            }            

            $this->returnString[] = (string) $value;
        }

        return implode(PHP_EOL, $this->returnString);
    }

}

?>