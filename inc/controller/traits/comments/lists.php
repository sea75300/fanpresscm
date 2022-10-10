<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\comments;

/**
 * Kommentar-Liste trait
 * 
 * @package fpcm\controller\traits\comments\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait lists {

    use \fpcm\controller\traits\common\massedit;

    protected $permissionsArray = [];

    /**
     *
     * @var array
     */
    protected $actions = array(
        'COMMENTLIST_ACTION_MASSEDIT' => 1,
        'COMMENTLIST_ACTION_DELETE' => 2
    );

    /**
     *
     * @var \fpcm\model\comments\commentList
     */
    protected $list;

    /**
     *
     * @var \fpcm\model\articles\articlelist
     */
    protected $articleList;

    /**
     *
     * @var \fpcm\model\comments\search
     */
    protected $conditions;

    /**
     *
     * @var int
     */
    protected $listShowStart = 0;

    /**
     *
     * @var int
     */
    protected $commentCount = 0;

    /**
     *
     * @var int
     */
    protected $page = 0;

    /**
     *
     * @var int
     */
    protected $maxItemCount = 0;

    /**
     *
     * @var int
     */
    protected $mode = 1;

    /**
     *
     * @var \fpcm\view\message
     */
    protected $filterError = null;

    /**
     * Initialisiert Berechtigungen
     */
    protected function initCommentPermissions()
    {
        if (!$this->permissions) {
            return false;
        }

        $this->permissionsArray['canEditComments'] = $this->permissions->editComments();
        $this->permissionsArray['canApprove'] = $this->permissions->comment->approve;
        $this->permissionsArray['canPrivate'] = $this->permissions->comment->private;
        $this->permissionsArray['canMove'] = $this->permissions->comment->move;
        $this->permissionsArray['canDelete'] = $this->permissions->comment->delete;
        $this->permissionsArray['canMassEdit'] = $this->permissions->comment->massedit;

        foreach ($this->permissionsArray as $key => $value) {
            $this->view->assign($key, $value);
        }
    }

    /**
     * Kommentar-Aktionen ausführen
     * @param \fpcm\model\comments\commentList $commentList
     * @return bool
     */
    protected function processCommentActions(\fpcm\model\comments\commentList $commentList)
    {
        $ids = $this->request->fromPOST('ids', [ \fpcm\model\http\request::FILTER_CASTINT ]);
        if (!is_array($ids) || !count($ids)) {
            $this->view->addErrorMessage('SELECT_ITEMS_MSG');
            return true;
        }

        if ($this->permissions->comment->delete && $commentList->deleteComments($ids)) {
            $this->view->addNoticeMessage('DELETE_SUCCESS_COMMENTS');
            return true;
        }

        $this->view->addErrorMessage('DELETE_FAILED_COMMENTS');
        return true;
    }

    /**
     * Initialisiert Suchformular-Daten
     * @param array $users
     */
    protected function initCommentMassEditForm($mode)
    {
        $fields = [];
        
        if ($this->permissions->comment->approve) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('isSpam'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('COMMMENT_SPAM')
                    ->setIcon('flag')
            );

            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('isApproved'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('COMMMENT_APPROVE')
                    ->setIcon('check-circle', 'far')
            );
        }
        
        if ($this->permissions->comment->private) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('isPrivate'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('COMMMENT_PRIVATE')
                    ->setIcon('eye-slash')
            );
        }
        
        if ($mode === 1 && $this->permissions->comment->move) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\textInput('moveToArticle'))
                    ->setClass('fpcm-ui-input-articleid')
                    ->setText('COMMMENT_MOVE')
                    ->setIcon('clipboard')
            );
        }

        $this->assignFields($fields);        
        $this->assignPageToken('comments');
        $this->view->addJsLangVars(['SAVE_FAILED_COMMENTS']);
    }

    /**
     * 
     * @return bool
     */
    protected function initActionObjects()
    {
        return $this->commentObjects();
    }

    /**
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        return [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(1),
            (new \fpcm\components\dataView\column('name', 'COMMMENT_AUTHOR'))->setSize(2),
            (new \fpcm\components\dataView\column('email', 'GLOBAL_EMAIL'))->setSize(3),
            (new \fpcm\components\dataView\column('create', 'COMMMENT_CREATEDATE'))->setAlign('center'),
            (new \fpcm\components\dataView\column('metadata', ''))->setAlign('center'),
        ];
    }

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'commentlist';
    }

    /**
     * 
     * @return bool
     */
    protected function initDataView()
    {
        $this->commentDataView();
    }

    /**
     * 
     * @return bool
     */
    protected function commentObjects()
    {
        $this->list = new \fpcm\model\comments\commentList();
        $this->articleList = new \fpcm\model\articles\articlelist();
        $this->conditions = new \fpcm\model\comments\search();
        
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function commentDataView()
    {
        $this->page          = $this->request->getPage();
        $this->listShowStart = \fpcm\classes\tools::getPageOffset($this->page, $this->config->articles_acp_limit);

        if ($this->getMode() < 2) {
            $this->conditions->limit = [$this->config->articles_acp_limit, $this->listShowStart];
        }
        
        $this->conditions->deleted = 0;
        $this->conditions->orderby = ['createtime DESC'];

        $comments = $this->list->getCommentsBySearchCondition($this->conditions);
        if ($comments === \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX) {
            $comments = [];
            $this->filterError = new \fpcm\view\message($this->language->translate('SEARCH_ERROR'), \fpcm\view\message::TYPE_ERROR);
        }        
        
        $this->commentCount = count($comments);
        $this->maxItemCount = $this->list->countCommentsByCondition(new \fpcm\model\comments\search());

        $this->dataView = new \fpcm\components\dataView\dataView($this->getDataViewName());
        $this->dataView->addColumns($this->getDataViewCols());

        if (!$this->commentCount) {
            $this->dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol(
                        'name',
                        (new \fpcm\view\helper\icon('list-ul '))->setSize('lg')->setStack(true)->setStack('ban fpcm-ui-important-text')->setStackTop(true).' '.
                        $this->language->translate('GLOBAL_NOTFOUND2'),
                        '',
                        \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT
                    ),
                ],
                '', false, true
            ));

            return true;
        }

        /* @var $comment \fpcm\model\comments\comment */
        foreach ($comments as $commentId => $comment) {

            $buttons = (new \fpcm\view\helper\controlgroup('itemactions'.$commentId))
                        ->addItem( (new \fpcm\view\helper\openButton('commentfe'))->setUrlbyObject($comment)->setTarget('_blank') )
                        ->addItem( (new \fpcm\view\helper\editButton('commentedit'))->setUrlbyObject($comment, '&mode=' . $this->getMode())->setClass('fpcm-ui-commentlist-link') );

            $this->dataView->addRow(
                new \fpcm\components\dataView\row([
                new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('ids[' . ($comment->getEditPermission() ? '' : 'ro') . ']', 'chbx' . $commentId))->setClass('fpcm-ui-list-checkbox')->setValue($commentId)->setReadonly(!$comment->getEditPermission()), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                new \fpcm\components\dataView\rowCol('button', $buttons, 'fpcm-ui-dataview-align-center fpcm-ui-font-small', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                new \fpcm\components\dataView\rowCol('name', $comment->getName(), 'fpcm-ui-ellipsis'),
                new \fpcm\components\dataView\rowCol('email', $comment->getEmail(), 'fpcm-ui-ellipsis'),
                new \fpcm\components\dataView\rowCol('create', new \fpcm\view\helper\dateText($comment->getCreatetime()), 'fpcm-ui-ellipsis'),
                new \fpcm\components\dataView\rowCol('metadata', implode('', $comment->getMetaDataStatusIcons()), 'fs-5', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            ]));
        }

        return true;
    }

    /**
     * 
     * @return int
     */
    protected function getMode()
    {
        return 1;
    }
}
