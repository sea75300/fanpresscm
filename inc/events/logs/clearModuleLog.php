<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\logs;

/**
 * Module-Event: clearModuleLog
 * 
 * Event to clear an module log file
 * Parameter: array with module key and log name
 * Rückgabe: \fpcm\model\files\logfileResult
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events/logs
 */
final class clearModuleLog extends \fpcm\events\abstracts\event {

    /**
     * Executes the event
     * @return null|\fpcm\model\files\logfileResult
     */
    public function run() {

        $obj = new \fpcm\module\module($this->data['key']);
        if (!$obj->isInstalled() || !$obj->isActive()) {
            return null;
        }
        
        $class = \fpcm\module\module::getEventNamespace($obj->getKey(), 'clearModuleLog');
        if (!class_exists($class)) {
            trigger_error('Undefined event class '.$class);
            return null;
        }

        $eventOb = new $class($this->data['log']);
        return $this->is_a($eventOb) ? $eventOb->run() : true;
    }

    
}
