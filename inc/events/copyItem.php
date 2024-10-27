<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: copyItem
 *
 * Event is executed if copy buttons has module name in copy parameters
 * Parameter: void
 * Rückgabe: void
 *
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.2.3-dev
 */
final class copyItem extends abstracts\event {

    /**
     * Executes the event
     * @return null|\fpcm\model\files\logfileResult
     */
    public function run() {

        $obj = new \fpcm\module\module($this->data['key']);
        if (!$obj->isInstalled() || !$obj->isActive()) {
            return (new \fpcm\module\eventResult)->setData(null);
        }

        $class = \fpcm\module\module::getEventNamespace($obj->getKey(), 'copyItem');
        if (!class_exists($class)) {
            trigger_error('Undefined event class '.$class);
            return (new \fpcm\module\eventResult)->setData(null);
        }
        
        unset($this->data['key']);

        $eventOb = new $class($this->data);
        $return = $this->is_a($eventOb) ? $eventOb->run() : null;

        return $this->toEventResult($return);
    }

}
