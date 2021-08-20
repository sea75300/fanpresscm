<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Shorthelp button view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class shorthelpButton extends linkButton {

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class = 'fpcm ui-button-shorthelp';
        $this->iconOnly = true;
        $this->target = '_blank';
        $this->rel = 'noreferrer,noopener,external';
        $this->setIcon('question-circle');
    }

    /**
     * Return element string
     * @return string
     */
    protected function getString()
    {
        if (!trim($this->url)) {
            return "<span {$this->getClassString()} title=\"{$this->text}\">{$this->getIconString()}</span>";
        }

        return "<a href=\"{$this->url}\" rel=\"help noopener\" target=\"{$this->target}\" {$this->getNameIdString()} {$this->getClassString()} title=\"{$this->text}\" {$this->getRelString()} {$this->getDataString()}>{$this->getIconString()}</a>";
    }

}

?>