<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\view;

/**
 * Module-Event: extendToolbar
 * 
 * Event extends main toolbar, calls a function "extendToolbarControllerName"
 * Parameter: array List with toolbar elements
 * Rückgabe: array List with toolbar elements
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 4.4
 */
final class extendToolbar extends \fpcm\events\abstracts\event {

    /**
     * Event data
     * @var extendToolbarResult
     */
    protected $data;

    /**
     * Execute event
     * @return \fpcm\events\view\extendToolbarResult
     */
    public function run() : extendToolbarResult
    {
        $this->data->area = \fpcm\classes\tools::getAreaName('toolbar');
        
        $eventClasses = $this->getEventClasses();
        if (!count($eventClasses)) {
            return $this->data;
        }

        $base = $this->getEventClassBase();
        $eventResult = $this->data->buttons;

        foreach ($eventClasses as $class) {

            if (!class_exists($class)) {
                trigger_error('Undefined event class '.$class);
                continue;
            }
            
            /* @var \fpcm\module\event $module */
            $module = new $class($eventResult);
            if (!$this->is_a($module) || !method_exists($module, $this->data->area)) {
                continue;
            }

            $eventResult = call_user_func([$module, $this->data->area]);
            if (!is_array($eventResult)) {
                trigger_error('Invalid data type. Returned data type must be an array for '.$base);
                return $this->data;
            }

            $this->data->buttons = $eventResult;
        }

        return $this->data;
    }

}
