<?php
    /**
     * Module-Event: thumbnailCreate
     * 
     * Event wird ausgeführt, wenn neuer Thumbnial für ein Bild erzeugt wird
     * Parameter: Objekt vom Type fpcm\model\files\image
     * Rückgabe: void
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: thumbnailCreate
     * 
     * Event wird ausgeführt, wenn neuer Thumbnial für ein Bild erzeugt wird
     * Parameter: Objekt vom Type fpcm\model\files\image
     * Rückgabe: void
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class thumbnailCreate extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn neuer Thumbnial für ein Bild erzeugt wird
         * @param fpcm\model\files\image $data
         * @return void
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'thumbnailCreate');
                
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