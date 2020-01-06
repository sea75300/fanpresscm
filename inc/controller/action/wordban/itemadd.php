<?php

/**
 * Wordban item add controller
 * @item Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban;

class itemadd extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    protected $item;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->wordban;
    }

    protected function getViewPath() : string
    {
        return 'wordban/itemadd';
    }

    public function request()
    {

        $this->item = new \fpcm\model\wordban\item();

        if ($this->buttonClicked('wbitemSave') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        if ($this->buttonClicked('wbitemSave')) {

            $data = $this->getRequestVar('wbitem');

            if (!trim($data['searchtext']) || !trim($data['replacementtext'])) {
                $this->view->addErrorMessage('SAVE_FAILED_WORDBAN');
            } else {
                $this->item->setSearchtext($data['searchtext']);
                $this->item->setReplacementtext($data['replacementtext']);
                $this->item->setReplaceTxt(isset($data['replacetxt']) ? $data['replacetxt'] : 0);
                $this->item->setLockArticle(isset($data['lockarticle']) ? $data['lockarticle'] : 0);
                $this->item->setCommentApproval(isset($data['commentapproval']) ? $data['commentapproval'] : 0);

                $res = $this->item->save();

                if ($res === false) {
                    $this->view->addErrorMessage('SAVE_FAILED_WORDBAN');                    
                }

                if ($res === true) {
                    $this->redirect('wordban/list', array('added' => 1));
                }
            }
        }


        return true;
    }

    public function process()
    {
        $this->view->assign('item', $this->item);
        $this->view->setFieldAutofocus('wbitemsearchtext');
        $this->view->setFormAction('wordban/add');
        $this->view->addButton(new \fpcm\view\helper\saveButton('wbitemSave'));
        $this->view->addJsFiles(['texts.js']);
        $this->view->render();
    }

    protected function getHelpLink()
    {
        return 'HL_OPTIONS_WORDBAN';
    }

    protected function getActiveNavigationElement()
    {
        return 'submenu-itemnav-item-wordban';
    }

}

?>