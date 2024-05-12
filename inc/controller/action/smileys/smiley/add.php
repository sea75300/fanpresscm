<?php

/**
 * Smiley add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\smileys\smiley;

class add extends base {

    public function process()
    {
        $this->view->setFormAction('smileys/add');
        parent::process();
    }

    protected function getActionText() : string
    {
        return 'ADD';
    }

}
