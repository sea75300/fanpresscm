<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Badge view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class badge extends helper {

    use traits\iconHelper,
        traits\valueHelper;

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        $title = ($this->text ? "title=\"{$this->text}\" " : '');
        return "<span {$this->getIdString()}{$title}{$this->getClassString()}>{$this->getIconString()}{$this->value}</span>";
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'fpcm-ui-badge badge p-2';
    }

}

?>