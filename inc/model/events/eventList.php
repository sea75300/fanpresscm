<?php
    /**
     * FanPress CM event list model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * FanPress CM event list model
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class eventList {

        /**
         * Run event $eventName with params $dataParams
         * @param string $eventName
         * @param mixed $dataParams
         * @return mixed
         */
        public function runEvent($eventName, $dataParams = null) {

            if (!\fpcm\classes\baseconfig::dbConfigExists() || \fpcm\classes\baseconfig::installerEnabled()) {
                return $dataParams;
            }
            
            if (!file_exists(\fpcm\classes\baseconfig::$incDir.'model/events/'.$eventName.'.php')) {
                trigger_error('ERROR: Undefined event called: '.$eventName);
                return $dataParams;
            }
            
            /**
             * @var \fpcm\model\events\event
             */
            $eventClassName = "\\fpcm\\model\\events\\".$eventName;
            $event  = new $eventClassName();
            
            if (!$event->checkPermissions()) {
                return $dataParams;
            }

            return $event->run($dataParams);            
        }
        
        /**
         * Gibt Liste mit Events des Systems zurück
         * @return array
         */
        public function getSystemEventList() {

            $list = [];
            foreach (glob(\fpcm\classes\baseconfig::$incDir.'model/events/*.php') as &$file) {                
                if ($file == __FILE__) continue;                
                $list[]  = basename($file, '.php');
            }
            
            return $list;
            
        }

    }
