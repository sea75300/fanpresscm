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
class dropdownItem {

    use traits\valueHelper,
        traits\urlHelper;

    /**
     * Item text
     * @var string
     */
    protected $text;

    /**
     * Item class
     * @var string
     */
    protected $class;

    /**
     * Language object
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->url = '#';
        $this->class = 'dropdown-item';
        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language');        
    }

    /**
     * Set dropdown item text
     * @param string $text
     * @return $this
     */
    public function setText(string $text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Set dropdown item css class
     * @param string $text
     * @return $this
     */
    public function setClass(string $class)
    {
        $this->class .= ' ' . $class;
        return $this;
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
     * @ignore
     * @return string
     */
    public function __toString()
    {
        return "<li><a href=\"{$this->url}\" class=\"{$this->class}\">{$this->language->translate($this->text)}</a></li>";
    }


}

?>