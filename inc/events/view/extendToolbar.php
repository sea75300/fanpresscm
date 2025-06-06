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
    protected function beforeRun() : void
    {
        $this->area = \fpcm\classes\tools::getAreaName('toolbar');
        $this->beforeRunData = $this->data;
    }

    /**
     * Returns event params
     * @return mixed
     */
    protected function getEventParams() : mixed
    {
        return $this->data->buttons;
    }

    /**
     * After event running
     * @return bool
     */
    protected function afterRun() : void
    {
        $this->beforeRunData->buttons = $this->data->getData();
        $this->data->setData($this->beforeRunData);
    }

}
