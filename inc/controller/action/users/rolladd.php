<?php

/**
 * User roll add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\users;

class rolladd extends rollbase {

    /**
     *
     * @var string
     */
    protected $headlineVar = 'USERS_ROLL_ADD';

    public function request()
    {
        $this->getRollObject();
        $this->save();

        $this->view->setFormAction('users/addroll');
        
        return parent::request();
    }

}

?>
