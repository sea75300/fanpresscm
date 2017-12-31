<?php
    /**
     * Module-Event: articlePrepareDataSave
     * 
     * Event wird ausgeführt, wenn Artikel gespeichert wird
     * Parameter: array Arary mit Artikel-Inhalt und Parametern zur Berenigung
     * Rückgabe: array Arary mit Artikel-Inhalt und Parametern zur Berenigung
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.4
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: articlePrepareDataSave
     * 
     * Event wird ausgeführt, wenn Artikel gespeichert wird
     * Parameter: array Arary mit Artikel-Inhalt und Parametern zur Berenigung
     * Rückgabe: array Arary mit Artikel-Inhalt und Parametern zur Berenigung
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     * @since FPCM 3.4
     */
    final class articlePrepareDataSave extends \fpcm\model\abstracts\event {

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
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'articlePrepareDataSave');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }

            if (!$mdata || !is_array($mdata) || !isset($eventData['content']) || !isset($eventData['searchParams']) || !is_array($eventData['searchParams'])) {
                return $data;
            }

            return $mdata;
            
        }
    }