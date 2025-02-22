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
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class buttongroup extends helper {

    private array $buttons = [];

    /**
     * Constructor
     * @param string $type
     */
    public function __construct(string $text)
    {
        parent::__construct(uniqid('buttongroup'));
        $this->setText($text);
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        return sprintf(
            '<div role="group" aria-label="%s" %s %s>%s</div>',
            $this->text,
            $this->getAriaString(),
            $this->getClassString(),
            implode('', $this->buttons)
        );
    }

    /**
     * Array of buttons
     * @param array $buttons
     * @return $this
     */
    public function setButtons(array $buttons)
    {
        $this->buttons = $buttons;
        return $this;
    }
    
    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'btn-group';
    }

}
