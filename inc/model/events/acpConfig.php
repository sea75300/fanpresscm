<?php
    /**
     * Module-Event: acpConfig
     * 
     * Event wird ausgeführt, wenn wenn Benutzer in Modulmanager auf "Modul konfigurieren" klickt
     * Parameter: void
     * Rückgabe: void
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: acpConfig
     * 
     * Event wird ausgeführt, wenn wenn Benutzer in Modulmanager auf "Modul konfigurieren" klickt
     * Parameter: void
     * Rückgabe: void
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     */
    final class acpConfig extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn wenn Benutzer in Modulmanager auf "Modul konfigurieren" klickt
         * @param string $module
         * @return boolean
         */
        public function run($module = null) {

            if (!in_array($module, $this->activeModules)) {
                trigger_error("Request for acpConfig event of disabled module '{$module}'!");

                $view = new \fpcm\model\view\error();
                $view->setMessage("The module '{$module}' is not enabled for execution!");
                $view->render();
                die();
            }

            $className = \fpcm\model\abstracts\module::getModuleEventNamespace($module, 'acpConfig');
            
            /**
             * @var \fpcm\model\abstracts\event
             */
            $module = new $className();

            if ($this->is_a($module)) {
                $module->run(null);
                return true;
            }

            return false;
        }
    }
