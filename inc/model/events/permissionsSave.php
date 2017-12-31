<?php
    /**
     * Module-Event: permissionsSave
     * 
     * Event wird ausgeführt, wenn Berechtigungsset in Datenbank gespeichert wird
     * Parameter: array Berechtigungsset
     * Rückgabe: array Berechtigungsset
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: permissionsSave
     * 
     * Event wird ausgeführt, wenn Berechtigungsset in Datenbank gespeichert wird
     * Parameter: array Berechtigungsset
     * Rückgabe: array Berechtigungsset
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class permissionsSave extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn Berechtigungsset in Datenbank gespeichert wir
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'permissionsSave');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }
            
            if (!$mdata) return $data;
            
            return $mdata;
            
        }
    }