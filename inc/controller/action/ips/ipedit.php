<?php

/**
 * IP address edit controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\ips;

class ipedit extends ipbase {

    public function request()
    {
        parent::request();
        if (!$this->ipaddress->exists()) {
            return false;
        }
        
        return true;
    }
    
    public function process()
    {
        $this->view->setFormAction('ips/edit&id='.$this->id);
        return true;
    }

}

?>