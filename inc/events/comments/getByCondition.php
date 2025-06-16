<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\comments;

/**
 * Module-Event: getByCondition
 *
 * Event wird ausgeführt, wenn Kommentar-Suche ausgeführt wird
 * Parameter: array Suchbedingungen
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 3.4
 */
final class getByCondition extends \fpcm\events\abstracts\event {

    /**
     * After event running
     * @return bool
     */
    protected function afterRun() : void
    {
        $tmp = $this->data->getData();
        if (!isset($tmp['where']) || !is_array($tmp['where']) ||
            !isset($tmp['conditions']) || !$tmp['conditions'] instanceof \fpcm\model\comments\search ||
            !isset($tmp['values']) || !is_array($tmp['values'])) {

            $this->data = (new \fpcm\module\eventResult)->setSuccessed(false)->setData([]);
        }

    }

}
