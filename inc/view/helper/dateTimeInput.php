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
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCm 4.2
 */
final class dateTimeInput extends input {

    const DEFAULT_CLASS = 'fpcm-ui-datetime-picker';

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->type = 'text';
        $this->class .= ' '.self::DEFAULT_CLASS;
    }
    
    public function setNativeDate()
    {
        $this->type = 'date';
        $this->replaceDefaultCssClass('fpcm-ui-input-date-native');
    }
    
    public function setNativeTime()
    {
        $this->type = 'time';
        $this->replaceDefaultCssClass('fpcm-ui-input-time-native');
    }
    
    public function setNativeDateTime()
    {
        $this->type = 'datetime-local';
        $this->replaceDefaultCssClass('fpcm-ui-input-datetime-native');
    }
    
    private function replaceDefaultCssClass($newClass)
    {
        $this->class = str_replace(self::DEFAULT_CLASS, $newClass, $this->class);
    }

}

?>