<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\system;

/**
 * AJAX-Controller - System Check
 *
 * @package fpcm\controller\ajax\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class sendStats extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * is accessible
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options;
    }

    public function request(): bool
    {

        $check = new \fpcm\model\system\check\check();
        $res = $check->submitStats();
        
        fpcmLogSystem('Statistic submission return code: ' . (int) $res);
        
        return true;
    }

}
