<?php
    /**
     * Module-Event: permissionsCheck
     * 
     * Event wird ausgeführt, wenn Berechtigungen überprüft werden sollen
     * Parameter: array mit zu prüfenden Berechtigungen
     * Rückgabe: array mit zu prüfenden Berechtigungen
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: permissionsCheck
     * 
     * Event wird ausgeführt, wenn Berechtigungen überprüft werden sollen
     * Parameter: array mit zu prüfenden Berechtigungen
     * Rückgabe: array mit zu prüfenden Berechtigungen
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class permissionsCheck extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn Berechtigungen überprüft werden sollen
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'permissionsCheck');
                
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
