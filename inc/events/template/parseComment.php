<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\template;

/**
 * Module-Event: parseTemplateComment
 * 
 * Event wird ausgeführt, wenn Kommentar-Formular-Template geparst wird
 * Parameter: array mit Platzhaltern und zugewiesenen Daten
 * Rückgabe: array mit Platzhaltern und zugewiesenen Daten
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class parseTemplateComment extends \fpcm\events\abstracts\event {

    /**
     * wird ausgeführt, wenn Kommentar-Template geparst wird
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
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'parseTemplateComment');

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
