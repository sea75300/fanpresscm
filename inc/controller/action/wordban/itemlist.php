<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\wordban;

/**
 * Wordban item list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class itemlist extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\common\dataView;

    /**
     *
     * @var \fpcm\model\wordban\items
     */
    protected $list;

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'wordban'];
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'hl_options';
    }

    /**
     * 
     * @return boolean
     */
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

    /**
     * 
     * @return boolean
     */
    public function process()
    {
        $this->items = $this->list->getItems();
        $this->initDataView();
        
        $this->view->assign('headline', 'HL_OPTIONS_WORDBAN');
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
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize('05')->setAlign('center'),
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

}

?>
