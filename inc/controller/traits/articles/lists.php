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
 * @copyright (c) 2011-2020, Stefan Seehafer
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
     * @var int
     */
    protected $count = 0;

    /**
     *
     * @var array
     */
    protected $items = [];

    /**
     *
     * @var array
     */
    protected $relatedCounts = [];

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
     *
     * @var bool
     */
    protected $isTrash = false;

    /**
     * Data view object
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     * Kategorien übersetzen
     * @return void
     */
    protected function translateCategories()
    {
        if (!count($this->items) || !$this->session->exists()) {
            return false;
        }

        $categories = $this->categoryList->getCategoriesNameListAll();
        foreach ($this->items as $articles) {

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
    protected function initDataView()
    {
        $this->dataView = new \fpcm\components\dataView\dataView($this->getDataViewName());
        $this->dataView->addColumns($this->getDataViewCols());

        if (!count($this->items)) {
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
        
        $showCommentsStatus = $this->config->system_comments_enabled;
        $showSharesCount = $this->config->system_share_count;
        $showDeleteButton = $this->permissions->article->delete && !($this->isTrash ?? false);

        /* @var $article \fpcm\model\articles\article */
        foreach ($this->items as $articleMonth => $articles) {

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
                    ]
            ));

            foreach ($articles as $articleId => $article) {

                $buttons = (new \fpcm\view\helper\controlgroup('articlebuttons' . $article->getId() ))
                            ->addItem( (new \fpcm\view\helper\openButton('articlefe'))->setUrlbyObject($article)->setTarget('_blank') )
                            ->addItem( (new \fpcm\view\helper\editButton('articleedit'))->setUrlbyObject($article) );

                if (!$this->isTrash) {
                    $buttons->addItem( (new \fpcm\view\helper\clearArticleCacheButton('cac'))->setDatabyObject($article) );
                }

                if ($showDeleteButton) {

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

                /* @var $relatedCountItem \fpcm\model\articles\relatedCountItem */
                $relatedCountItem = $this->relatedCounts[$articleId] ?? null;

                $metaDataIcons = array_merge(
                    [$showCommentsStatus ? $this->getCommentBadge($relatedCountItem) : ''],
                    [$showSharesCount ? $this->getSharesBadge($relatedCountItem) : ''],
                    $article->getMetaDataStatusIcons($this->showDraftStatus, $showCommentsStatus, $this->showArchivedStatus)
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
     * @param \fpcm\model\articles\relatedCountItem|null $countItem
     * @return \fpcm\view\helper\badge
     */
    private function getCommentBadge(?\fpcm\model\articles\relatedCountItem $countItem)
    {
        $badge = new \fpcm\view\helper\badge('badge' . uniqid() );
       
        $privateUnapproved = $countItem !== null && $countItem->getPrivateUnapprovedComments() ? true : false;

        $badge->setClass(($privateUnapproved ? 'fpcm-ui-badge-red fpcm-ui-badge-comments' : 'fpcm-ui-badge-comments'))
                ->setText(($privateUnapproved ? 'ARTICLE_LIST_COMMENTNOTICE' : 'COMMMENT_HEADLINE'))
                ->setValue($countItem !== null ? $countItem->getComments() : 0)
                ->setIcon('comments');

        return $badge;
    }

    /**
     * 
     * @param int $articleId
     * @return \fpcm\view\helper\badge
     */
    private function getSharesBadge(?\fpcm\model\articles\relatedCountItem $countItem)
    {
        return (new \fpcm\view\helper\badge('badge' . uniqid() ))
            ->setClass('fpcm-ui-badge-comments')
            ->setText('EDITOR_SHARES')
            ->setValue($countItem !== null ? $countItem->getShares() : 0)
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
