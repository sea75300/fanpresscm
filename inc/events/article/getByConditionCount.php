<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\article;

/**
 * Module-Event: article/getByConditionCount
 * 
 * Event wird ausgeführt, wenn Artikel anhand von Bedingungen gezählt werden
 * Parameter: array Zählbedingungen
 * Rückgabe: array Zählbedingungen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 * @since FPCM 3.4
 */
final class getByConditionCount extends \fpcm\events\abstracts\eventReturnArray {

    /**
     * wird ausgeführt, wenn Kommentar-Suche ausgeführt wird
     * @param array $data
     * @return array
     */
    public function run()
    {
        $eventData = parent::run();
        if (!isset($eventData['where']) || !is_array($eventData['where']) ||
            !isset($eventData['values']) || !is_array($eventData['values'])) {
            return $this->data;
        }

        return $eventData;
    }
}
