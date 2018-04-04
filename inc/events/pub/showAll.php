<?php

/**
 * Module-Event: publicShowAll
 * 
 * Event wird ausgeführt, bevor Inhalt in public-Controller showAll ausgegeben wird
 * Parameter: array Daten für Ausgabe
 * Rückgabe: array Daten für Ausgabe
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\pub;

/**
 * Module-Event: publicShowAll
 * 
 * Event wird ausgeführt, bevor Inhalt in public-Controller showAll ausgegeben wird
 * Parameter: array Daten für Ausgabe
 * Rückgabe: array Daten für Ausgabe
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class publicShowAll extends \fpcm\events\abstracts\event {

    /**
     * wird ausgeführt, bevor Inhalt in public-Controller showAll ausgegeben wird
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
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'publicShowAll');

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
