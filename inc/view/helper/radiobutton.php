<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Chckbox view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class radiobutton extends radiocheck {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->type = 'radio';
        $this->class .= ' form-check-input';
    }

}

?>