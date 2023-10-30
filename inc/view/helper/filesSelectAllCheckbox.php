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
 * @copyright (c) 2011-2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.0
 */
final class filesSelectAllCheckbox extends checkbox {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->class = 'btn-check fpcm-select-all';
        $this->icon = (new \fpcm\view\helper\icon('check-double'))->setText('GLOBAL_SELECTALL');
        
        
    }

    protected function getString()
    {
        return sprintf(
            '<input type="%s" class="%s" %s autocomplete="off"><label class="btn btn-%s" for="%s">%s</label>',
            $this->type,
            $this->class,
            $this->getNameIdString(),
            $this->getColorMode(),
            $this->id,
            $this->icon
        );
    }
    
}
