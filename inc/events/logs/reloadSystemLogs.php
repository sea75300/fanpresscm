<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\logs;

/**
 * Module-Event: reloadSystemLogs
 * 
 * Event wird ausgeführt, wenn Systemlogs via AJAX neu geladen werden
 * Parameter: void
 * Rückgabe: void
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class reloadSystemLogs extends \fpcm\events\abstracts\event {

    /**
     * wird ausgeführt, wenn Systemlogs via AJAX neu geladen werden
     * @param void $data
     * @return void
     */
    public function run()
    {

        $eventClasses = $this->getEventClasses();

        if (!count($eventClasses))
            return;

        foreach ($eventClasses as $eventClass) {

            $classkey = $this->getModuleKeyByEvent($eventClass);
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'reloadSystemLogs');

            /**
             * @var \fpcm\events\event
             */
            $module = new $eventClass();

            if (!$this->is_a($module))
                continue;

            $module->run();
        }
    }

}
