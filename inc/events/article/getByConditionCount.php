<?php

/**
 * FanPress CM 5.x
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
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 3.4
 */
final class getByConditionCount extends \fpcm\events\abstracts\eventReturnArray {

    /**
     * Executes a certain event
     * @param array $data
     * @return array
     */
    public function run()
    {
        $eventData = parent::run();
        
        $obj = $eventData->getData();
        if (!isset($obj['where']) || !is_array($obj['where']) ||
            !isset($obj['values']) || !is_array($obj['values'])) {
            return (new \fpcm\module\eventResult)->setContinue(true)->setData($this->data);
        }

        return $eventData;           
        
    }
}
