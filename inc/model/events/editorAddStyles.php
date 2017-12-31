<?php
    /**
     * Module-Event: editorAddStyles
     * 
     * Event wird ausgeführt, wenn im Artikel-Editor die Liste mit CSS-Klassen zur Nutzung für Bilder, Links, etc. geladen wird
     * Parameter: array mit CSS-Klassen aus den Systemeinstellungen
     * Rückgabe: array mit CSS-Klassen gemäß vorhandener Einträge
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: editorAddStyles
     * 
     * Event wird ausgeführt, wenn im Artikel-Editor die Liste mit CSS-Klassen zur Nutzung für Bilder, Links, etc. geladen wird
     * Parameter: array mit CSS-Klassen aus den Systemeinstellungen
     * Rückgabe: array mit CSS-Klassen gemäß vorhandener Einträge
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class editorAddStyles extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn im Artikel-Editor die Liste mit CSS-Klassen zur Nutzung für Bilder, Links, etc. geladen wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'editorAddStyles');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }
            
            array_shift($mdata);
            
            return $mdata;
            
        }
    }