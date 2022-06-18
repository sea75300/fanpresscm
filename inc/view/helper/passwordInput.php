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
 * @copyright (c) 2011-2020, Stefan Seehafer
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

    protected function appendItems(string &$str): bool
    {
     
        $this->getLabelTypeConfig($wrprS, $wrprE, $class, $type, $icoO);
        
        $str .= $wrprS .
                (string) (new button($this->name . '-toggle'))
                    ->overrideButtonType($type)
                    ->setText('PASSWORD_TOGGLE')
                    ->setIcon('eye')
                    ->setIconOnly($icoO)
                    ->setClass('fpcm ui-put-pass-toggle' . $class)
                    ->setOnClick('system.togglePasswordField')
                    ->setReadonly($this->readonly) .
                $wrprE;

        return true;
    }

    /**
     * 
     * @param string $wrprS
     * @param string $wrprE
     * @param string $class
     * @param string $type
     * @param string $icoO
     * @return bool
     */
    private function getLabelTypeConfig(&$wrprS, &$wrprE, &$class, &$type, &$icoO) : bool
    {
        if ($this->labelType === 'form-floating') {
            $wrprS = '<div class="d-block d-flex justify-content-end">';
            $wrprE = '</div>';
            $class = ' shadow-none ui-font-small';
            $type  = 'link';
            $icoO  = false;
            return true;
        }

        $wrprS = '';
        $wrprE = '';
        $class = '';
        $type  = 'light';
        $icoO  = true;
        return true;
    }

}

?>