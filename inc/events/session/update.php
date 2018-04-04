<?php

/**
 * Module-Event: sessionUpdate
 * 
 * Event wird ausgeführt, wenn Daten der Session aktualisiert werden
 * Parameter: array Session-Daten
 * Rückgabe: array Session-Daten
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\session;

/**
 * Module-Event: sessionUpdate
 * 
 * Event wird ausgeführt, wenn Daten der Session aktualisiert werden
 * Parameter: array Session-Daten
 * Rückgabe: array Session-Daten
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class sessionUpdate extends \fpcm\events\abstracts\event {

    /**
     * wird ausgeführt, wenn Daten der Session aktualisiert werden
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
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'sessionUpdate');

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
