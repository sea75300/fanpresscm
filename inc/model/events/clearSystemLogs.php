<?php
    /**
     * Module-Event: clearSystemLogs
     * 
     * Event wird ausgeführt, wenn über eines der Systemlogs über den Button "Leeren" aufgeräumt wird
     * Parameter: void
     * Rückgabe: void
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: clearSystemLogs
     * 
     * Event wird ausgeführt, wenn über eines der Systemlogs über den Button "Leeren" aufgeräumt wird
     * Parameter: void
     * Rückgabe: void
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class clearSystemLogs extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn über eines der Systemlogs über den Button "Leeren" aufgeräumt wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return;
            
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'clearSystemLogs');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $module->run();
            }
        }
    }
