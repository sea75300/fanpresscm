<?php
    /**
     * Module-Event: templateSave
     * 
     * Event wird ausgeführt, wenn Template gespeichert wird
     * Parameter: array mit Dateiname in "file" und HTML-Code des Templates "content"
     * Rückgabe: array mit Dateiname in "file" und HTML-Code des Templates "content", "content" wird genutzt zum Speichern
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: templateSave
     * 
     * Event wird ausgeführt, wenn Template gespeichert wird
     * Parameter: array mit Dateiname in "file" und HTML-Code des Templates "content"
     * Rückgabe: array mit Dateiname in "file" und HTML-Code des Templates "content", "content" wird genutzt zum Speichern
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class templateSave extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn Template gespeichert wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'templateSave');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }
            
            if (!isset($mdata['content'])) return $data;
            
            return $mdata;
            
        }
    }