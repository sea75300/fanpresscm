<?php

/**
 * Module-Event: parseTemplate
 * 
 * Event wird ausgeführt, wenn ein Template geparst wird das keine eigene Parse-Funktion besitzt
 * Parameter: array Liste mit Artikel-Platzhaltern und zugewiesenen Daten
 * Rückgabe: array Liste mit Platzhaltern und Daten
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\template;

/**
 * Module-Event: parseTemplate
 * 
 * Event wird ausgeführt, wenn ein Template geparst wird das keine eigene Parse-Funktion besitzt
 * Parameter: array Liste mit Artikel-Platzhaltern und zugewiesenen Daten
 * Rückgabe: array Liste mit Platzhaltern und Daten
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class parseTemplate extends \fpcm\events\abstracts\event {

    /**
     * wird ausgeführt, wenn ein Template geparst wird das keine eigene Parse-Funktion besitzt
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
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'parseTemplate');

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
