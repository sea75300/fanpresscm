<?php
    /**
     * Module-Event: dashboardContainersLoad
     * 
     * Event wird ausgeführt, wenn Liste von Dashboard-Container-Klassen geladen wird
     * Parameter: array mit Liste von Container-Klassen
     * Rückgabe: array mit Liste von Container-Klassen, zurückgegebene Klassen müssen müssen das Interface "\fpcm\model\abstracts\dashcontainer" implementieren!
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: dashboardContainersLoad
     * 
     * Event wird ausgeführt, wenn Liste von Dashboard-Container-Klassen geladen wird
     * Parameter: array mit Liste von Container-Klassen
     * Rückgabe: array mit Liste von Container-Klassen, zurückgegebene Klassen müssen müssen das Interface "\fpcm\model\abstracts\dashcontainer" implementieren!
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class dashboardContainersLoad extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn Liste von Dashboard-Container-Klassen geladen wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'dashboardContainersLoad');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }
            
            if (!isset($mdata)) return $data;
            
            return $mdata;
            
        }
    }