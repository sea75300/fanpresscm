<?php

/**
 * AJAX module installer controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr\modules;

class install extends base {

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->steps['checkFs'] = false;
        parent::process();
    }

    protected function getMode() : string
    {
        return 'install';
    }

    protected function getTabHeadline() : string
    {
        return 'MODULES_LIST_INSTALL';
    }
}
