<?php

/**
 * Wordban item add controller
 * @item Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban;

class itemadd extends itembase implements \fpcm\controller\interfaces\isAccessible {

    public function request()
    {
        $this->item = new \fpcm\model\wordban\item();
        return true;
    }

    public function process()
    {
        $this->view->setFormAction('wordban/add');
        parent::process();
    }

    protected function getActionText() : string
    {
        return 'ADD';
    }
}

?>