<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Text input view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class numberInput extends input {

    use traits\minMaxHelper;
    
    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->type = 'number';
        $this->maxlenght = '';
    }

}
