<?php
    /**
     * Module-Event: editorGetFileList
     * 
     * Event wird ausgeführt, wenn im Artikel-Editor die Liste vorhandener Upload geladen wird
     * Parameter: array mit Daten im Dateiindex, "label" enthält das Label für die Dateilist je nach aktivem Editor und "files"
     * die eigentliche Dateiliste
     * Rückgabe: array Liste mit Dateien in der obigen Form mit "label" und "files"
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: editorGetFileList
     * 
     * Event wird ausgeführt, wenn im Artikel-Editor die Liste vorhandener Upload geladen wird
     * Parameter: array mit Daten im Dateiindex, "label" enthält das Label für die Dateilist je nach aktivem Editor und "files"
     * die eigentliche Dateiliste
     * Rückgabe: array Liste mit Dateien in der obigen Form mit "label" und "files"
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class editorGetFileList extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn im Artikel-Editor die Liste vorhandener Upload geladen wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'editorGetFileList');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }
            
            array_shift($mdata);
            
            if (!isset($mdata['files'])) return $data;
            
            return $mdata;
            
        }
    }