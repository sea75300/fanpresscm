<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: autocompleteGetData
 * 
 * Event wird ausgeführt, wenn autocomplete-Controller aufgerufen wird
 * Parameter: array mit Daten für Autocomplete
 * Rückgabe: array mit Daten für Autocomplete
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 * @since FPCM 3.6
 */
final class autocompleteGetData extends \fpcm\events\abstracts\eventReturnArray {

    /**
     * Executes a certain event
     * @param array $data
     * @return array
     */
    public function run()
    {
        $result = parent::run();
        if (!count($result) || !isset($result['returnData'])) {
            return $this->data['returnData'];
        }

        return $result['returnData'];
    }

}
