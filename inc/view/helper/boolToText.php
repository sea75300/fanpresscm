<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Bool select menu view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class boolToText extends helper {

    use traits\valueHelper,
        traits\iconHelper;

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        if ($this->value) {

            if (!trim($this->text)) {
                $this->setText('GLOBAL_YES');
            }

            $this->setIcon('check-square');
            $this->setClass('fpcm-ui-booltext-yes');
            
        } else {

            if (!trim($this->text)) {
                $this->setText('GLOBAL_NO');
            }

            $this->setClass('fpcm-ui-booltext-no');
            $this->setIcon('minus-square');
        }

        return str_replace(
            ["class=\"", '></span>'],
            ["class=\"{$this->class} ", "{$this->getIdString()} title=\"{$this->text}\"></span>"],
            $this->getIconString());
    }

}

?>