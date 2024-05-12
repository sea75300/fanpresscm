<?php

/**
 * Wordban item edit controller
 * @item Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban\item;

class edit extends base {

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

    /**
     * 
     * @return string
     */
    protected function getActionText() : string
    {
        return 'EDIT';
    }

    /**
     * Delete item in edit mode
     * @return bool
     */
    protected function onDelete()
    {
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }        
        
        if (!$this->item->delete()) {
            $this->view->addErrorMessage('DELETE_FAILED_WORDBAN');        
            return true;
        }
        
        $this->redirect('wordban/list', array('deleted' => 1));
        return true;        
    }
    
    public function getButtons(): array
    {
        $buttons = parent::getButtons();
        $buttons[] = (new \fpcm\view\helper\deleteButton('delete'))->setClass('fpcm ui-button-confirm');
        
        return $buttons;
    }

}
