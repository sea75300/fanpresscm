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
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.0-dev
 */
class dropdownItem extends helper {

    use traits\valueHelper,
        traits\iconHelper,
        traits\urlHelper;

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->url = '#';
        $this->class = 'dropdown-item';
    }

        /**
     * Return item value
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns item text
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Return item string
     * @return string
     */
    protected function getString(): string
    {        
        return "<li><a href=\"{$this->url}\" {$this->getClassString()} {$this->getDataString()} >{$this->getIconString()}{$this->language->translate($this->text)}</a></li>";
    }

}

?>