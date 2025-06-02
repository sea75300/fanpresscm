<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\traits;

/**
 * Extent ui element trait
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.3
 */
trait extendUiEvent {

    protected function doEventbyArea(\fpcm\module\event $module) : \fpcm\module\eventResult
    {
        if (!$this->is_a($module) || !method_exists($module, $this->data->area)) {
            return false;
        }

        return call_user_func([$module, $this->data->area]);
    }

}