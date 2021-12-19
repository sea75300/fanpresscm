<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\theme\nav;

/**
 * Smiley manager nav trait
 * 
 * @package fpcm\controller\traits\system\syscheck
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait smileys {

    /**
     * 
     * @return string
     */
    protected function getActiveNavigationElement()
    {
        return 'smileys';
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'HL_OPTIONS_SMILEYS';
    }

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->smileys;
    }

}

?>