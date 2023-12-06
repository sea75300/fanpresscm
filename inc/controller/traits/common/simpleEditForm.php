<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Simple edit form trait
 * 
 * @package fpcm\controller\traits\common
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait simpleEditForm {

    /**
     * 
     * @param string $name
     * @return bool
     */
    protected function assignFields(array $fields)
    {
        $res = new \fpcm\events\view\extendFieldsResult;
        $res->fields = $fields;
        
        $event = $this->events->trigger('view\extendFields', $res);
        if (!$event->getSuccessed() || !$event->getContinue()) {
            $this->view->assign('formFields', []);
            return true;
        }

        $this->view->assign('formFields', $event->getData());
        return true;
    }

    protected function getViewPath() : string
    {
        return 'components/simpleEditForm';
    }

}
