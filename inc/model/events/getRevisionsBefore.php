<?php
    /**
     * Module-Event: getRevisionsBefore
     * 
     * Event wird ausgeführt, bevor die Revisionsliste abgerufen wird
     * Parameter: array Liste von Revisionsdateien für des Artikels
     * Rückgabe: array Liste der Revisionsdateien
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: getRevisionsBefore
     * 
     * Event wird ausgeführt, bevor die Revisionsliste abgerufen wird
     * Parameter: array Liste von Revisionsdateien für des Artikels
     * Rückgabe: array Liste der Revisionsdateien
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class getRevisionsBefore extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, bevor die Revisionsliste abgerufen wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'getRevisionsBefore');
                
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