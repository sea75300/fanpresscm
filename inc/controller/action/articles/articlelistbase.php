<?php

/**
 * Article list controller base
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

abstract class articlelistbase extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\articles\lists,
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
        if (($this->buttonClicked('doAction') || $this->buttonClicked('clearTrash')) && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return $this->init();
        }

        if ($this->buttonClicked('doAction') && !is_null($this->getRequestVar('actions'))) {

            $actionData = $this->getRequestVar('actions');

            if ($actionData['action'] === 'trash') {

                if (!$this->doTrash()) {
                    $this->view->addErrorMessage('DELETE_FAILED_TRASH');
                } else {
                    $this->view->addNoticeMessage('DELETE_SUCCESS_TRASH');
                }

                return $this->init();
            }


            if ((!isset($actionData['ids']) && $actionData['action'] != 'trash') || !$actionData['action']) {
                $this->view->addErrorMessage('SELECT_ITEMS_MSG');
                return $this->init();
            }

            $ids = array_map('intval', $actionData['ids']);

            $action = in_array($actionData['action'], array_values($this->articleActions)) ? $actionData['action'] : false;

            if ($action === false) {
                $this->view->addErrorMessage('SELECT_ITEMS_MSG');
                return $this->init();
            }

            if (!call_user_func([$this, 'do' . ucfirst($action)], $ids)) {
                $msg = ($action == 'delete') ? 'DELETE_FAILED_ARTICLE' : 'SAVE_FAILED_ARTICLE';
                $this->view->addErrorMessage($msg);
                return $this->init();
            }

            $msg = ($action == 'delete') ? 'DELETE_SUCCESS_ARTICLE' : 'SAVE_SUCCESS_ARTICLE' . strtoupper($action);
            $this->view->addNoticeMessage($msg);
        }

        return $this->init();
    }

    /**
     * 
     * @return bool
     */
    private function init()
    {
        $this->getListAction();
        $this->getLimitsByPage();
        $this->getConditionItem();
        $this->getArticleCount();
        $this->getArticleItems();
        
        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->initActionVars();

        $this->view->addAjaxPageToken('articles/delete');
        $this->view->assign('users', array_flip($this->users));
        $this->view->assign('commentEnabledGlobal', $this->config->system_comments_enabled);
        $this->view->assign('showDraftStatus', $this->showDraftStatus);

        $this->initSearchForm();
        $this->initMassEditForm();

        $this->view->addJsFiles(['articlelist.js']);

        $buttons = [];

        if ($this->listAction !== 'articles/trash') {

            if ($this->permissions->check(['article' => 'add'])) {
                $buttons[] = (new \fpcm\view\helper\linkButton('addArticle'))->setUrl(\fpcm\classes\tools::getFullControllerLink('articles/add'))->setText('HL_ARTICLE_ADD')->setIcon('pen-square')->setIconOnly(true)->setClass('fpcm-loader');
            }

            if ($this->canEdit && $this->permissions->check(['article' => 'massedit'])) {
                $buttons[] = (new \fpcm\view\helper\button('massEdit', 'massEdit'))->setText('GLOBAL_EDIT')->setIcon('edit')->setIconOnly(true);
            }

            $buttons[] = (new \fpcm\view\helper\button('opensearch', 'opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly(true);
        }

        $buttons[] = (new \fpcm\view\helper\select('actions[action]'))->setOptions($this->articleActions);
        $buttons[] = (new \fpcm\view\helper\submitButton('doAction'))->setText('GLOBAL_OK')->setClass('fpcm-loader fpcm-ui-articleactions-ok')->setIcon('check')->setIconOnly(true)->setData([
            'hidespinner' => $this->listAction !== 'articles/trash' ? true :  false
        ]);
        
        if ($this->listAction !== 'articles/trash') {
            $this->view->addPager((new \fpcm\view\helper\pager($this->listAction, $this->page, count($this->articleItems), $this->config->articles_acp_limit, $this->articleCount)));
        }
        
        $this->view->addButtons($buttons);
        $this->view->addJsVars(['articleSearchMode' => $this->getSearchMode()]);
        $this->view->assign('searchMinDate', date('Y-m-d', $this->articleList->getMinMaxDate(1)['minDate']));

        $formActionParams = [];
        if ($this->page) {
            $formActionParams['page'] = $this->page;
        }
        
        $this->view->setFormAction($this->listAction, $formActionParams);
        
        $this->translateCategories();

        $this->initDataView();
        $this->view->addDataView($this->dataView);

        return true;
    }

    /**
     * Papierkorb leeren
     * @return bool
     */
    protected function doTrash()
    {
        if (!$this->deleteActions) {
            return false;
        }

        return $this->articleList->emptyTrash();
    }

    /**
     * Artikel aus Papierkorb wiederherstellen
     * @param array $ids
     * @return bool
     */
    protected function doRestore(array $ids)
    {
        if (!$this->deleteActions) {
            return false;
        }
        
        return $this->articleList->restoreArticles($ids);
    }
    
    protected function getLimitsByPage()
    {
        $this->page          = $this->getRequestVar('page', [\fpcm\classes\http::FILTER_CASTINT]);
        $this->listShowStart = \fpcm\classes\tools::getPageOffset($this->page, $this->config->articles_acp_limit);
    }

    protected function initArticleActions()
    {
        if (!$this->permissions) {
            return false;
        }

        $this->canEdit = $this->permissions->check(['article' => ['edit', 'editall', 'approve', 'archive']]);
        $this->deleteActions = $this->permissions->check(['article' => 'delete']);

        $this->view->assign('canEdit', $this->canEdit);

        $tweet = new \fpcm\model\system\twitter();

        if ($tweet->checkRequirements() && $tweet->checkConnection()) {
            $this->articleActions['ARTICLE_LIST_NEWTWEET'] = 'newtweet';
        }

        if ($this->deleteActions) {
            $this->articleActions['GLOBAL_DELETE'] = 'delete';
        }

        $this->articleActions['ARTICLES_CACHE_CLEAR'] = 'articlecache';

        $crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');
        $this->view->addJsVars(['artCacheMod' => urlencode($crypt->encrypt(\fpcm\model\articles\article::CACHE_ARTICLE_MODULE))]);
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
        $this->assignPageToken('articles');
        
        $fields = [];
        
        if ($this->permissions->check(['article' => 'authors'])) {
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
        
        if ($this->showDraftStatus) {
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
        
        if ($this->permissions->check(['article' => 'approve'])) {
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
        
        if ($this->config->system_comments_enabled) {
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
        
        if ($this->permissions->check(['article' => 'archive'])) {
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

        $this->view->assign('tabHeadline', $this->getTabHeadline());
        $this->view->assign('massEditCategories', $this->categories);
        $this->view->addJsVars(['massEditSaveFailed' => 'SAVE_FAILED_ARTICLES']);
        $this->view->addJsLangVars(['EDITOR_CATEGORIES_SEARCH']);
        $this->view->addJsFiles([\fpcm\classes\loader::libGetFileUrl('selectize_js/dist/js/selectize.min.js')]);
        $this->view->addCssFiles([\fpcm\classes\loader::libGetFileUrl('selectize_js/dist/css/selectize.default.css')]);
    }

    /**
     * 
     * @return string
     */
    protected function getTabHeadline() : string
    {
        return 'HL_ARTICLE_EDIT';
    }

    abstract protected function getArticleCount();

    abstract protected function getArticleItems();

    abstract protected function getConditionItem();

    abstract protected function getListAction();

    abstract protected function getSearchMode();

}

?>
