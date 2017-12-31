<?php
    /**
     * Module-Event: apiCallFunction
     * 
     * Event wird ausgeführt, wenn fpcmAPI::__call oder fpcmAPI::__callStatic aufgerufen wird
     * Parameter: array mit Funktionsname und Funktionsparametern
     * Rückgabe: Mixed, Rückgabewerte der Funktion
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.1.5
     */
    namespace fpcm\model\events;

    /**
     * Module-Event: apiCallFunction
     * 
     * Event wird ausgeführt, wenn fpcmAPI::__call oder fpcmAPI::__callStatic aufgerufen wird
     * Parameter: array mit Funktionsname und Funktionsparametern
     * Rückgabe: Mixed, Rückgabewerte der Funktion
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm/model/events
     * @since FPCM 3.1.5
     */
    final class apiCallFunction extends \fpcm\model\abstracts\event {

        /**
         * wird ausgeführt, wenn Cache über "Cache leeren" Button geleert wird
         * @param array $data
         * @return mixed
         */
        public function run($data = null) {
            
            $functionData = explode('_', $data['name'], 3);
            
            if (!isset($functionData[0]) || !isset($functionData[1]) || !isset($functionData[2])) {
                trigger_error('Malformed function name data given: "'.$data['name'].'"');
                return false;
            }
            
            $vendorKey    = $functionData[0];
            $moduleKey    = $functionData[1];
            $functionName = $functionData[2];
            
            $classFile = \fpcm\classes\baseconfig::$moduleDir.$vendorKey.'/'.$moduleKey.'/events/apiCallFunction.php';

            if (!file_exists($classFile)) {
                trigger_error('Event class "apiCallFunction" not found in '.\fpcm\model\files\ops::removeBaseDir($classFile, true));
                return false;
            }

            $classkey = $vendorKey.'/'.$moduleKey;
            $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, 'apiCallFunction');

            /**
             * @var \fpcm\model\abstracts\event
             */
            $module = new $eventClass();

            if (!$this->is_a($module)) {
                return false;
            }
            
            $data['name'] = $functionName;
            return $module->run($data);
        }
    }
