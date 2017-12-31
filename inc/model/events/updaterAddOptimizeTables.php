<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: updaterAddOptimizeTables
     * 
     * Event wird ausgeführt, wenn Tabellenliste erzeugt wird für Optimierung der Datenbank-Tabellen beim Update
     * Parameter: array mit Liste der Tabellen
     * Rückgabe: array mit Liste der Tabellen
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     * @since FPCM 3.3
     */
    final class updaterAddOptimizeTables extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn Tabellenliste erzeugt wird für Optimierung der Datenbank-Tabellen beim Update
         * @param array $data
         * @return array
         */
        public function run($data = null) {

            $eventClasses = $this->getEventClasses();

            if (!count($eventClasses)) {
                return $data;
            }
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'updaterAddOptimizeTables');
                
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