<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: clearSystemLog
     * 
     * Event wird ausgeführt, wenn über eines der Systemlogs über den Button "Leeren" aufgeräumt wird
     * Parameter: string Log-ID
     * Rückgabe: bool true wenn Log geleert
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     * @since FPCM 3.3
     */
    final class clearSystemLog extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn über eines der Systemlogs über den Button "Leeren" aufgeräumt wird
         * @param string $data Log-ID
         * @return bool true wenn Log geleert
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return;

            $return = true;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'clearSystemLog');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $return =$return && $module->run($data);
            }

            return $return;
        }
    }
