<?php

/**
 * AJAX module installer controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\packagemgr;

class moduleInstaller extends moduleBase {

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->steps['tabHeadline'] = 'MODULES_LIST_INSTALL';

        $this->jsVars = [
            'pkgdata' => [
                'action' => 'install',
                'key' => $this->key
            ]
        ];
        
        parent::process();
    }

}
