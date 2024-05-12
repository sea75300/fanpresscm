<?php

/**
 * Wordban item add controller
 * @item Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban\item;

class add extends base
{

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
