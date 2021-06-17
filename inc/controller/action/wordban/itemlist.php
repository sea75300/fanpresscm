<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban;

/**
 * Wordban item list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class itemlist extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\common\dataView;

    /**
     *
     * @var \fpcm\model\wordban\items
     */
    protected $list;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->wordban;
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'HL_OPTIONS_WORDBAN';
    }

    /**
     * 
     * @return bool
     */
    public function request()
    {
        $this->list = new \fpcm\model\wordban\items();

        if ($this->request->hasMessage('added')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_WORDBAN');
        }

        if ($this->request->hasMessage('edited')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_WORDBAN');
        }

        $this->delete();
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function process()
    {
        $this->items = $this->list->getItems();
        $this->initDataView();
        
        $this->view->setFormAction('wordban/list');
        $this->view->addJsFiles(['texts.js']);
        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('addnew'))->setUrl(\fpcm\classes\tools::getFullControllerLink('wordban/add'))->setText('WORDBAN_ADD')->setIcon('ban')->setClass('fpcm-loader'),
            (new \fpcm\view\helper\deleteButton('delete'))->setClass('fpcm-ui-button-confirm')
        ]);
        
        $this->view->render();
        return true;
    }

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'textslist';
    }

    /**
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        return [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('subject', 'WORDBAN_NAME'))->setSize(4),
            (new \fpcm\components\dataView\column('replacement', 'WORDBAN_REPLACEMENT_TEXT'))->setSize(4),
            (new \fpcm\components\dataView\column('metadata', '')),
        ];
    }

    /**
     * 
     * @param \fpcm\model\wordban\item $item
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($item)
    {
        $metaData = [
            (new \fpcm\view\helper\icon('search fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-' . $item->getReplaceTxt())->setText('WORDBAN_REPLACETEXT')->setStack('square'),
            (new \fpcm\view\helper\icon('thumbs-up fa-inverse', 'far'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-' . $item->getLockArticle())->setText('WORDBAN_APPROVE_ARTICLE')->setStack('square'),
            (new \fpcm\view\helper\icon('check-circle fa-inverse', 'far'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-' . $item->getCommentApproval())->setText('WORDBAN_APPROVA_COMMENT')->setStack('square')
        ];

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('ids[]', 'chbx' . $item->getId()))->setClass('fpcm-ui-list-checkbox')->setValue($item->getId()), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\editButton('editItem'))->setUrlbyObject($item), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('subject', new \fpcm\view\helper\escape($item->getSearchtext())),
            new \fpcm\components\dataView\rowCol('replacement', new \fpcm\view\helper\escape($item->getReplacementtext())),
            new \fpcm\components\dataView\rowCol('metadata', implode('', $metaData), 'fpcm-ui-metabox fpcm-ui-dataview-align-center', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
        ]);
    }

    protected function getDataViewTabs() : array
    {
        return [
            (new \fpcm\view\helper\tabItem('tabs-'.$this->getDataViewName().'-list'))
                ->setText('HL_OPTIONS_WORDBAN')
                ->setFile('components/dataview__inline.php')
                ->setState(\fpcm\view\helper\tabItem::STATE_ACTIVE)
        ];
    }
    
    private function delete()
    {
        if (!$this->buttonClicked('delete')) {
            return false;
        }
        
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $ids = $this->request->getIDs();
        if (!count($ids)) {
            return false;
        }

        if ($this->list->deleteItems($ids)) {
            $this->view->addNoticeMessage('DELETE_SUCCESS_WORDBAN');
            return true;
        }

        $this->view->addErrorMessage('DELETE_FAILED_WORDBAN');
        return true;
    }

}

?>
