<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Alert view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class alert extends helper {

    use traits\iconHelper;

    /**
     * Constructor
     * @param string $type
     */
    public function __construct(string $type)
    {
        parent::__construct(uniqid('alert'));
        $this->setClass('alert-'.$type);
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        return "<div {$this->getAriaString()}{$this->getClassString()} role=\"alert\">{$this->getIconString()}{$this->text}</div>";
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'shadow-sm alert alert-';
        $this->aria['role'] = 'alert';
    }

}
