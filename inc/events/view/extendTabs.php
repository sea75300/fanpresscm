<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\view;

/**
 * Module-Event: extendTabs
 * 
 * Event extends tabs if tabs are set via view object
 * Parameter: extendTabsResult object
 * Rückgabe: \fpcm\module\eventResult
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.2.0-a1
 * @see \fpcm\view\view::addTabs
 */
final class extendTabs extends \fpcm\events\abstracts\event {

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

}
