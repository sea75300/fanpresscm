<?php

/**
 * Article list controller base
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

abstract class articlelistbase extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\articles\listsCommon,
        \fpcm\controller\traits\articles\listsView,
        \fpcm\controller\traits\common\massedit,
        \fpcm\controller\traits\common\searchParams;


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
        $this->init();
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function init()
    {
        $this->getListAction();
        $this->getLimitsByPage();
//        $this->getConditionItem();
//        $this->getArticleCount();
//        $this->getArticleItems();
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

        $this->view->addJsFiles(['articles/list.js']);

        $buttons = [];

        if ($this->permissions->article->add) {
            $buttons[] = (new \fpcm\view\helper\linkButton('addArticle'))->setUrl(\fpcm\classes\tools::getFullControllerLink('articles/add'))->setText('HL_ARTICLE_ADD')->setIcon('pen-square')->setIconOnly(true)->setClass('fpcm-loader');
        }

        if ($this->permissions->editArticlesMass()) {
            $buttons[] = (new \fpcm\view\helper\button('massEdit', 'massEdit'))->setText('GLOBAL_EDIT')->setIcon('edit')->setIconOnly(true);
        }

        $buttons[] = (new \fpcm\view\helper\button('opensearch', 'opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly(true);
        $buttons[] = (new \fpcm\view\helper\select('action'))->setOptions($this->articleActions);
        $buttons[] = (new \fpcm\view\helper\submitButton('doAction'))->setText('GLOBAL_OK')->setClass('fpcm-ui-articleactions-ok')->setIcon('check')->setIconOnly(true)->setData(['hidespinner' => true]);
        
        $this->view->addPager((new \fpcm\view\helper\pager($this->listAction, $this->page, 0, $this->config->articles_acp_limit, 0)));
        $this->view->addButtons($buttons);
        $this->view->addJsVars([
            'listMode' => $this->getSearchMode(),
            'listName' => $this->getDataViewName(),
            'listPage' => $this->page ? $this->page : 1
        ]);

        $this->view->assign('searchMinDate', date('Y-m-d', $this->articleList->getMinMaxDate(1)['minDate']));

        $formActionParams = [];
        if ($this->page) {
            $formActionParams['page'] = $this->page;
        }
        
        $this->view->setFormAction($this->listAction, $formActionParams);
        $this->view->addDataView( new \fpcm\components\dataView\dataView('articlelist') );
        return true;
    }
    
    protected function getLimitsByPage()
    {
        $this->page = $this->request->getPage();
    }

    protected function initArticleActions()
    {
        if (!$this->permissions) {
            return false;
        }

        $tweet = new \fpcm\model\system\twitter();

        if ($tweet->checkRequirements() && $tweet->checkConnection()) {
            $this->articleActions['ARTICLE_LIST_NEWTWEET'] = 'newtweet';
        }

        if ($this->permissions->article && $this->permissions->article->delete) {
            $this->articleActions['GLOBAL_DELETE'] = 'delete';
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
        $this->view->assign('searchUsers', ['ARTICLE_SEARCH_USER' => -1] + $this->users);
        $this->view->assign('searchCategories', ['ARTICLE_SEARCH_CATEGORY' => -1] + $this->categories);
        
        $this->assignSearchFromVars();

        $this->view->assign('searchTypes', [
            'ARTICLE_SEARCH_TYPE_ALL' => \fpcm\model\articles\search::TYPE_COMBINED,
            'ARTICLE_SEARCH_TYPE_ALLOR' => \fpcm\model\articles\search::TYPE_COMBINED_OR,
            'ARTICLE_SEARCH_TYPE_TITLE' => \fpcm\model\articles\search::TYPE_TITLE,
            'ARTICLE_SEARCH_TYPE_TEXT' => \fpcm\model\articles\search::TYPE_CONTENT
        ]);

        $this->view->assign('searchPinned', [
            'ARTICLE_SEARCH_PINNED' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('searchPostponed', [
            'ARTICLE_SEARCH_POSTPONED' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('searchComments', [
            'ARTICLE_SEARCH_COMMENTS' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('searchApproval', [
            'ARTICLE_SEARCH_APPROVAL' => -1,
            'GLOBAL_YES' => 1,
            'GLOBAL_NO' => 0
        ]);

        $this->view->assign('searchDraft', [
            'ARTICLE_SEARCH_DRAFT' => -1,
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
        $this->view->addJsFiles([\fpcm\classes\loader::libGetFileUrl('selectize_js/dist/js/selectize.min.js')]);
        $this->view->addCssFiles([\fpcm\classes\loader::libGetFileUrl('selectize_js/dist/css/selectize.default.css')]);

        if (!$this->permissions->editArticlesMass()) {
            return [];
        }
        
        $this->assignPageToken('articles');
        
        $fields = [];
        
        if ($this->permissions->article->authors) {
            $fields[] = new \fpcm\components\masseditField(
                'users',
                'EDITOR_CHANGEAUTHOR',
                (new \fpcm\view\helper\select('userid'))
                    ->setOptions(['GLOBAL_NOCHANGE_APPLY' => -1] + $this->users)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit')
            );
        }

        $fields[] = new \fpcm\components\masseditField(
            'thumbtack fa-rotate-90',
            'EDITOR_PINNED',
            (new \fpcm\view\helper\select('pinned'))
                ->setOptions($this->yesNoChangeList)
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setClass('fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'),
                'col-sm-6 col-md-4'
        );
        
        if ($this->showDraftStatus()) {
            $fields[] = new \fpcm\components\masseditField(
                ['icon' => 'file-alt'],
                'EDITOR_DRAFT',
                (new \fpcm\view\helper\select('draft'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'),
                    'col-sm-6 col-md-4'
            );
        }
        
        if (!$this->permissions->article->approve) {
            $fields[] = new \fpcm\components\masseditField(
                ['icon' => 'thumbs-up', 'prefix' => 'far'],
                'EDITOR_STATUS_APPROVAL',
                (new \fpcm\view\helper\select('approval'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'),
                    'col-sm-6 col-md-4'
            );
        }
        
        if ($this->config->system_comments_enabled && $this->permissions->editComments()) {
            $fields[] = new \fpcm\components\masseditField(
                ['icon' => 'comments', 'prefix' => 'far'],
                'EDITOR_COMMENTS',
                (new \fpcm\view\helper\select('comments'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'),
                    'col-sm-6 col-md-4'
            );
        }
        
        if ($this->permissions->article->archive) {
            $fields[] = new \fpcm\components\masseditField(
                'archive',
                'EDITOR_ARCHIVE',
                (new \fpcm\view\helper\select('archived'))
                    ->setOptions($this->yesNoChangeList)
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setClass('fpcm-articles-search-input fpcm-ui-input-select-massedit fpcm-ui-input-massedit'),
                    'col-sm-6 col-md-4'
            );
        }

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

?>
