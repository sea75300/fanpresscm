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
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
class accordion extends helper {

    use traits\escapeHelper;

    /**
     * Select options
     * @var array
     */
    protected $items = [];

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
        $this->id = 'fpcm-id-accordion-' . $this->id;
        
        return <<<HTML
            <div {$this->getIdString()} {$this->getClassString()} {$this->getDataString()}>
               {$this->getOptionsString()}
            </div>
            \n
        HTML;
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'accordion';
    }

    /**
     * Set items for selectbox
     * @param array $items
     * @return $this
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Create options string
     * @param array $options
     * @return string|void
     */
    protected function getOptionsString() : string
    {
        $str = '';
        if (!count($this->items)) {
            return $str;
        }

        foreach ($this->items as $key => $value) {
            
            if (! $value instanceof accordionItem) {
                $value = (new accordionItem(md5(uniqid('acci').$key.$value) ))->setText($key)->setValue($value);
            }         

            $str .= (string) $value;
        }

        return $str;
    }

}
