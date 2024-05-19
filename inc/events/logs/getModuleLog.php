<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\logs;

/**
 * Module-Event: getModuleLog
 * 
 * Event to fetch an module log file
 * Parameter: array with module key and log name
 * Rückgabe: \fpcm\model\files\logfileResult
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events\logs
 */
final class getModuleLog extends \fpcm\events\abstracts\event {

    /**
     * Executes the event
     * @return null|\fpcm\model\files\logfileResult
     */
    public function run() {

        $obj = new \fpcm\module\module($this->data['key']);
        if (!$obj->isInstalled() || !$obj->isActive()) {
            return (new \fpcm\module\eventResult)->setData(null);
        }
        
        $class = \fpcm\module\module::getEventNamespace($obj->getKey(), 'logs\\getModuleLog');
        if (!class_exists($class)) {
            trigger_error('Undefined event class '.$class);
            return (new \fpcm\module\eventResult)->setData(null);
        }

        $eventOb = new $class($this->data['log'], $this->data['term']);
        $return = $this->is_a($eventOb) ? $eventOb->run() : null;
        
        return $this->toEventResult($return);
    }

    
}
