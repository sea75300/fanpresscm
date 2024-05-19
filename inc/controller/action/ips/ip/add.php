<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\ips\ip;

/**
 * IP address add controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class add extends base {

    public function process()
    {
        $this->view->setFormAction('ips/add');
        return true;
    }

}
