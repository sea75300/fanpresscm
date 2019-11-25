<?php

/**
 * Category add controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\categories;

class categoryadd extends base {

    public function request()
    {
        $this->category = new \fpcm\model\categories\category();
        parent::request();
        return true;
    }

    public function process() {
        $this->view->setFormAction('categories/add');
        parent::process();
    }

}

?>