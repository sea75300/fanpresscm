<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Icon view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class icon extends helper {

    use traits\iconHelper;

    public function __construct($icon, $prefix = 'fa', $useFa = true)
    {
        $this->setIcon($icon, $prefix, $useFa);
        parent::__construct(uniqid());
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        if ($this->iconStack) {
            
            $stack = "<span class=\"fa {$this->iconStack} fa-stack-2x\"></span>";
            
            return implode(PHP_EOL, [
                "<span class=\"{$this->class} fa-stack {$this->size}\"" . ($this->text ? " title=\"{$this->text}\"" : '') . " {$this->getDataString()}>",
                !$this->stackTop ? $stack : '',
                "<span class=\"fpcm-ui-icon {$this->icon} fa-stack-1x\"></span>",
                $this->stackTop ? $stack : '',
                "</span>",
            ]);
        }

        return "<span class=\"fpcm-ui-icon {$this->class} {$this->icon} {$this->size} \"" . ($this->text ? " title=\"{$this->text}\"" : '') . "{$this->getDataString()}></span> ";
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'fpcm-ui-icon-single';
    }

}

?>