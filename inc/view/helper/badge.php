<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Badge view helper object
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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

    /**
     * Add Padding
     * @param int $padding
     * @return $this
     */
    public function addPadding(int $padding)
    {
        if ($padding === -1) {
            $this->class = preg_replace('/(.*)(\ p-[0-9])/i', '$1', $this->class);
            return $this;
        }
        
        $this->class = preg_replace('/(.*\ p)\-([0-9]{1})/i', '$1-' . $padding, $this->class);
        return $this;
    }

}

?>