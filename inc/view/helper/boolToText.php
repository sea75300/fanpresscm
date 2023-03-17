<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Bool select menu view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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

            $this->setClass('text-success');
            $this->setIcon('check-square');
            
        } else {

            if (!trim($this->text)) {
                $this->setText('GLOBAL_NO');
            }

            $this->setClass('text-danger');
            $this->setIcon('minus-square');
        }

        return str_replace(
            ["class=\"", '></span>'],
            ["class=\"{$this->class} ", "{$this->getIdString()} title=\"{$this->text}\"></span>"],
            $this->getIconString());
    }

}

?>