<?php

/**
 * Wordban item edit controller
 * @item Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban;

class itemedit extends itembase {

    public function request()
    {
        $id = $this->request->getID();

        if (!$id) {
            $this->redirect('wordban/list');
        }

        $this->item = new \fpcm\model\wordban\item($id);

        if (!$this->item->exists()) {
            $this->view = new \fpcm\view\error('LOAD_FAILED_WORDBAN', 'wordban/list');
            return false;
        }

        return true;
    }

    public function process()
    {
        $this->view->setFormAction($this->item->getEditLink(), [], true);
        parent::process();
    }

    protected function getActionText() : string
    {
        return 'EDIT';
    }

}

?>