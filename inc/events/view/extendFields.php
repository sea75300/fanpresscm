<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\view;

/**
 * Module-Event: extendFields
 * 
 * Extends simepla editor form fields
 * Parameter: array List with toolbar elements
 * Rückgabe: array List with toolbar elements
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 5.1.0-a1
 */
final class extendFields extends \fpcm\events\abstracts\event {

    /**
     * Event data
     * @var extendFieldsResult
     */
    protected $data;

    /**
     * Execute event
     * @return \fpcm\events\view\extendFieldsResult
     */
    public function run() : \fpcm\module\eventResult
    {
        $this->data->area = \fpcm\classes\tools::getAreaName('form');
        
        $eventClasses = $this->getEventClasses();

        $base = $this->getEventClassBase();
        $evRes = $this->toEventResult($this->data);
        
        if (!count($eventClasses)) {
            $evRes->setSuccessed(true);
            $evRes->setContinue(true);
            return $evRes;
        }        

        foreach ($eventClasses as $class) {
            
            if (!class_exists($class)) {
                trigger_error(sprintf('Undefined event class "%s"', $class));
                continue;
            }

            /* @var \fpcm\module\event $module */
            $module = new $class($evRes);
            if (!$this->is_a($module) || !method_exists($module, $this->data->area)) {
                continue;
            }

            $evRes = call_user_func([$module, $this->data->area]);
            if (empty($evRes)) {
                trigger_error(sprintf('The return value of the module event "%s" cannot be empty. An instance of "\fpcm\module\eventResult" is required at least.', $module::class), E_USER_ERROR);
                $evRes = $this->toEventResult($this->data);
            }

        }

        return $evRes;
    }

}
