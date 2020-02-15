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
     * @var array
     */
    protected $sharesCounts = [];

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

        $this->view->assign('permEditOwn', $this->permissions->article->edit);
        $this->view->assign('permEditAll', $this->permissions->article->editall);
        $this->view->assign('permMassEdit', $this->permissions->article->massedit);
        $this->view->assign('currentUserId', $this->session->getUserId());
        $this->view->assign('isAdmin', $this->session->getCurrentUser()->isAdmin());

        $this->view->assign('canArchive', $this->permissions->article->archive);
        $this->view->assign('canApprove', $this->permissions->article->approve);
        $this->view->assign('canChangeAuthor', $this->permissions->article->authors);

        $this->deleteActions = $this->permissions->article->delete;
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
     * @return bool
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
     * @return bool
     */
    protected function initActionVars()
    {
        $this->users = $this->userList->getUsersNameList();
        $this->categories = $this->categoryList->getCategoriesNameListCurrent();

        $this->commentCount = $this->config->system_comments_enabled
                            ? $this->commentList->countComments($this->getArticleListIds())
                            : [];

        $this->commentPrivateUnapproved = $this->config->system_comments_enabled
                                        ? $this->commentList->countUnapprovedPrivateComments($this->getArticleListIds())
                                        : [];

        $this->sharesCounts = $this->config->system_share_count
                            ? (new \fpcm\model\shares\shares())->getSharesCountByArticles()
                            : [];
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function initDataView()
    {
        $this->dataView = new \fpcm\components\dataView\dataView($this->getDataViewName());
        $this->dataView->addColumns($this->getDataViewCols());

        if (!count($this->articleItems)) {
            $this->dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol(
                        'title',
                        (new \fpcm\view\helper\icon('list-ul '))->setSize('lg')->setStack(true)->setStack('ban fpcm-ui-important-text')->setStackTop(true).' '.
                        $this->language->translate('GLOBAL_NOTFOUND2'),
                        'fpcm-ui-padding-md-lr fpcm-ui-dataview-align-notfound',
                        \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT
                    ),
                ],
                '', false, true
            ));

            return true;
        }

        /* @var $article \fpcm\model\articles\article */
        foreach ($this->articleItems as $articleMonth => $articles) {

            $titleStr  = $this->language->writeMonth(date('n', $articleMonth), true);
            $titleStr .= ' ' .date('Y', $articleMonth);
            $titleStr .= ' (' . count($articles) . ')';

            $this->dataView->addRow(
                    new \fpcm\components\dataView\row([
                        new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('fpcm-ui-list-checkbox-sub', 'fpcm-ui-list-checkbox-sub' . $articleMonth))->setClass('fpcm-ui-list-checkbox-sub')->setValue($articleMonth), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                        new \fpcm\components\dataView\rowCol('button', '', 'd-none d-lg-block'),
                        new \fpcm\components\dataView\rowCol('title', $titleStr),
                        new \fpcm\components\dataView\rowCol('categories', '', 'd-none d-lg-block'),
                        new \fpcm\components\dataView\rowCol('metadata', '', 'd-none d-lg-block'),
                    ],
                    'fpcm-ui-dataview-rowcolpadding ui-widget-header ui-corner-all ui-helper-reset',
                    true
            ));
            
            $showCommentsStatus = $this->config->system_comments_enabled;
            $showSharesCount = $this->config->system_share_count;

            foreach ($articles as $articleId => $article) {

                $buttons = (new \fpcm\view\helper\controlgroup('articlebuttons' . $article->getId() ))
                            ->addItem( (new \fpcm\view\helper\openButton('articlefe'))->setUrlbyObject($article)->setTarget('_blank') )
                            ->addItem( (new \fpcm\view\helper\editButton('articleedit'))->setUrlbyObject($article) )
                            ->addItem( (new \fpcm\view\helper\clearArticleCacheButton('cac'))->setDatabyObject($article) );

                $isTrash = $this->isTrash ?? false;
                if ($this->deleteActions && !$isTrash) {
                    
                    $buttons->addItem( (new \fpcm\view\helper\button('delete'.$articleId))
                            ->setText('GLOBAL_DELETE')
                            ->setIcon('trash')
                            ->setIconOnly(true)
                            ->setClass('fpcm-ui-button-delete fpcm-ui-button-delete-article-single')
                            ->setData(['articleid' => $articleId]) );
                }

                $title = [
                    '<strong>' . strip_tags($article->getTitle()) . '</strong>',
                    $this->getMetaData($article)
                ];

                $metaDataIcons = array_merge(
                    [$showCommentsStatus ? $this->getCommentBadge($articleId) : ''],
                    [$showSharesCount ? $this->getSharesBadge($articleId) : ''],
                    $article->getMetaDataStatusIcons($this->showDraftStatus, $showCommentsStatus,$this->showArchivedStatus)
                );

                $this->dataView->addRow(
                        new \fpcm\components\dataView\row([
                            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('actions[' . ($article->getEditPermission() || $article->isInEdit() ? 'ids' : 'ro') . '][]', 'chbx' . $articleId))->setClass('fpcm-ui-list-checkbox fpcm-ui-list-checkbox-subitem' . $articleMonth)->setValue($articleId)->setReadonly(!$article->getEditPermission()), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                            new \fpcm\components\dataView\rowCol('button', $buttons, 'fpcm-ui-dataview-align-center fpcm-ui-font-small', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
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
                ->setText(($privateUnapproved ? 'ARTICLE_LIST_COMMENTNOTICE' : 'COMMMENT_HEADLINE'))
                ->setValue((isset($this->commentCount[$articleId]) ? $this->commentCount[$articleId] : 0))
                ->setIcon('comments');

        return $badge;
    }

    /**
     * 
     * @param int $articleId
     * @return \fpcm\view\helper\badge
     */
    private function getSharesBadge($articleId)
    {
        return (new \fpcm\view\helper\badge('badge' . $articleId))->setClass('fpcm-ui-badge-comments')
            ->setText('EDITOR_SHARES')
            ->setValue((isset($this->sharesCounts[$articleId]) ? $this->sharesCounts[$articleId] : 0))
            ->setIcon('share');
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

        $notFound = $this->language->translate('GLOBAL_NOTFOUND');

        return implode('', [
            '<span class="fpcm-ui-font-small fpcm-ui-block fpcm-ui-">',
            new \fpcm\view\helper\icon('calendar'),
            $this->language->translate('EDITOR_AUTHOREDIT', [
                '{{username}}' => isset($createuser[0]) ? $createuser[0] : $notFound,
                '{{time}}' => new \fpcm\view\helper\dateText($article->getCreatetime())
            ]),
            '</span>',
            '<span class="fpcm-ui-font-small fpcm-ui-block">',
            new \fpcm\view\helper\icon('clock', 'far'),
            $this->language->translate('EDITOR_LASTEDIT', [
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
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(2),
            (new \fpcm\components\dataView\column('title', 'ARTICLE_LIST_TITLE'))->setSize(4),
            (new \fpcm\components\dataView\column('categories', 'HL_CATEGORIES_MNG'))->setSize(3)->setAlign('center'),
            (new \fpcm\components\dataView\column('metadata', ''))->setAlign('center')->setSize(2),
        ];
    }

}
