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
 * @copyright (c) 2011-2022, Stefan Seehafer
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
            return (new \fpcm\module\eventResult)->setData(true);
        }

        $obj = new $class($this->data);
        if (!$this->is_a($obj)) {
            return (new \fpcm\module\eventResult)->setData(false);
        }

        return (new \fpcm\module\eventResult)->setData($obj->run());
    }

}
