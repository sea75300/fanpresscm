<?php

/**
 * IP address add controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\ips;

class ipadd extends ipbase {

    public function process()
    {
        $this->view->setFormAction('ips/add');
        return true;
    }

}

?>