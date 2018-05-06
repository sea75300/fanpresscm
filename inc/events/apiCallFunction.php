<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: apiCallFunction
 * 
 * Event wird ausgeführt, wenn fpcmAPI::__call oder fpcmAPI::__callStatic aufgerufen wird
 * Parameter: array mit Funktionsname und Funktionsparametern
 * Rückgabe: Mixed, Rückgabewerte der Funktion
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 * @since FPCM 3.1.5
 */
final class apiCallFunction extends \fpcm\events\abstracts\event {

    /**
     * 
     * @return boolean|mixed
     */
    public function run()
    {
        $functionData = explode('_', $this->data['name'], 3);

        if (!isset($functionData[0]) || !isset($functionData[1]) || !isset($functionData[2])) {
            trigger_error('Malformed function name data given: "' . $this->data['name'] . '"');
            return false;
        }

        $vendorKey = $functionData[0];
        $moduleKey = $functionData[1];
        $functionName = $functionData[2];

        $classFile = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $vendorKey . DIRECTORY_SEPARATOR . $moduleKey . DIRECTORY_SEPARATOR . 'events' . DIRECTORY_SEPARATOR . 'apiCallFunction.php');
        if (!file_exists($classFile)) {
            trigger_error('Event class "apiCallFunction" not found in ' . \fpcm\model\files\ops::removeBaseDir($classFile, true));
            return false;
        }

        $classkey = $vendorKey . '/' . $moduleKey;
        $eventClass = \fpcm\module\module::getEventNamespace($classkey, $this->getEventClassBase());

        $this->data['name'] = $functionName;

        /**
         * @var \fpcm\events\event
         */
        $module = new $eventClass($this->data);

        if (!$this->is_a($module)) {
            return false;
        }

        return $module->run();
    }

}
