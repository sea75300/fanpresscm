<?php
    /**
     * Module-Event: articleCreateTweet
     * 
     * Event wird ausgeführt, wenn Artikel gespeichert wird
     * Parameter: \fpcm\model\articles\article Artikel, aus dem ein Tweet erzeugt werden soll
     * Rückgabe: \fpcm\model\articles\article Artikel, aus dem Tweet erzeugt werden soll
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: articleCreateTweet
     * 
     * Event wird ausgeführt, wenn Artikel gespeichert wird
     * Parameter: \fpcm\model\articles\article Artikel, aus dem ein Tweet erzeugt werden soll
     * Rückgabe: \fpcm\model\articles\article Artikel, aus dem Tweet erzeugt werden soll
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class articleCreateTweet extends \fpcm\model\abstracts\event {

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
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'articleCreateTweet');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }
            
            if (!is_a($mdata, '\\fpcm\\model\\articles\\article')) return $data;
            
            return $mdata;
            
        }
    }