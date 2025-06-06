<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\traits;

/**
 * Extent ui element trait
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.3
 */
trait extendUiEvent {

    /**
     * ARea name
     * @var string
     */
    protected string $area;

    /**
     * Data before event running
     * @var mixed
     */
    protected mixed $beforeRunData;

    /**
     * Performances event call by area name
     * @param \fpcm\module\event $module
     * @return \fpcm\module\eventResult
     */
    protected function doEventbyArea(\fpcm\module\event $module) : ?\fpcm\module\eventResult
    {
        if (!method_exists($module, $this->area)) {            
            return (new \fpcm\module\eventResult())->setData($this->getEventParams());
        }

        return call_user_func([$module, $this->area]);
    }

}