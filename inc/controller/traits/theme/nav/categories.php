<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\theme\nav;

/**
 * Category manager nav trait
 * 
 * @package fpcm\controller\traits\system\syscheck
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait categories {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->categories;
    }

    protected function getHelpLink()
    {
        return 'HL_CATEGORIES_MNG';
    }

    protected function getActiveNavigationElement()
    {
        return 'categories';
    }

}

?>