<?php

/**
 * AJAX module update controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr;

class moduleUpdater extends moduleBase {

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        if ($this->updateDb) {
            $this->steps = array_map([$this, 'setFalse'], $this->steps);
            $this->steps['updateDb'] = true;
        }
        else {
            $this->steps['checkFs'] = true;
        }

        if ($this->keepMaintenance) {
            $this->steps['keepMaintenance'] = true;
        }

        $this->steps['tabHeadline'] = 'MODULES_LIST_UPDATE';
        $this->steps['successMsg'] = 'PACKAGEMANAGER_SUCCESS_UPDATE';
        $this->steps['errorMsg'] = 'PACKAGEMANAGER_FAILED_UPDATE';
        
        $this->jsVars = [
            'pkgdata' => [
                'action' => 'update',
                'key' => $this->key
            ]
        ];

        parent::process();
    }

    /**
     *
     * @param bool $data
     * @return bool
     */
    private function setFalse($data)
    {
        return false;
    }

}
