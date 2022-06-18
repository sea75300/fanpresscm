<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Wrapper view helper object for common use
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.0-dev
 */
class wrapper extends helper {

    /**
     * HTML element
     * @var string
     */
    private $el = '';

    /**
     * View helper item
     * @var helper
     */
    private $item = null;

    /**
     * 
     * @param string $el
     * @param string $class
     * @param \fpcm\view\helper\helper $item
     * @return void
     */
    public function __construct(string $el, string $class, $item)
    {
        $this->el = $el;
        $this->class = $class;
        
        if (!$item instanceof helper) {
            trigger_error('Invalid parameter, $item must be an instance of /fpcm/view/helper');
            return;
        }
        
        $this->item = $item;
    }

    /**
     * 
     * @return string
     */
    protected function getString(): string
    {
        return "<{$this->el} {$this->getClassString()}>{$this->item}</{$this->el}>";
    }

}

?>