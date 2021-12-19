<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\theme;

/**
 * Ajax view dummy return trait
 * 
 * @package fpcm\controller\traits\common
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.0.0-a4
 */
trait viewAjaxDummy {

    /**
     * 
     * @return string
     */
    final protected function getViewPath() : string
    {
        return 'common/ajax';
    }

}

?>