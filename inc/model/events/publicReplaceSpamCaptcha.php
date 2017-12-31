<?php
    /**
     * Module-Event: publicReplaceSpamCaptcha
     * 
     * Event wird ausgeführt, wenn Spam-Captcha in Kommentar-Formular initialisiert werden soll
     * Parameter: void
     * Rückgabe: Objekt welche von der Klasse fpcm\model\abstracts\spamCaptcha abgeleitet wurde
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: publicReplaceSpamCaptcha
     * 
     * Event wird ausgeführt, wenn Spam-Captcha in Kommentar-Formular initialisiert werden soll
     * Parameter: void
     * Rückgabe: Objekt welche von der Klasse fpcm\model\abstracts\spamCaptcha abgeleitet wurde
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class publicReplaceSpamCaptcha extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn Spam-Captcha in Kommentar-Formular initialisiert werden soll
         * @param void $data
         * @return \model\abstracts\spamCaptcha
         */
        public function run($data = null) {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return false;
            
            $eventClass = array_shift($eventClasses);
                
            $classkey = $this->getModuleKeyByEvent($eventClass);                
            if (!in_array($classkey, $this->activeModules)) return false;

            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'publicReplaceSpamCaptcha');

            /**
             * @var \fpcm\model\abstracts\event
             */
            $module = new $eventClass();

            if (!$this->is_a($module)) return false;

            $data = $module->run();
            
            if (!is_object($data)) return false;
            
            return $data;
            
        }
    }