<?php

/**
 * Smiley edit controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\smileys\smiley;

class edit extends base {

    public function process()
    {
        $this->view->setFormAction('smileys/edit', [
            'id' => $this->smiley->getId()
        ]);
        parent::process();
    }

    protected function getActionText() : string
    {
        return 'EDIT';
    }
    
    public function getButtons(): array
    {
        $buttons = parent::getButtons();
        $buttons[] = (new \fpcm\view\helper\deleteButton('delete'))->setClickConfirm();
        
        return $buttons;
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
        
        if (!$this->smiley->delete()) {
            $this->view->addErrorMessage('DELETE_FAILED_SMILEYS');        
            return true;
        }
        
        $this->redirect('smileys/list', array('deleted' => 1));
        return true;        
    }

}
