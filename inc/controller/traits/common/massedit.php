<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\common;

/**
 * Mass editing trait
 * 
 * @package fpcm\controller\traits\common\massedit
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait massedit {

    protected $yesNoChangeList = [
        'GLOBAL_NOCHANGE_APPLY' => -1,
        'GLOBAL_YES' => 1,
        'GLOBAL_NO' => 0
    ];


    /**
     * 
     * @return bool
     */
    protected function assignNoChangeYesNo()
    {
        $this->view->assign('yesNoChangeList', $this->yesNoChangeList);
        
        return true;
    }

    /**
     * 
     * @param string $name
     * @return bool
     */
    protected function assignPageToken($module)
    {
        $this->view->addAjaxPageToken($module.'/massedit');
        return true;
    }

    /**
     * 
     * @param string $name
     * @return bool
     */
    protected function assignFields(array $fields)
    {
        $this->view->assign('masseditFields', $fields);
        return true;
    }

}

?>