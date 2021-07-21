<?php

/**
 * Wordban item edit controller
 * @item Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban;

abstract class itembase extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\isAccessible,
           \fpcm\controller\interfaces\requestFunctions {

    use \fpcm\controller\traits\common\simpleEditForm;
    
    /**
     *
     * @var \fpcm\model\wordban\item
     */
    protected $item;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->wordban;
    }

    protected function getHelpLink()
    {
        return 'HL_OPTIONS_WORDBAN';
    }

    protected function getActiveNavigationElement()
    {
        return 'submenu-itemnav-item-wordban';
    }

    public function process()
    {
        $this->view->addButton(new \fpcm\view\helper\saveButton('save'));
        $this->view->addJsFiles(['texts.js']);

        $this->view->addTabs('texts', [
            (new \fpcm\view\helper\tabItem('text'))
                ->setText('WORDBAN_'.$this->getActionText())
                ->setFile($this->getViewPath().'.php')
        ]);

        $this->assignFields([
            (new \fpcm\view\helper\textInput('wbitem[searchtext]'))
                    ->setValue($this->item->getSearchtext())
                    ->setText('WORDBAN_NAME')
                    ->setIcon('filter')
                    ->setAutoFocused(true),
            (new \fpcm\view\helper\textInput('wbitem[replacementtext]'))
                    ->setValue($this->item->getReplacementtext())
                    ->setText('WORDBAN_REPLACEMENT_TEXT')
                    ->setIcon('edit'),
            new \fpcm\components\fieldGroup([               
                (new \fpcm\view\helper\checkbox('wbitem[replacetxt]'))->setText('WORDBAN_REPLACETEXT')->setSelected($this->item->getReplaceTxt())->setSwitch(true),
                (new \fpcm\view\helper\checkbox('wbitem[lockarticle]'))->setText('WORDBAN_APPROVE_ARTICLE')->setSelected($this->item->getLockArticle())->setSwitch(true),
                (new \fpcm\view\helper\checkbox('wbitem[commentapproval]'))->setText('WORDBAN_APPROVA_COMMENT')->setSelected($this->item->getCommentApproval())->setSwitch(true),
            ], 'GLOBAL_ACTION_PERFORM', new \fpcm\view\helper\icon('cogs'))
        ]);        
        
        $this->view->render();
    }

    protected function onSave()
    {

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $data = $this->request->fromPOST('wbitem');

        if (!trim($data['searchtext']) || !trim($data['replacementtext'])) {
            $this->view->addErrorMessage('SAVE_FAILED_WORDBAN');
            return true;
        }
        
        $this->item->setSearchtext($data['searchtext']);
        $this->item->setReplacementtext($data['replacementtext']);
        $this->item->setReplaceTxt(isset($data['replacetxt']) ? $data['replacetxt'] : 0);
        $this->item->setLockArticle(isset($data['lockarticle']) ? $data['lockarticle'] : 0);
        $this->item->setCommentApproval(isset($data['commentapproval']) ? $data['commentapproval'] : 0);

        
        $fn = $this->item->getId() ? 'update' : 'save';
        if (!call_user_func([$this->item, $fn])) {
            $this->view->addErrorMessage('SAVE_FAILED_WORDBAN');
            return false;
        }        

        $this->redirect('wordban/list', array('edited' => 1));
        return true;
    }
    
    abstract protected function getActionText() : string;

}

?>