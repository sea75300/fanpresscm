<?php
    /**
     * Module-Event: articleSave
     * 
     * Event wird ausgeführt, wenn Artikel gespeichert wird
     * Parameter: array Artikel-Daten-Array
     * Rückgabe: array Artikel-Daten-Array
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: articleSave
     * 
     * Event wird ausgeführt, wenn Artikel gespeichert wird
     * Parameter: array Artikel-Daten-Array
     * Rückgabe: array Artikel-Daten-Array
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class articleSave extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn Artikel gespeichert wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'articleSave');
                
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