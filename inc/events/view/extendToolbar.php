<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\view;

/**
 * Module-Event: extendToolbar
 *
 * Event extends main toolbar, calls a function "extendToolbarControllerName"
 * Parameter: array List with toolbar elements
 * Rückgabe: array List with toolbar elements
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 4.4
 */
final class extendToolbar extends \fpcm\events\abstracts\event {

    use \fpcm\events\traits\extendUiEvent;

    /**
     * Preprare event before running
     * @return bool
     */
    protected function beforeRun() : bool
    {
        $this->data->area = \fpcm\classes\tools::getAreaName('toolbar');
        return true;
    }

    /**
     * Process class data
     * @param string $class
     * @return bool
     */
    protected function processClass(string $class) : bool
    {
        /* @var \fpcm\module\event $module */
        $module = new $class($this->data->buttons);
        $r = $this->doEventbyArea($module);

        if ($r === false) {
            return false;
        }

        $this->data->buttons = $r->getData();
        return true;
    }

}
