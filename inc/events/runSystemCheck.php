<?php

/**
 * Module-Event: runSystemCheck
 * 
 * Event wird ausgeführt, wenn Systemcheck durchgführt wird
 * Parameter: array mit System-Check-Optionen
 * Rückgabe: array mit System-Check-Optionen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: runSystemCheck
 * 
 * Event wird ausgeführt, wenn Systemcheck durchgführt wird
 * Parameter: array mit System-Check-Optionen
 * Rückgabe: array mit System-Check-Optionen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class runSystemCheck extends \fpcm\events\abstracts\event {

    /**
     * wird ausgeführt, wenn Systemcheck durchgführt wird
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
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'runSystemCheck');

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
