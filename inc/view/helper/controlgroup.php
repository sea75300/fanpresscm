<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Control group item
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\view\helper
 * @since 4.4
 */
class controlgroup extends helper {

    /**
     * Add controlgroup item
     * @param \fpcm\view\helper $item
     */
    final public function addItem($item)
    {
        if (!$item instanceof helper) {
            trigger_error('Item of class '.get_class($item).' must be an instace of fpcm\view\helper\helper!');
            return $this;
        }

        $this->data[] = $item;
        return $this;
    }

    /**
     * Return item string
     * @return string
     */
    protected function getString()
    {
        $this->class .= ' ';
        
        return "<div {$this->getIdString()} {$this->getClassString()}>".implode('', $this->data).'</div>';
    }

    /**
     * Optional init function
     * @return void
     */
    protected function init()
    {
        $this->class .= ' fpcm-ui-controlgroup';
    }
}
