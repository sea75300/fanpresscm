<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\comments;

/**
 * Kommentar-Liste trait
 * 
 * @package fpcm\controller\traits\comments\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait lists {

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
     * @return array
     */
    protected function getPermissions()
    {
        return [
            'article' => [
                'editall',
                'edit'
            ],
            'comment' => [
                'editall',
                'edit'
            ]
        ];
    }

    /**
     * Initialisiert Berechtigungen
     */
    protected function initCommentPermissions()
    {
        if (!$this->permissions) {
            return false;
        }

        $this->permissionsArray['canEditComments'] = $this->permissions->check(['comment' => ['editall', 'edit']]);
        $this->permissionsArray['canApprove'] = $this->permissions->check(['comment' => 'approve']);
        $this->permissionsArray['canPrivate'] = $this->permissions->check(['comment' => 'private']);
        $this->permissionsArray['canMove'] = $this->permissions->check(['comment' => 'move']);
        $this->permissionsArray['canDelete'] = $this->permissions->check(['comment' => 'delete']);

        foreach ($this->permissionsArray as $key => $value) {
            $this->view->assign($key, $value);
        }
    }

    /**
     * Kommentar-Aktionen ausführen
     * @param \fpcm\model\comments\commentList $commentList
     * @return boolean
     */
    protected function processCommentActions(\fpcm\model\comments\commentList $commentList)
    {
        $ids = $this->getRequestVar('ids', [\fpcm\classes\http::FPCM_REQFILTER_CASTINT]);
        if (!is_array($ids) || !count($ids)) {
            $this->view->addErrorMessage('SELECT_ITEMS_MSG');
            return true;
        }

        if ($commentList->deleteComments($ids)) {
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
    protected function initCommentMassEditForm($ajax = false)
    {
        $this->view->assign('massEditPrivate', [
            'GLOBAL_NOCHANGE_APPLY' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('massEditSpam', [
            'GLOBAL_NOCHANGE_APPLY' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('massEditApproved', [
            'GLOBAL_NOCHANGE_APPLY' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        if ($ajax) {
            return true;
        }

        $this->view->addJsVars([
            'masseditPageToken' => \fpcm\classes\security::createPageToken('coments/massedit'),
            'masseditSaveFailed' => $this->lang->translate('SAVE_FAILED_COMMENTS')
        ]);
    }

    /**
     * 
     * @return boolean
     */
    protected function initActionObjects()
    {
        $this->list = new \fpcm\model\comments\commentList();
        $this->articleList = new \fpcm\model\articles\articlelist();
        $this->conditions = new \fpcm\model\comments\search();

        return true;
    }

    protected function getDataViewCols()
    {
        if (!$this->commentCount) {
            return [
                (new \fpcm\components\dataView\column('title', 'ARTICLE_LIST_TITLE'))->setSize(12),
            ];
        }

        return [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize('05')->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(2),
            (new \fpcm\components\dataView\column('name', 'COMMMENT_AUTHOR'))->setSize(2),
            (new \fpcm\components\dataView\column('email', 'GLOBAL_EMAIL'))->setSize(3),
            (new \fpcm\components\dataView\column('create', 'COMMMENT_CREATEDATE'))->setSize(3)->setAlign('center'),
            (new \fpcm\components\dataView\column('metadata', ''))->setAlign('center'),
        ];
    }

    protected function getDataViewName()
    {
        return 'commentlist';
    }

    protected function initDataView()
    {
        $this->page = $this->getRequestVar('page', [\fpcm\classes\http::FPCM_REQFILTER_CASTINT]);
        $this->listShowStart = \fpcm\classes\tools::getPageOffset($this->page, $this->config->articles_acp_limit);

        if ($this->getMode() < 2) {
            $this->conditions->limit = [$this->config->articles_acp_limit, $this->listShowStart];
        }

        $comments = $this->list->getCommentsBySearchCondition($this->conditions);
        $this->commentCount = count($comments);
        $this->maxItemCount = $this->list->countCommentsByCondition(new \fpcm\model\comments\search());

        $this->dataView = new \fpcm\components\dataView\dataView($this->getDataViewName());
        $this->dataView->addColumns($this->getDataViewCols());

        if (!$this->commentCount) {
            $this->dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('title', 'GLOBAL_NOTFOUND2', 'fpcm-ui-padding-md-lr'),
                ]
            ));

            return true;
        }

        /* @var $comment \fpcm\model\comments\comment */
        foreach ($comments as $commentId => $comment) {

            $buttons = [
                '<div class="fpcm-ui-controlgroup">',
                (new \fpcm\view\helper\openButton('commentfe'))->setUrlbyObject($comment)->setTarget('_blank'),
                (new \fpcm\view\helper\editButton('commentedit'))->setUrlbyObject($comment, '&mode=' . $this->getMode()),
                '</div>'
            ];

            $metaDataIcons = [];
            $metaDataIcons[] = (new \fpcm\view\helper\icon('flag fa-rotate-90 fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-' . $comment->getSpammer())->setText('COMMMENT_SPAM')->setStack('square');
            $metaDataIcons[] = (new \fpcm\view\helper\icon('check-circle-o fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-' . $comment->getApproved())->setText('COMMMENT_APPROVE')->setStack('square');
            $metaDataIcons[] = (new \fpcm\view\helper\icon('eye-slash fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-' . $comment->getPrivate())->setText('COMMMENT_PRIVATE')->setStack('square');

            $this->dataView->addRow(
                new \fpcm\components\dataView\row([
                new \fpcm\components\dataView\rowCol('select', (string) (new \fpcm\view\helper\checkbox('actions[' . ($comment->getEditPermission() ? 'ids' : 'ro') . '][]', 'chbx' . $commentId))->setClass('fpcm-ui-list-checkbox')->setValue($commentId)->setReadonly(!$comment->getEditPermission()), 'fpcm-ui-dataview-lineheight4', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                new \fpcm\components\dataView\rowCol('button', implode('', $buttons), 'fpcm-ui-dataview-align-center fpcm-ui-font-small', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                new \fpcm\components\dataView\rowCol('name', $comment->getName(), 'fpcm-ui-ellipsis'),
                new \fpcm\components\dataView\rowCol('email', $comment->getEmail(), 'fpcm-ui-ellipsis'),
                new \fpcm\components\dataView\rowCol('create', (string) new \fpcm\view\helper\dateText($comment->getCreatetime()), 'fpcm-ui-ellipsis'),
                new \fpcm\components\dataView\rowCol('metadata', implode('', $metaDataIcons), 'fpcm-ui-metabox fpcm-ui-dataview-align-center', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
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
