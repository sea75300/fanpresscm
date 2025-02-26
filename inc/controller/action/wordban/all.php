<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban;

/**
 * Wordban item list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class all extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\requestFunctions
{

    use \fpcm\controller\traits\common\dataView,
        \fpcm\controller\traits\theme\nav\texts,
        \fpcm\model\traits\statusIcons;

    /**
     *
     * @var \fpcm\model\wordban\items
     */
    protected $list;

    /**
     * 
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     * 
     * @return bool
     */
    public function request()
    {

        if ($this->request->hasMessage('added')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_WORDBAN');
        }

        if ($this->request->hasMessage('edited')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_WORDBAN');
        }
        
        if ($this->request->hasMessage('deleted')) {
            $this->view->addNoticeMessage('DELETE_SUCCESS_WORDBAN');
        }

        $this->list = new \fpcm\model\wordban\items();
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
        $this->view->addJsFiles(['system/texts.js']);
        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('addnew'))->setUrl(\fpcm\classes\tools::getFullControllerLink('wordban/add'))->setText('GLOBAL_NEW')->setIcon('ban')->setPrimary(),
            (new \fpcm\view\helper\deleteButton('delete'))->setClickConfirm()
        ]);
        
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
            (new \fpcm\components\dataView\column('metadata', ''))->setAlign('center'),
        ];
    }

    /**
     * 
     * @param \fpcm\model\wordban\item $item
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($item)
    {
        $metaData   = [
            $this->getStatusColor( (new \fpcm\view\helper\icon('search fa-inverse'))->setClass('fpcm-ui-editor-metainfo')->setText('WORDBAN_REPLACETEXT')->setStack('square'), $item->getReplaceTxt() ),
            $this->getStatusColor( (new \fpcm\view\helper\icon('thumbs-up fa-inverse', 'far'))->setClass('fpcm-ui-editor-metainfo')->setText('WORDBAN_APPROVE_ARTICLE')->setStack('square'), $item->getLockArticle() ),
            $this->getStatusColor( (new \fpcm\view\helper\icon('check-circle fa-inverse', 'far'))->setClass('fpcm-ui-editor-metainfo')->setText('WORDBAN_APPROVA_COMMENT')->setStack('square'), $item->getCommentApproval() ),
        ];        

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('ids[]', 'chbx' . $item->getId()))->setClass('fpcm-ui-list-checkbox')->setValue($item->getId()), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('button', (new \fpcm\view\helper\editButton('editItem'))->setUrlbyObject($item), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('subject', new \fpcm\view\helper\escape($item->getSearchtext())),
            new \fpcm\components\dataView\rowCol('replacement', new \fpcm\view\helper\escape($item->getReplacementtext())),
            new \fpcm\components\dataView\rowCol('metadata', implode('', $metaData), 'fs-5', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
        ]);
    }

    protected function getDataViewTabs() : array
    {
        return [
            (new \fpcm\view\helper\tabItem('tabs-'.$this->getDataViewName().'-list'))
                ->setText('HL_OPTIONS_WORDBAN')
                ->setFile('components/dataview__inline.php')
        ];
    }
    
    protected function onDelete()
    {

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
