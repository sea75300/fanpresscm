<?php

/**
 * AJAX module update controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr\modules;

class update extends base {

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        parent::process();

        if ($this->updateDb) {
            $this->steps = array_map([$this, 'invert'], $this->steps);
            $this->steps['updateDb'] = true;
            return;
        }

        $this->steps['checkFs'] = true;

    }

    protected function getMode() : string
    {
        return 'update';
    }

    protected function getTabHeadline() : string
    {
        return 'MODULES_LIST_UPDATE';
    }
}
