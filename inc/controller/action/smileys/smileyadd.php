<?php

/**
 * Smiley add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\smileys;

class smileyadd extends smileybase {

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

?>
