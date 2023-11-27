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
final class passwordInput extends input {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        parent::init();
        $this->type = 'password';
    }

    /**
     * Appends item to input field before wrapper
     * @param string $str
     * @return bool
     * @since 4.5.2
     */
    protected function appendItems(string &$str) : bool
    {
        if ($this->labelType === self::LABEL_TYPE_FLOATING) {
            return true;
        }

        $str .= (string) $this->getButtonObject();
        return true;
    }
    
    

    /**
     * Adds input end wrapper
     * @return string
     * @since 5.2.0-a1
     */
    protected function getWrapperEnd(): string
    {
        if ($this->labelType !== self::LABEL_TYPE_FLOATING) {
            return parent::getWrapperEnd();
        }

        return '</div>' . (string) $this->getButtonObject();        

    }

    /**
     * Adds input start wrapper
     * @return string
     * @since 5.2.0-a1
     */
    protected function getWrapperStart(): string
    {
        return $this->labelType === self::LABEL_TYPE_FLOATING ? "<div class= \"input-group {$this->bottomSpace}\"><div class=\"{$this->labelType}\">" : parent::getWrapperStart();
    }

    /**
     * Return button object
     * @return button
     * @since 5.2.0-a1
     */
    protected function getButtonObject() : button
    {
        return (new button($this->name . '-toggle'))
                    ->setText('PASSWORD_TOGGLE')
                    ->setIcon('eye')
                    ->setIconOnly()
                    ->setClass('fpcm ui-put-pass-toggle')
                    ->setOnClick('system.togglePasswordField')
                    ->setReadonly($this->readonly);
    }

}
