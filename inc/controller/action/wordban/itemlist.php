<?php

/**
 * Wordban item list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban;

class itemlist extends \fpcm\controller\abstracts\controller {

    protected $list;

    protected function getPermissions()
    {
        return ['system' => 'wordban'];
    }

    protected function getViewPath()
    {
        return 'wordban/itemlist';
    }

    public function request()
    {

        $this->list = new \fpcm\model\wordban\items();

        if ($this->getRequestVar('added')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_WORDBAN');
        }

        if ($this->getRequestVar('edited')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_WORDBAN');
        }

        if ($this->buttonClicked('delete') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $ids = $this->getRequestVar('ids');
        if ($this->buttonClicked('delete') && !is_null($ids)) {
            if ($this->list->deleteItems($ids)) {
                $this->view->addNoticeMessage('DELETE_SUCCESS_WORDBAN');
            } else {
                $this->view->addErrorMessage('DELETE_FAILED_WORDBAN');
            }
        }

        return true;
    }

    public function process()
    {
        $itemList = $this->list->getItems();
        $this->view->assign('itemList', $itemList);
        $this->view->setFormAction('wordban/list');
        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('addnew'))->setUrl(\fpcm\classes\tools::getFullControllerLink('wordban/add'))->setText('WORDBAN_ADD')->setIcon('ban')->setClass('fpcm-loader'),
            (new \fpcm\view\helper\deleteButton('delete'))->setClass('fpcm-ui-button-confirm')
        ]);
        $this->view->render();
    }

    protected function getHelpLink()
    {
        return 'hl_options';
    }

}

?>
