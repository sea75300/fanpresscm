<?php
    /**
     * Module-Event: articleReplaceEditorPlugin
     * 
     * Event wird ausgeführt, wenn Artikel-Editor-Plugin geladen werden soll
     * Parameter: void
     * Rückgabe: Objekt welche von der Klasse fpcm\model\abstracts\articleEditor abgeleitet wurde
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.1.0
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: articleReplaceEditorPlugin
     * 
     * Event wird ausgeführt, wenn Artikel-Editor-Plugin geladen werden soll
     * Parameter: void
     * Rückgabe: Objekt welche von der Klasse fpcm\model\abstracts\articleEditor abgeleitet wurde
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     * @since FPCM 3.1.0
     */
    final class articleReplaceEditorPlugin extends \fpcm\model\abstracts\event {

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
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'articleReplaceEditorPlugin');

            /**
             * @var \fpcm\model\abstracts\articleEditor
             */
            $module = new $eventClass();

            if (!$this->is_a($module)) return false;

            $data = $module->run();
            
            if (!is_object($data)) return false;
            
            return $data;
            
        }
    }