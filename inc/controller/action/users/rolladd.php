<?php

/**
 * User roll add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

class rolladd extends rollbase {

    protected function getViewPath()
    {
        return 'users/rolladd';
    }

    public function request()
    {
        $this->view->setFormAction('users/addroll');
        $this->userRoll = new \fpcm\model\users\userRoll();
        $this->save();
        
        return parent::request();
    }

}

?>
