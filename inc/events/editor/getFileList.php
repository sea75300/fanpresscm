<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\editor;

/**
 * Module-Event: getFileList
 * 
 * Event wird ausgeführt, wenn im Artikel-Editor die Liste vorhandener Upload geladen wird
 * Parameter: array mit Daten im Dateiindex, "label" enthält das Label für die Dateilist je nach aktivem Editor und "files"
 * die eigentliche Dateiliste
 * Rückgabe: array Liste mit Dateien in der obigen Form mit "label" und "files"
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class getFileList extends \fpcm\events\abstracts\event {

    /**
     * wird ausgeführt, wenn im Artikel-Editor die Liste vorhandener Upload geladen wird
     * @param array $data
     * @return array
     */
    public function run()
    {

        $eventClasses = $this->getEventClasses();
        if (!count($eventClasses)) {
            return $data;            
        }

        $mdata = $data;
        foreach ($eventClasses as $eventClass) {

            $classkey = $this->getModuleKeyByEvent($eventClass);
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'getFileList');

            /**
             * @var \fpcm\events\event
             */
            $module = new $eventClass();

            if (!$this->is_a($module))
                continue;

            $mdata = $module->run($mdata);
        }

        array_shift($mdata);

        if (!isset($mdata['files']))
            return $data;

        return $mdata;
    }

}
