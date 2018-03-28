<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\articles;

/**
 * Artikelliste trait
 * 
 * @package fpcm\controller\traits\articles\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait lists {

    /**
     *
     * @var \fpcm\model\categories\categoryList
     */
    protected $categoryList;

    /**
     *
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     *
     * @var \fpcm\model\articles\articlelist
     */
    protected $articleList;

    /**
     *
     * @var \fpcm\model\comments\commentList
     */
    protected $commentList;

    /**
     *
     * @var array
     */
    protected $articleItems = [];

    /**
     *
     * @var array
     */
    protected $categories = [];

    /**
     *
     * @var array
     */
    protected $users = [];

    /**
     *
     * @var array
     */
    protected $commentCount = [];

    /**
     *
     * @var array
     */
    protected $commentPrivateUnapproved = [];

    /**
     *
     * @var int
     */
    protected $listShowLimit = 0;

    /**
     *
     * @var int
     */
    protected $listShowStart = 0;

    /**
     *
     * @var int
     */
    protected $articleCount = 0;

    /**
     *
     * @var bool
     */
    protected $showArchivedStatus = true;

    /**
     *
     * @var bool
     */
    protected $showDraftStatus = true;

    /**
     * Data view object
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'articlelist';
    }

    /**
     * Berechtigungen zum Bearbeiten initialisieren
     */
    public function initEditPermisions()
    {
        if (!$this->session->exists()) {
            return false;
        }

        $this->view->assign('permEditOwn', $this->permissions->check(array('article' => 'edit')));
        $this->view->assign('permEditAll', $this->permissions->check(array('article' => 'editall')));
        $this->view->assign('currentUserId', $this->session->getUserId());
        $this->view->assign('isAdmin', $this->session->getCurrentUser()->isAdmin());

        $this->view->assign('canArchive', $this->permissions->check(['article' => 'archive']));
        $this->view->assign('canApprove', $this->permissions->check(['article' => 'approve']));
        $this->view->assign('canChangeAuthor', $this->permissions->check(['article' => 'authors']));

        $this->deleteActions = $this->permissions->check(['article' => 'delete']);
    }

    /**
     * Kategorien übersetzen
     * @return void
     */
    protected function translateCategories()
    {
        if (!count($this->articleItems) || !$this->session->exists()) {
            return false;
        }

        $categories = $this->categoryList->getCategoriesNameListAll();
        foreach ($this->articleItems as $articles) {

            /* @var $article \fpcm\model\articles\article */
            foreach ($articles as &$article) {
                $article->setCategories(array_keys(array_intersect($categories, $article->getCategories())));
            }
        }
    }

    /**
     * 
     * @return boolean
     */
    protected function initActionObjects()
    {
        $this->articleList = new \fpcm\model\articles\articlelist();
        $this->categoryList = new \fpcm\model\categories\categoryList();
        $this->commentList = new \fpcm\model\comments\commentList();
        $this->userList = new \fpcm\model\users\userList();

        return true;
    }

    /**
     * Init action vars
     * @return boolean
     */
    protected function initActionVars()
    {
        $this->users = $this->userList->getUsersNameList();
        $this->categories = $this->categoryList->getCategoriesNameListCurrent();
        $this->commentCount = $this->commentList->countComments($this->getArticleListIds());
        $this->commentPrivateUnapproved = $this->commentList->countUnapprovedPrivateComments($this->getArticleListIds());

        return true;
    }

    /**
     * 
     * @return boolean
     */
    protected function initDataView()
    {
        $this->dataView = new \fpcm\components\dataView\dataView($this->getDataViewName());
        $this->dataView->addColumns($this->getDataViewCols());

        if (!count($this->articleItems)) {
            $this->dataView->addRow(
                    new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('title', 'GLOBAL_NOTFOUND2', 'fpcm-ui-padding-md-lr'),
                ],
                '', false, true
            ));

            return true;
        }

        /* @var $article \fpcm\model\articles\article */
        foreach ($this->articleItems as $articleMonth => $articles) {

            $titleStr  = $this->lang->writeMonth(date('n', $articleMonth), true);
            $titleStr .= ' ' .date('Y', $articleMonth);
            $titleStr .= ' (' . count($articles) . ')';

            $this->dataView->addRow(
                    new \fpcm\components\dataView\row([
                        new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('fpcm-ui-list-checkbox-sub', 'fpcm-ui-list-checkbox-sub' . $articleMonth))->setClass('fpcm-ui-list-checkbox-sub')->setValue($articleMonth), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                        new \fpcm\components\dataView\rowCol('button', ''),
                        new \fpcm\components\dataView\rowCol('title', $titleStr),
                        new \fpcm\components\dataView\rowCol('categories', ''),
                        new \fpcm\components\dataView\rowCol('metadata', ''),
                    ],
                    'fpcm-ui-dataview-rowcolpadding ui-widget-header ui-corner-all ui-helper-reset',
                    true
            ));
            
            $showCommentsStatus = $this->config->system_comments_enabled;
            foreach ($articles as $articleId => $article) {

                $buttons = [
                    '<div class="fpcm-ui-controlgroup">',
                    (new \fpcm\view\helper\openButton('articlefe'))->setUrlbyObject($article)->setTarget('_blank'),
                    (new \fpcm\view\helper\editButton('articleedit'))->setUrlbyObject($article),
                    (new \fpcm\view\helper\clearArticleCacheButton('cac'))->setDatabyObject($article),
                    '</div>'
                ];

                $title = [
                    '<strong>' . strip_tags($article->getTitle()) . '</strong>',
                    $this->getMetaData($article)
                ];

                $metaDataIcons = array_merge(
                    [$showCommentsStatus ? $this->getCommentBadge($articleId) : ''],
                    $article->getMetaDataStatusIcons($this->showDraftStatus, $showCommentsStatus,$this->showArchivedStatus)
                );

                $this->dataView->addRow(
                        new \fpcm\components\dataView\row([
                            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('actions[' . ($article->getEditPermission() ? 'ids' : 'ro') . '][]', 'chbx' . $articleId))->setClass('fpcm-ui-list-checkbox fpcm-ui-list-checkbox-subitem' . $articleMonth)->setValue($articleId)->setReadonly(!$article->getEditPermission()), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                            new \fpcm\components\dataView\rowCol('button', implode('', $buttons), 'fpcm-ui-dataview-align-center fpcm-ui-font-small', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                            new \fpcm\components\dataView\rowCol('title', implode(PHP_EOL, $title), 'fpcm-ui-ellipsis'),
                            new \fpcm\components\dataView\rowCol('categories', wordwrap(implode(', ', $article->getCategories()), 50, '<br>')),
                            new \fpcm\components\dataView\rowCol('metadata', implode('', $metaDataIcons), 'fpcm-ui-metabox fpcm-ui-dataview-align-center', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                        ])
                );
            }
        }

        return true;
    }

    /**
     * 
     * @param int $articleId
     * @return \fpcm\view\helper\badge
     */
    private function getCommentBadge($articleId)
    {
        $badge = new \fpcm\view\helper\badge('badge' . $articleId);

        $privateUnapproved = (isset($this->commentPrivateUnapproved[$articleId]) && $this->commentPrivateUnapproved[$articleId] ? true : false);

        $badge->setClass(($privateUnapproved ? 'fpcm-ui-badge-red fpcm-ui-badge-comments' : 'fpcm-ui-badge-comments'))
                ->setText(($privateUnapproved ? 'ARTICLE_LIST_COMMENTNOTICE' : 'HL_COMMENTS_MNG'))
                ->setValue((isset($this->commentCount[$articleId]) ? $this->commentCount[$articleId] : 0))
                ->setIcon('comments');

        return $badge;
    }

    /**
     * 
     * @param \fpcm\model\articles\article $article
     * @return string
     */
    private function getMetaData(\fpcm\model\articles\article $article)
    {
        $createuser = array_keys($this->users, $article->getCreateuser());
        $changeuser = array_keys($this->users, $article->getChangeuser());

        $notFound = $this->lang->translate('GLOBAL_NOTFOUND');

        return implode('', [
            '<span class="fpcm-ui-font-small fpcm-ui-block fpcm-ui-">',
            new \fpcm\view\helper\icon('calendar'),
            $this->lang->translate('EDITOR_AUTHOREDIT', [
                '{{username}}' => isset($createuser[0]) ? $createuser[0] : $notFound,
                '{{time}}' => new \fpcm\view\helper\dateText($article->getCreatetime())
            ]),
            '</span>',
            '<span class="fpcm-ui-font-small fpcm-ui-block">',
            new \fpcm\view\helper\icon('clock', 'far'),
            $this->lang->translate('EDITOR_LASTEDIT', [
                '{{username}}' => isset($changeuser[0]) ? $changeuser[0] : $notFound,
                '{{time}}' => new \fpcm\view\helper\dateText($article->getChangetime())
            ]),
            '</span>'
        ]);
    }

    /**
     * Artikel-IDs ermitteln
     * @return array
     */
    protected function getArticleListIds()
    {
        $articleIds = [];
        foreach ($this->articleItems as $monthData) {
            $articleIds = array_merge($articleIds, array_keys($monthData));
        }

        return $articleIds;
    }

    /**
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        return [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize('05')->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(2),
            (new \fpcm\components\dataView\column('title', 'ARTICLE_LIST_TITLE'))->setSize(4),
            (new \fpcm\components\dataView\column('categories', 'HL_CATEGORIES_MNG'))->setSize(3)->setAlign('center'),
            (new \fpcm\components\dataView\column('metadata', ''))->setAlign('center'),
        ];
    }

}
