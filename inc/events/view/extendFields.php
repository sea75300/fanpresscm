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
     * Preprare event before running
     * @return bool
     */
    protected function beforeRun() : void
    {
        $this->beforeRunData = $this->data;
        $this->data->area = \fpcm\classes\tools::getAreaName('form');
    }

}
