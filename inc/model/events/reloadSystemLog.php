<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: reloadSystemLog
     * 
     * Event wird ausgeführt, wenn Systemlogs via AJAX neu geladen werden
     * Parameter: string Log-ID
     * Rückgabe: void
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     * @since FPCM 3.3
     */
    final class reloadSystemLog extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn Systemlogs via AJAX neu geladen werden
         * @param void $data
         * @return void
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return;
            
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'reloadSystemLog');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $module->run($data);
            }
        }
    }
