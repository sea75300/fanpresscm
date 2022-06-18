<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\modules;

/**
 * Module-Event: configure
 * 
 * Event wird ausgeführt, wenn "Modul konfigurieren" aufgerufen wird
 * Parameter: void
 * Rückgabe: voiarray
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 4
 */
class configure extends \fpcm\events\abstracts\eventReturnArray {

    /**
     * Executes a certain event
     * @return array
     */
    public function run()
    {
        $class = \fpcm\module\module::getEventNamespace($this->data, $this->getEventClassBase());
        if (!class_exists($class)) {
            return true;
        }

        $obj = new $class($this->data);
        if (!$this->is_a($obj)) {
            return false;
        }

        return $obj->run();
    }

}
