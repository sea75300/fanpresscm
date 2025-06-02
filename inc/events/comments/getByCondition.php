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
 * Rückgabe: array Suchbedingungen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 3.4
 */
final class getByCondition extends \fpcm\events\abstracts\event {

    /**
     * Executes a certain event
     * @param array $data
     * @return array
     */
    public function run() : \fpcm\module\eventResult
    {        
        $result = parent::run();
        $tmp = $result->getData();
        if (!isset($tmp['where']) || !is_array($tmp['where']) ||
            !isset($tmp['conditions']) || $tmp['conditions'] instanceof \fpcm\model\comments\search ||
            !isset($tmp['values']) || !is_array($tmp['values'])) {
            return (new \fpcm\module\eventResult)->setContinue(true)->setData($this->data);
        }

        return $eventData;        
    }

}
