<?php
    /**
     * Module-Event: fileUploadPhpAfter
     * 
     * Event wird ausgeführt, wenn Liste von Cronjob-Dateien für asynchrone Ausführung geladen wird
     * Parameter: array mit Dateiliste von Cronjob-Classen
     * Rückgabe: array mit Dateiliste von Cronjob-Classen
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: fileUploadPhpAfter
     * 
     * Event wird ausgeführt, wenn Liste von Cronjob-Dateien für asynchrone Ausführung geladen wird
     * Parameter: array mit Dateiliste von Cronjob-Classen
     * Rückgabe: array mit Dateiliste von Cronjob-Classen
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class fileUploadPhpAfter extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, nachdem der PHP-Uploader ausgeführt wurde
         * @param array $data
         * @return void
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'fileUploadPhpAfter');
                
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