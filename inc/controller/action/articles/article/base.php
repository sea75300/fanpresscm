<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles\article;

/**
 * Article controller base
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class base extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\requestFunctions
{

    use \fpcm\controller\traits\articles\newteets;

    /**
     *
     * @var \fpcm\model\articles\article
     */
    protected $article;

    /**
     *
     * @var array
     */
    protected $jsVars = [];

    /**
     *
     * @var string
     */
    protected $editorFile;

    /**
     *
     * @var \fpcm\model\categories\categoryList
     */
    protected $categoryList;

    /**
     *
     * @var \fpcm\model\abstracts\articleEditor
     */
    protected $editorPlugin;

    /**
     *
     * @var bool
     */
    protected $approvalRequired = false;

    /**
     *
     * @var bool
     */
    protected $emptyTitleContent = false;

    /**
     *
     * @var bool
     */
    protected $canChangeAuthor = false;

    /**
     *
     * @var bool
     */
    protected $showComments = true;

    /**
     *
     * @var bool
     */
    protected $showRevisions = false;

    /**
     *
     * @var bool
     */
    protected $commentCount = 0;

    /**
     *
     * @var bool
     */
    protected $revisionCount = 0;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        parent::__construct();
        $this->categoryList = new \fpcm\model\categories\categoryList();
    }

    /**
     *
     * @return string
     */
    protected function getHelpLink()
    {
        return 'articles_editor';
    }

    /**
     * see \fpcm\controller\abstracts\controller::getViewPath
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'articles/editor';
    }

    /**
     *
     * @return void
     */
    protected function initObject()
    {
        $id = $this->request->getID();
        if (!$id) {
            $id = null;
        }

        $this->article = new \fpcm\model\articles\article($id);
    }

    /**
     *
     * @return void
     */
    public function request()
    {
        $this->canChangeAuthor = $this->permissions->article->authors;
        $this->approvalRequired = $this->permissions->article->approve;
        $this->initObject();

        return true;
    }

    /**
     *
     * @return void
     */
    public function process()
    {
        $this->initTabs();

        $this->editorPlugin = \fpcm\components\components::getArticleEditor();
        if (!$this->editorPlugin) {
            $this->view = new \fpcm\view\error('Error loading article editor component '.$this->config->system_editor);
            $this->view->render();
        }

        $this->view->addJsFiles(array_merge([
                'articles/articlebase.js',
                'editor/editor.js',
                'editor/editor_videolinks.js',
            ],
            $this->editorPlugin->getJsFiles()
        ));

        $this->view->addCssFiles($this->editorPlugin->getCssFiles());

        $this->view->addFromLibrary(
            'tom-select_js',
            [ 'tom-select.min.js' ],
            [ 'tom-select.bootstrap5.min.css' ]
        );

        $viewVars = $this->editorPlugin->getViewVars();
        foreach ($viewVars as $key => $value) {
            $this->view->assign($key, $value);
        }

        $this->view->assign('changeAuthor', $this->canChangeAuthor);
        if ($this->canChangeAuthor) {
            $this->view->assign('changeuserList', (new \fpcm\model\users\userList())->getUsersNameList());
        }

        if (!$this->article->getId()) {
            $this->article->setComments($this->config->comments_default_active);
        }

        $this->view->assign('approvalRequired', $this->approvalRequired);
        $this->view->assign('article', $this->article);
        $this->view->assign('categories', $this->categoryList->getCategoriesNameListCurrent());
        $this->view->assign('commentEnabledGlobal', $this->config->system_comments_enabled);
        $this->view->assign('showDraftStatus', true);
        $this->view->assign('userfields', $this->getUserFields());
        $this->view->assign('rollCodex', (new \fpcm\model\users\userRoll($this->session->getCurrentUser()->getRoll()))->getCodex());

        $this->view->assign('urlRewrite', $this->config->articles_link_urlrewrite);
        $this->view->setActiveTab($this->getActiveTab());

        $this->jsVars  = $this->editorPlugin->getJsVars();
        $this->jsVars += array(
            'filemanagerUrl' => \fpcm\classes\tools::getFullControllerLink('files/list', ['mode' => '']),
            'filemanagerMode' => 2,
            'filemanagerPermissions' => $this->permissions->uploads,
            'editorGalleryTagStart' => \fpcm\model\pubtemplates\article::GALLERY_TAG_START,
            'editorGalleryTagEnd' => \fpcm\model\pubtemplates\article::GALLERY_TAG_END,
            'editorGalleryTagThumb' => \fpcm\model\pubtemplates\article::GALLERY_TAG_THUMB,
            'editorGalleryTagLink' => \fpcm\model\pubtemplates\article::GALLERY_TAG_LINK
        );

        $this->view->addJsVars($this->jsVars);

        $this->view->addJsLangVars(array_merge([
            'HL_FILES_MNG', 'ARTICLES_SEARCH', 'FILE_LIST_NEWTHUMBS', 'GLOBAL_DELETE',
            'EDITOR_CATEGORIES_SEARCH', 'FILE_LIST_INSERTGALLERY', 'FILE_LIST_UPLOADFORM',
            'SYSTEM_OPTIONS_NEWS_SOURCESLIST'
        ], $this->editorPlugin->getJsLangVars()));


        $this->view->addButton(
            (new \fpcm\view\helper\saveButton('articleSave'))
                ->setClass( $this->getToolbarButtonToggleClass(1, '', true) )
                ->setReadonly($this->article->isInEdit())
                ->setPrimary()
        );

        return true;
    }

    private function initTabs()
    {

        $tabs = [];

        $tabs[] = (new \fpcm\view\helper\tabItem('editor'))
                ->setFile('articles/editor.php')
                ->setText('ARTICLES_EDITOR')
                ->setTabToolbar(1);

        $tabs[] = (new \fpcm\view\helper\tabItem('extended'))
                ->setFile('articles/extended.php')
                ->setText('GLOBAL_EXTENDED')
                ->setTabToolbar(1);

        if ($this->showComments && $this->config->system_comments_enabled) {

            $tabs[] = (new \fpcm\view\helper\tabItem('comments'))
                    ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/editor/editorlist', [
                        'id' => $this->article->getId(),
                        'view' => 'comments'])
                    )
                    ->setText('HL_ARTICLE_EDIT_COMMENTS', [ 'count' => $this->commentCount ])
                    ->setDataViewId('commentlist')
                    ->setTabToolbar(2);

        }

        if ($this->showRevisions) {

            $tabs[] = (new \fpcm\view\helper\tabItem('revisions'))
                    ->setUrl(\fpcm\classes\tools::getFullcontrollerLink('ajax/editor/editorlist', [
                        'id' => $this->article->getId(),
                        'view' => 'revisions'])
                    )
                    ->setText('HL_ARTICLE_EDIT_REVISIONS', [ 'count' => $this->revisionCount ])
                    ->setDataViewId('revisionslist')
                    ->setTabToolbar(3);

        }


        $this->view->addTabs('tabs-editor', $tabs, '', $this->getActiveTab());

    }

    /**
     *
     * @param array $data
     * @param int $allTimer
     * @return bool
     */
    protected function assignArticleFormData(array $data, $allTimer)
    {
        $this->article->setTitle($data['title']);
        $this->article->setContent($data['content']);

        $cats = $this->categoryList->getCategoriesCurrentUser();

        $categories = isset($data['categories']) && is_array($data['categories'])
                    ? array_intersect( array_keys($cats), array_map('intval', $data['categories']) )
                    : [array_shift($cats)->getId()];

        $this->article->setCategories($categories);

        if (!isset($data['archived']) && isset($data['postponed']) && \fpcm\classes\tools::validateDateString($data['postponedate'])) {
            $timer = strtotime($data['postponedate'] . ' ' . $data['postponetime'] . ':00');

            $postpone = 1;
            if ($timer === false) {
                trigger_error('Error while processing postponed date-time information');
                $timer = $allTimer;
                $postpone = 0;
            }

            $this->article->setPostponed($postpone);
            $this->article->setCreatetime($timer);
        } else {
            if ($this->article->getPostponed() || ($this->article->getDraft() && !isset($data['draft']))) {
                $this->article->setCreatetime($allTimer);
            }

            $this->article->setPostponed(0);
        }

        $this->article->setPinned($data['pinned'] ?? 0);
        $this->article->setDraft($data['draft'] ?? 0);

        if ($this->article->getPinned() &&
            isset($data['pinned_until']) && trim($data['pinned_until']) &&
            \fpcm\classes\tools::validateDateString($data['pinned_until'])
        ) {
            $puDt = new \DateTime($data['pinned_until']);
            $puDt->setTime(23, 59, 59);
            $this->article->setPinnedUntil($puDt->getTimestamp());
        }
        else {
            $this->article->setPinnedUntil(0);
        }

        $this->article->setComments($data['comments'] ?? $this->config->comments_default_active);

        $relates_to = intval($data['relatesto'] ?? 0);
        if ($relates_to && $relates_to !== $this->article->getId()) {
            $this->article->setRelatesTo($relates_to);
        }

        $this->article->setApproval($this->approvalRequired ? 1 : ( $data['approval'] ?? 0 ));
        $this->article->setImagepath($data['imagepath'] ?? '');
        $this->article->setSources($data['sources'] ?? '');
        $this->article->setUrl($data['url'] ?? '');

        if ($this->permissions->article->archive) {
            $this->article->setArchived($data['archived'] ?? 0);
            if ($this->article->getArchived()) {
                $this->article->setPinned(0);
                $this->article->setPinnedUntil(0);
                $this->article->setDraft(0);
            }
        }

        if (!$this->canChangeAuthor || empty($data['author'])) {
            $this->article->setCreateuser($this->session->getUserId());
            return true;
        }

        $this->article->setCreateuser((int) $data['author']);
        return true;
    }

    /**
     * Liefert benutzerdefinierte Felder zurück, welche durch Module in Editor eingefügt werden können;
     * * möglich sind textarea, select, checkbox, radio, textinput
     * * nicht unterstütze Typen werden zu textinput
     * @return array
     */
    protected function getUserFields() : array
    {
        $ev = $this->events->trigger('editor\addUserFields');
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event editor\addUserFields failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return [];
        }

        $fields = $ev->getData();
        if (!is_array($fields) || !count($fields)) {
            return [];
        }

        return $fields;
    }

    /**
     *
     * @return bool
     */
    protected function onArticleSave() : bool
    {
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $allTimer = time();

        $data = $this->request->fromPOST('article', [
            \fpcm\model\http\request::FILTER_STRIPSLASHES,
            \fpcm\model\http\request::FILTER_TRIM
        ]);

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }

        if ($this->article->getId()) {
            $this->article->prepareRevision();
        }

        $this->assignArticleFormData($data, $allTimer);

        $fn = 'save';
        if ($this->article->getId()) {

            $fn = 'update';

            if (!$this->article->getEditPermission()) {
                return false;
            }

            if ($this->article->isInEdit()) {
                return false;
            }

        }
        elseif (!$this->article->getCreatetime()) {
            $this->article->setCreatetime($allTimer);
        }

        if (!$this->article->getTitle() || !$this->article->getContent()) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLE_EMPTY');
            return false;
        }

        $this->article->setChangetime($allTimer);
        $this->article->setChangeuser($this->session->getUserId());

        $res = $this->article->{$fn}();

        if ($res === false) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLE');
            return false;
        }

        \fpcm\model\articles\article::addSourcesAutocomplete($this->article->getSources());

        $this->onArticleSaveAfterSuccess((int) $res);
        return true;
    }

    abstract protected function onArticleSaveAfterSuccess(int $id) : bool;

}
