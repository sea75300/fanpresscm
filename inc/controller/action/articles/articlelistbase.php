<?php

/**
 * Article list controller base
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

abstract class articlelistbase extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\articles\listsCommon,
        \fpcm\controller\traits\articles\listsView,
        \fpcm\controller\traits\common\massedit,
        \fpcm\controller\traits\common\searchParams,
        \fpcm\controller\traits\articles\newteets;


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
        $this->view->assign('includeSearchForm', true);
        $this->view->assign('includeMassEditForm', true);

        $this->initSearchForm();
        $this->initMassEditForm();

        $this->view->addJsFiles(['articles/lists.js']);

        $buttons = [];

        if ($this->permissions->article->add) {
            $buttons[] = (new \fpcm\view\helper\linkButton('addArticle'))->setUrl(\fpcm\classes\tools::getFullControllerLink('articles/add'))->setText('GLOBAL_NEW')->setIcon('plus');
        }

        if ($this->permissions->editArticlesMass()) {
            $buttons[] = (new \fpcm\view\helper\button('massEdit', 'massEdit'))->setText('GLOBAL_EDIT')->setIcon('edit')->setIconOnly(true);
        }

        $buttons[] = (new \fpcm\view\helper\button('opensearch', 'opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly(true);
        
        $tweet = $this->getTwitterInstace();

        if ($tweet->checkConnection()) {
            $buttons[] = (new \fpcm\view\helper\button('newtweet'))
                    ->setText('ARTICLE_LIST_NEWTWEET')
                    ->setIcon('twitter', 'fab')
                    ->setIconOnly(true)
                    ->setOnClick('articles.articleActionsTweet');
            
            $this->view->addJsLangVars(['EDITOR_TWEET_TEXT', 'ARTICLE_LIST_NEWTWEET']);
            
            $ttpl = $this->getTemplateContent();
            
            $this->view->addJsVars([
                'newTweetFields' => [
                    (string) (new \fpcm\view\helper\textInput('twitterText'))
                        ->setPlaceholder($ttpl['tpl'])
                        ->setText($ttpl['tpl'])
                        ->setLabelTypeFloat()
                        ->setValue('')
                        ->setSize(280)
                        ->setIcon('twitter', 'fab')
                        ->setSize('lg'),
                    (string) (new \fpcm\view\helper\dropdown('twitterReplacements'))
                        ->setOptions($ttpl['vars'])
                        ->setSelected('')
                        ->setText('TEMPLATE_REPLACEMENTS')
                        ->setDdType('end')
                        ->setIcon('square-plus')
                        ->setIconOnly()
                ]
            ]);
            
        }

        $buttons[] = (new \fpcm\view\helper\button('articlecache'))
                ->setText('ARTICLES_CACHE_CLEAR')
                ->setIcon('recycle')
                ->setIconOnly(true)
                ->setOnClick('articles.clearMultipleArticleCache');

        if ($this->permissions->article && $this->permissions->article->delete) {
            $buttons[] = (new \fpcm\view\helper\button('delete'))
                    ->setText('GLOBAL_DELETE')
                    ->setIcon('trash')
                    ->setIconOnly(true)
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
        $this->view->assign('searchUsers', ['' => -1] + $this->users);
        $this->view->assign('searchCategories', ['' => -1] + $this->categories);
        
        $this->assignSearchFromVars();

        $this->view->assign('searchTypes', [
            'ARTICLE_SEARCH_TYPE_ALL' => \fpcm\model\articles\search::TYPE_COMBINED,
            'ARTICLE_SEARCH_TYPE_ALLOR' => \fpcm\model\articles\search::TYPE_COMBINED_OR,
            'ARTICLE_SEARCH_TYPE_TITLE' => \fpcm\model\articles\search::TYPE_TITLE,
            'ARTICLE_SEARCH_TYPE_TEXT' => \fpcm\model\articles\search::TYPE_CONTENT
        ]);

        $this->view->assign('searchPinned', [
            '' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('searchPostponed', [
            '' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('searchComments', [
            '' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('searchApproval', [
            '' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('searchDraft', [
            '' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->addJsLangVars(['DELETE_FAILED_ARTICLE']);
        $this->view->addJsVars(['articlesLastSearch' => 0]);
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
            [ 'tom-select-bootstrap5.min.css' ]
        );   

        if (!$this->permissions->editArticlesMass()) {
            return [];
        }
        
        $this->assignPageToken('articles');
        
        $fields = [];
        
        if ($this->permissions->article->authors) {
            $fields[] = new \fpcm\components\masseditField(
                (new \fpcm\view\helper\select('userid', 'meUserid'))
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
