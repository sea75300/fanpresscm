<?php

/**
 * AJAX module installer controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
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
        $this->steps['tabHeadline'] = 'MODULES_LIST_INSTALL';
        $this->steps['successMsg'] = 'PACKAGEMANAGER_SUCCESS';
        $this->steps['errorMsg'] = 'PACKAGEMANAGER_FAILED';

        $this->jsVars = [
            'pkgdata' => [
                'action' => 'install',
                'key' => $this->key
            ]
        ];
        
        $this->view->assign('successMsg', 'PACKAGEMANAGER_SUCCESS');
        $this->view->assign('errorMsg', 'PACKAGEMANAGER_FAILED');
        parent::process();
    }

}
