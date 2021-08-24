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
 * @since 4.2
 */
final class dateTimeInput extends input {

    use traits\minMaxHelper;
    
    const DEFAULT_CLASS = 'fpcm-ui-datetime-picker';

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->type = 'date';
        $this->class .= ' '.self::DEFAULT_CLASS;
        $this->maxlenght = '';
        $this->setPlaceholder('');
    }

    /**
     * Enables native browser date selection field input
     * @return void
     */
    public function setNativeDate()
    {
        $this->type = 'date';
        $this->replaceDefaultCssClass('fpcm-ui-input-date-native');
        return $this;
    }

    /**
     * Enables native browser time selection field input
     * @return void
     */
    public function setNativeTime()
    {
        $this->type = 'time';
        $this->replaceDefaultCssClass('fpcm-ui-input-time-native');
        return $this;
    }

    /**
     * Enables native browser datetime-local selection field input
     * @return void
     */
    public function setNativeDateTime()
    {
        $this->type = 'datetime-local';
        $this->replaceDefaultCssClass('fpcm-ui-input-datetime-native');
        return $this;
    }

    /**
     * 
     * Replaces default css-classes within native date/time/datetime fields
     * @param string $newClass
     * @return void
     */
    private function replaceDefaultCssClass(string $newClass)
    {
        $this->class = str_replace(self::DEFAULT_CLASS, $newClass, $this->class);
    }

}

?>