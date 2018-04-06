<?php

/**
 * Module-Event: controllerRedirect
 * 
 * Event wird ausgeführt, wenn ein Controller-Redirect via PHP durchgeführt wird
 * Parameter: string Redirect-String inkl. Controller und Parametern
 * Rückgabe: string Redirect-String inkl. Controller und Parametern
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: controllerRedirect
 * 
 * Event wird ausgeführt, wenn ein Controller-Redirect via PHP durchgeführt wird
 * Parameter: string Redirect-String inkl. Controller und Parametern
 * Rückgabe: string Redirect-String inkl. Controller und Parametern
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class controllerRedirect extends \fpcm\events\abstracts\event {

    /**
     * wird ausgeführt, wenn ein Controller-Redirect via PHP durchgeführt wird
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
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'controllerRedirect');

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
