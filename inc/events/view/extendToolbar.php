<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\view;

/**
 * Module-Event: extendToolbar
 * 
 * Event extends main toolbar
 * Parameter: array Liste mit CSS-Dateien
 * Rückgabe: array Liste mit CSS-Dateien
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class extendToolbar extends \fpcm\events\abstracts\event {

    public function run()
    {
        $eventClasses = $this->getEventClasses();
        if (!count($eventClasses)) {
            return $this->data;
        }

        $fnName = 'extendToolbar'. ucfirst(str_replace('/', '', \fpcm\classes\http::getModuleString()));

        $eventResult = $this->data;

        foreach ($eventClasses as $class) {

            if (!class_exists($class)) {
                trigger_error('Undefined event class '.$class);
                continue;
            }
            
            /* @var \fpcm\module\event $module */
            $module = new $class($eventResult);
            if (!$this->is_a($module) || !method_exists($module, $fnName)) {
                continue;
            }

            $eventResult = call_user_func([$module, $fnName]);
        }    
        
        return $eventResult;
    }

}
