<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\modules;

/**
 * Module-Event: installAfter
 * 
 * Event wird ausgeführt, wenn nachdem Modul installiert wurde
 * Parameter: void
 * Rückgabe: void
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 4
 */
class installAfter extends \fpcm\events\abstracts\event {

    /**
     * Executes a certain event
     * @return bool
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
