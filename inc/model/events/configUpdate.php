<?php
    /**
     * Module-Event: configUpdate
     * 
     * Event wird ausgeführt, wenn die Systemconfiguration aktualisiert wird
     * Parameter: array Daten der NEUEN System-Konfiguration aus Systemeinstellungen
     * Rückgabe: array Daten der System-Konfiguration
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: configUpdate
     * 
     * Event wird ausgeführt, wenn die Systemconfiguration aktualisiert wird
     * Parameter: array Daten der NEUEN System-Konfiguration aus Systemeinstellungen
     * Rückgabe: array Daten der System-Konfiguration
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class configUpdate extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn die Systemconfiguration aktualisiert wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'configUpdate');
                
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