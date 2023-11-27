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
class hiddenInput extends input {

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        return "<input type=\"hidden\" {$this->getNameIdString()}{$this->getDataString()} {$this->getValueString()}>";
    }

}
