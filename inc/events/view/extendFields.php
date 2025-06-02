<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\view;

/**
 * Module-Event: extendFields
 * 
 * Extends simepla editor form fields
 * Parameter: array List with toolbar elements
 * Rückgabe: array List with toolbar elements
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.1.0-a1
 */
final class extendFields extends \fpcm\events\abstracts\event {
    
    use \fpcm\events\traits\extendUiEvent;

    /**
     * Process class data
     * @param string $class
     * @return bool
     */
    protected function processClass(string $class) : bool
    {
        /* @var \fpcm\module\event $module */
        $module = new $class($this->data);
        $r = $this->doEventbyArea($module);
        if ($r === false) {
            return false;
        }

        $this->data = $r;
        return true;
    }    

    /**
     * Preprare event before running
     * @return bool
     */
    protected function beforeRun(): bool
    {
        $this->data->area = \fpcm\classes\tools::getAreaName('form');
        return true;
    }

}
