<?php

/**
 * Article list controller base
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles\articlelist;

abstract class base extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\articles\listsCommon,
        \fpcm\controller\traits\articles\listsView,
        \fpcm\controller\traits\common\massedit,
        \fpcm\controller\traits\common\searchParams,
        \fpcm\controller\traits\common\listSettings;


    /**
     * Liste mit erlaubten Artikel-Aktionen
     * @var array
     */
    protected $articleActions = [];

    /**
     *
     * @var bool
     */
    protected $deleteActions = false;

    /**
     *
     * @var bool
     */
    protected $canEdit = true;

    /**
     *
     * @var string
     */
    protected $listAction = '';

    /**
     *
     * @var string
     */
    protected $page = '';

    /**
     *
     * @var \fpcm\model\articles\search
     */
    protected $conditionItems;

    /**
     *
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'articles/listouter';
    }

    /**
     *
     * @return string
     */
    protected function getHelpLink()
    {
        return 'hl_article_edit';
    }

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();

        $this->initActionObjects();
        $this->initArticleActions();
        $this->initEditPermisions();
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->page = $this->request->getPage();
        $this->getListAction();


        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->view->addAjaxPageToken('articles/delete');
        $this->view->assign('users', array_flip($this->users));
        $this->view->assign('commentEnabledGlobal', $this->config->system_comments_enabled);
        $this->view->assign('showDraftStatus', $this->showDraftStatus());
        $this->view->assign('includeMassEditForm', true);

        $this->initSearchForm();
        $this->initMassEditForm();

        $this->view->addJsFiles(['articles/lists.js', 'articles/search.js', 'ui/dnd.js']);

        $buttons = [];

        if ($this->permissions->article->add) {
            $buttons[] = (new \fpcm\view\helper\linkButton('addArticle'))
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('articles/add'))
                ->setText('GLOBAL_NEW')
                ->setIcon('plus')
                ->setPrimary();
        }

        if ($this->permissions->editArticlesMass()) {
            $buttons[] = (new \fpcm\view\helper\button('massEdit', 'massEdit'))->setText('GLOBAL_EDIT')->setIcon('edit')->setIconOnly();
        }

        $buttons[] = (new \fpcm\view\helper\button('opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly();
        $buttons[] = (new \fpcm\view\helper\button('articlecache'))
                ->setText('ARTICLES_CACHE_CLEAR')
                ->setIcon('recycle')
                ->setIconOnly()
                ->setOnClick('articles.clearMultipleArticleCache');

        if ($this->permissions->article && $this->permissions->article->delete) {
            $buttons[] = (new \fpcm\view\helper\button('delete'))
                    ->setText('GLOBAL_DELETE')
                    ->setIcon('trash')
                    ->setIconOnly()
                    ->setOnClick('articles.deleteMultipleArticle');
        }

        $this->view->addPager((new \fpcm\view\helper\pager($this->listAction, $this->page, 1, $this->config->articles_acp_limit, 1)));
        $this->view->addButtons($buttons);
        $this->view->addJsVars([
            'listMode' => $this->getSearchMode(),
            'listPage' => $this->page ? $this->page : 1
        ]);

        $this->view->assign('searchMinDate', date('Y-m-d', $this->articleList->getMinMaxDate(1)['minDate']));

        $formActionParams = [];
        if ($this->page) {
            $formActionParams['page'] = $this->page;
        }

        $this->view->setFormAction($this->listAction, $formActionParams);
        $this->view->addDataView( new \fpcm\components\dataView\dataView('articlelist') );

        $this->addListSettingsDialog();

        $this->view->addTabs('articles', [
            (new \fpcm\view\helper\tabItem('articles'))->setText($this->getTabHeadline())->setFile('articles/listouter.php')
        ]);

        return true;
    }

    protected function initArticleActions()
    {
        if (!$this->permissions) {
            return false;
        }

        $this->articleActions['ARTICLES_CACHE_CLEAR'] = 'articlecache';

        $this->view->addJsVars([
            'artCacheMod' => urlencode(\fpcm\classes\loader::getObject('\fpcm\classes\crypt')->encrypt(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE))
        ]);
    }

    /**
     * Initialisiert Suchformular
     * @param array $users
     */
    private function initSearchForm()
    {
        $searchDlg = new \fpcm\view\helper\dialogs\search();
        $searchDlg->setFields([
            'valueFields' => [
                'title' => (new \fpcm\view\helper\textInput('title'))
                    ->setText('ARTICLE_SEARCH_TYPE_TITLE')
                    ->setLabelTypeFloat(),
                'content' => (new \fpcm\view\helper\textInput('content'))
                    ->setText('ARTICLE_SEARCH_TYPE_TEXT')
                    ->setLabelTypeFloat(),
                'category' => (new \fpcm\view\helper\select('category'))
                    ->setText('ARTICLE_SEARCH_CATEGORY')
                    ->setOptions(['GLOBAL_SELECT' => -1] + $this->categories)
                    ->setSelected(-1)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat(),
                'datefrom' => (new \fpcm\view\helper\dateTimeInput('datefrom'))
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setNativeDate()
                    ->setLabelTypeFloat(),
                'dateto' => (new \fpcm\view\helper\dateTimeInput('dateto'))
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setNativeDate()
                    ->setLabelTypeFloat(),
                'changefrom' => (new \fpcm\view\helper\dateTimeInput('changefrom'))
                    ->setText('ARTICLE_SEARCH_DATE_FROM_CHG')
                    ->setNativeDate()
                    ->setLabelTypeFloat(),
                'changeto' => (new \fpcm\view\helper\dateTimeInput('changeto'))
                    ->setText('ARTICLE_SEARCH_DATE_TO_CHG')
                    ->setNativeDate()
                    ->setLabelTypeFloat(),
                'draft' => (new \fpcm\view\helper\boolSelect('draft'))
                    ->setText('ARTICLE_SEARCH_DRAFT')
                    ->setSelected(-1)
                    ->setExtendedList()
                    ->setLabelTypeFloat(),
                'pinned' => (new \fpcm\view\helper\boolSelect('pinned'))
                    ->setText('ARTICLE_SEARCH_PINNED')
                    ->setSelected(-1)
                    ->setExtendedList()
                    ->setLabelTypeFloat(),
                'postponed' => (new \fpcm\view\helper\boolSelect('postponed'))
                    ->setText('ARTICLE_SEARCH_POSTPONED')
                    ->setSelected(-1)
                    ->setExtendedList()
                    ->setLabelTypeFloat(),
                'approval' => (new \fpcm\view\helper\boolSelect('approval'))
                    ->setText('ARTICLE_SEARCH_APPROVAL')
                    ->setSelected(-1)
                    ->setExtendedList()
                    ->setLabelTypeFloat(),
                'comments' => (new \fpcm\view\helper\boolSelect('comments'))
                    ->setText('ARTICLE_SEARCH_COMMENTS')
                    ->setSelected(-1)
                    ->setExtendedList()
                    ->setLabelTypeFloat(),
                'user' => (new \fpcm\view\helper\select('user'))
                    ->setText('ARTICLE_SEARCH_USER')
                    ->setOptions(['GLOBAL_SELECT' => -1] + $this->users)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat(),
                'changeuser' => (new \fpcm\view\helper\select('changeuser'))
                    ->setText('ARTICLE_SEARCH_CHGUSER')
                    ->setOptions(['GLOBAL_SELECT' => -1] + $this->users)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat(),
                'relates_to' => (new \fpcm\view\helper\dateTimeInput('relates_to'))
                    ->setText('LABEL_FIELD_ARTICLE_RELATESTO')
                    ->setType('number')
                    ->setLabelTypeFloat(),
                'sources' => (new \fpcm\view\helper\textInput('sources'))
                    ->setText('TEMPLATE_ARTICLE_SOURCES')
                    ->setLabelTypeFloat(),
            ],
            'buildFields' => [
                (new \fpcm\view\helper\button('cremove'))
                    ->setText('GLOBAL_REMOVE')
                    ->setIcon('minus')
                    ->setIconOnly()
                    ->setClass('btn-sm')
                    ->setLabelTypeFloat(),
                (new \fpcm\view\helper\select('combinations'))
                    ->setText('ARTICLE_SEARCH_LOGIC')
                    ->setOptions($searchDlg->getDefaultCombinations())
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected(-1)
                    ->setLabelTypeFloat(),
                (new \fpcm\view\helper\select('fields'))
                    ->setOptions([
                        'ARTICLE_SEARCH_TYPE_TITLE' => 'title',
                        'ARTICLE_SEARCH_TYPE_TEXT' => 'content',
                        'ARTICLE_SEARCH_USER' => 'user',
                        'ARTICLE_SEARCH_CHGUSER' => 'changeuser',
                        'ARTICLE_SEARCH_CATEGORY' => 'category',
                        'ARTICLE_SEARCH_DATE_FROM' => 'datefrom',
                        'ARTICLE_SEARCH_DATE_TO' => 'dateto',
                        'ARTICLE_SEARCH_DATE_FROM_CHG' => 'changefrom',
                        'ARTICLE_SEARCH_DATE_TO_CHG' => 'changeto',
                        'ARTICLE_SEARCH_DRAFT' => 'draft',
                        'ARTICLE_SEARCH_PINNED' => 'pinned',
                        'ARTICLE_SEARCH_POSTPONED' => 'postponed',
                        'ARTICLE_SEARCH_APPROVAL' => 'approval',
                        'ARTICLE_SEARCH_COMMENTS' => 'comments',
                        'LABEL_FIELD_ARTICLE_RELATESTO' => 'relates_to',
                        'TEMPLATE_ARTICLE_SOURCES' => 'sources',
                    ])
                    ->setLabelTypeFloat()
            ],
            'sortFields' => [
                (new \fpcm\view\helper\select('field'))
                    ->setText('GLOBAL_SORT_BY')
                    ->setOptions([
                        'ARTICLE_SEARCH_TYPE_TITLE' => 'title',
                        'ARTICLE_SEARCH_TYPE_TEXT' => 'content',
                        'ARTICLE_SEARCH_USER' => 'createuser',
                        'COMMMENT_CREATEDATE' => 'createtime',
                        'GLOBAL_LASTCHANGE' => 'changetime',
                        'ARTICLE_SEARCH_CHGUSER' => 'changeuser',
                        'ARTICLE_SEARCH_DRAFT' => 'draft',
                        'ARTICLE_SEARCH_PINNED' => 'pinned',
                        'ARTICLE_SEARCH_POSTPONED' => 'postponed',
                        'ARTICLE_SEARCH_APPROVAL' => 'approval',
                        'ARTICLE_SEARCH_COMMENTS' => 'comments',
                        'LABEL_FIELD_ARTICLE_RELATESTO' => 'relates_to'
                    ])
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected('createtime')
                    ->setLabelTypeFloat(),
                (new \fpcm\view\helper\select('order'))
                    ->setText('GLOBAL_SORT_ODER')
                    ->setOptions($this->language->translate('GLOBAL_SORTBY_LIST'))
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected('desc')
                    ->setLabelTypeFloat(),
            ]
        ]);

        $this->view->addDialogs($searchDlg);

        $this->view->addJsLangVars([
            'DELETE_FAILED_ARTICLE', 'ARTICLE_SEARCH_CATEGORY',
            'ARTICLE_SEARCH_TYPE_TITLE', 'ARTICLE_SEARCH_TYPE_TEXT',
            'ARTICLE_SEARCH_DATE_FROM', 'ARTICLE_SEARCH_DRAFT',
            'ARTICLE_SEARCH_PINNED', 'ARTICLE_SEARCH_POSTPONED',
            'ARTICLE_SEARCH_APPROVAL', 'ARTICLE_SEARCH_COMMENTS',
            'ARTICLE_SEARCH_DATE_FROM_CHG', 'ARTICLE_SEARCH_DATE_TO_CHG',
            'COMMMENT_CREATEDATE', 'GLOBAL_LASTCHANGE',
            'ARTICLE_SEARCH_CHGUSER', 'LABEL_FIELD_ARTICLE_RELATESTO',
            'TEMPLATE_ARTICLE_SOURCES'
        ]);

        $this->view->addJsVars(['articlesLastSearch' => 0]);

        $this->view->addFromLibrary('sortable_js/', [
            'Sortable.min.js'
        ]);
    }

    /**
     * Initialisiert Massenbearbeitung
     */
    private function initMassEditForm()
    {
        $this->view->assign('tabHeadline', $this->getTabHeadline());
        $this->view->assign('massEditCategories', $this->categories);
        $this->view->addJsVars(['massEditSaveFailed' => 'SAVE_FAILED_ARTICLES']);
        $this->view->addJsLangVars(['EDITOR_CATEGORIES_SEARCH']);

        $this->view->addFromLibrary(
            'tom-select_js',
            [ 'tom-select.min.js' ],
            [ 'tom-select.bootstrap5.min.css' ]
        );

        if (!$this->permissions->editArticlesMass()) {
            return [];
        }

        $this->assignPageToken('articles');

        $fields = [];

        if ($this->permissions->article->authors) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('createuser', 'meUserid'))
                    ->setOptions(['GLOBAL_NOCHANGE_APPLY' => -1] + $this->users)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('EDITOR_CHANGEAUTHOR')
                    ->setIcon('users')
                    ->setLabelTypeFloat()
            );
        }

        $fields[] = new \fpcm\components\masseditField(
            (new \fpcm\view\helper\select('pinned', 'mePinned'))
                ->setOptions($this->yesNoChangeList)
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setText('EDITOR_PINNED')
                ->setIcon('thumbtack fa-rotate-90')
                    ->setLabelTypeFloat()
        );

        if ($this->showDraftStatus()) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('draft', 'meDraft'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('EDITOR_DRAFT')
                    ->setIcon('file-alt')
                    ->setLabelTypeFloat()
            );
        }

        if (!$this->permissions->article->approve) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('approval', 'meApproval'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('EDITOR_STATUS_APPROVAL')
                    ->setIcon('thumbs-up', 'far')
                    ->setLabelTypeFloat()
            );
        }

        if ($this->config->system_comments_enabled && $this->permissions->editComments()) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('comments', 'meComments'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('EDITOR_COMMENTS')
                    ->setIcon('comments', 'far')
                    ->setLabelTypeFloat()
            );
        }

        if ($this->permissions->article->archive) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('archived', 'meArchived'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setText('EDITOR_ARCHIVE')
                    ->setIcon('archive')
                    ->setLabelTypeFloat()
            );
        }

        $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('categories[]'))
                    ->setIsMultiple(true)
                    ->setOptions($this->categories)
                    ->setText('')
                    ->setIcon('tags')
                    ->setSelected([]),
                null
        );

        $this->assignFields($fields);
    }

    /**
     *
     * @return string
     */
    protected function getTabHeadline() : string
    {
        return 'HL_ARTICLE_EDIT';
    }

    abstract protected function getListAction() : void;

    abstract protected function getSearchMode() : string;

    abstract protected function showDraftStatus() : bool;

}
