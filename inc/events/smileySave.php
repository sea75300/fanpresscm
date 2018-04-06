<?php

/**
 * Module-Event: smileySave
 * 
 * Event wird ausgeführt, wenn Smiley in Datenbank gespeichert wird
 * Parameter: array Smiley-Informationen
 * Rückgabe: array Smiley-Informationen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: smileySave
 * 
 * Event wird ausgeführt, wenn Smiley in Datenbank gespeichert wird
 * Parameter: array Smiley-Informationen
 * Rückgabe: array Smiley-Informationen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class smileySave extends \fpcm\events\abstracts\event {

    /**
     * wird ausgeführt, wenn Smiley in Datenbank gespeichert wird
     * @param array $data
     * @return array
     */
    public function run()
    {

        $eventClasses = $this->getEventClasses();

        if (!count($eventClasses))
            return $data;

        $mdata = $data;
        foreach ($eventClasses as $eventClass) {

            $classkey = $this->getModuleKeyByEvent($eventClass);
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'smileySave');

            /**
             * @var \fpcm\events\event
             */
            $module = new $eventClass();

            if (!$this->is_a($module))
                continue;

            $mdata = $module->run($mdata);
        }

        if (!$mdata)
            return $data;

        return $mdata;
    }

}
