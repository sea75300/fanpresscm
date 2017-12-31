<?php
    /**
     * Module-Event: emailSubmit
     * 
     * Event wird ausgeführt, wenn über fpcm\classes\email-Klasse eine E-mail versendet wird
     * Parameter: array mit E-Mail-Daten
     * Rückgabe: array mit E-Mail-Daten
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: emailSubmit
     * 
     * Event wird ausgeführt, wenn über fpcm\classes\email-Klasse eine E-mail versendet wird
     * Parameter: array mit E-Mail-Daten
     * Rückgabe: array mit E-Mail-Daten
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class emailSubmit extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn über fpcm\classes\email-Klasse eine E-mail versendet wird
         * @param array $data
         * @return array
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'emailSubmit');
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }
            
            if (!$mdata || !isset($mdata['maildata']) || !isset($mdata['headers'])) return $data;
            
            return $mdata;
            
        }
    }