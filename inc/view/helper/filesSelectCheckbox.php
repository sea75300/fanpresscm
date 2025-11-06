<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Chckbox view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.0
 */
final class filesSelectCheckbox extends checkbox {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->class = 'btn-check';
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        return sprintf(
            '<input type="%s" class="%s" %s %s autocomplete="off"><label class="btn btn-%s" for="%s" title="%s">%s</label>',
            $this->type,
            $this->class,
            $this->getNameIdString(),
            $this->getValueString(),
            $this->getColorMode(),
            $this->id,
            $this->text,
            $this->getIconString()
        );
    }
    
}
