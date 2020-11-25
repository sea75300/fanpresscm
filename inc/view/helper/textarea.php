<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Text input view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class textarea extends input {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'fpcm-ui-textarea';
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        return  '<textarea '.$this->getNameIdString().' '.
                $this->getClassString().' '.
                $this->getReadonlyString().' '.
                $this->getDataString().' '.">".
                $this->value."</textarea>"
        ;
    }

}

?>