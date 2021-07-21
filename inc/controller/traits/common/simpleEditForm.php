<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Simple edit form trait
 * 
 * @package fpcm\controller\traits\common
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2021, Stefan Seehafer
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
        $this->view->assign('formFields', $fields);
        return true;
    }

    protected function getViewPath() : string
    {
        return 'components/simpleEditForm';
    }

}

?>