<?php
    /**
     * Module-Event: articlesPrepareSearch
     * 
     * Event wird ausgeführt, wenn Artikel-Suche durchgeführt wird
     * Parameter: array mit Liste der Suchparameter aus dem Suchformular
     * Rückgabe: array mit Liste der Suchparameter aus dem Suchformular
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.1
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: articlesPrepareSearch
     * 
     * Event wird ausgeführt, wenn Artikel-Suche durchgeführt wird
     * Parameter: array mit Liste der Suchparameter aus dem Suchformular
     * Rückgabe: array mit Liste der Suchparameter aus dem Suchformular
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     * @since FPCM 3.1
     */
    final class articlesPrepareSearch extends \fpcm\model\abstracts\event {

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
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'articlesPrepareSearch');
                
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