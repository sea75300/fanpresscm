<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

/**
 * Article controller base
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class articlebase extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

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

        if ($this->buttonClicked('doAction') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }

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
                'editor/editor.js',
                'editor/editor_videolinks.js',
            ],
            $this->editorPlugin->getJsFiles()
        ));

        $this->view->addCssFiles($this->editorPlugin->getCssFiles());
        
        $this->view->addFromLibrary(
            'selectize_js',
            [ 'dist/js/selectize.min.js' ],
            [ 'dist/css/selectize.default.css' ]
        );

        $viewVars = $this->editorPlugin->getViewVars();
        foreach ($viewVars as $key => $value) {
            $this->view->assign($key, $value);
        }            

        $this->view->assign('changeAuthor', $this->canChangeAuthor);
        if ($this->canChangeAuthor) {
            $this->view->assign('changeuserList', (new \fpcm\model\users\userList())->getUsersNameList());
        }

        $this->view->assign('approvalRequired', $this->approvalRequired);
        $this->view->assign('article', $this->article);
        $this->view->assign('categories', $this->categoryList->getCategoriesNameListCurrent());
        $this->view->assign('commentEnabledGlobal', $this->config->system_comments_enabled);
        $this->view->assign('showArchiveStatus', true);
        $this->view->assign('showDraftStatus', true);
        $this->view->assign('userfields', $this->getUserFields());
        $this->view->assign('rollCodex', (new \fpcm\model\users\userRoll($this->session->getCurrentUser()->getRoll()))->getCodex());

        $twitter    = new \fpcm\model\system\twitter();
        $twitterOk  = $twitter->checkRequirements();
        $twitterReplacements = ['TEMPLATE_REPLACEMENTS' => '', 'tplCode' => ''];

        if ($twitterOk) {
            $twitterTpl = new \fpcm\model\pubtemplates\tweet();
            foreach ($twitterTpl->getReplacementTranslations('TEMPLATE_ARTICLE_') as $tag => $descr) {
                $twitterReplacements[$descr.': '.$tag] = $tag;
            }

            $twitterReplacements['tplCode'] = $twitterTpl->getContent();
        }

        $this->view->assign('twitterTplPlaceholder', $twitterReplacements['tplCode']);
        unset($twitterReplacements['tplCode']);

        $this->view->assign('twitterReplacements', $twitterReplacements);
        $this->view->assign('showTwitter', $twitterOk);
        $this->view->setActiveTab($this->getActiveTab());

        $this->jsVars  = $this->editorPlugin->getJsVars();
        $this->jsVars += array(
            'filemanagerUrl' => \fpcm\classes\tools::getFullControllerLink('files/list', ['mode' => '']),
            'filemanagerMode' => 2,
            'editorGalleryTagStart' => \fpcm\model\pubtemplates\article::GALLERY_TAG_START,
            'editorGalleryTagEnd' => \fpcm\model\pubtemplates\article::GALLERY_TAG_END,
            'editorGalleryTagThumb' => \fpcm\model\pubtemplates\article::GALLERY_TAG_THUMB,
            'editorGalleryTagLink' => \fpcm\model\pubtemplates\article::GALLERY_TAG_LINK
        );

        $this->view->addJsLangVars(array_merge(['HL_FILES_MNG', 'ARTICLES_SEARCH', 'FILE_LIST_NEWTHUMBS', 'GLOBAL_DELETE', 'EDITOR_CATEGORIES_SEARCH', 'FILE_LIST_INSERTGALLERY'], $this->editorPlugin->getJsLangVars()));
        $this->view->addJsVars($this->jsVars);

        $this->view->addButton((new \fpcm\view\helper\saveButton('articleSave'))
                ->setClass( 'fpcm-ui-maintoolbarbuttons-tab1')
                ->setReadonly($this->article->isInEdit())
                ->setPrimary($this->article->getId() > 0));

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
                ->setFile('articles/buttons.php')
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
     * @return bool
     */
    protected function saveArticle()
    {
        $res = false;

        $allTimer = time();

        if (!$this->buttonClicked('articleSave')) {
            return -1;
        }

        if ($this->article->getId()) {
            $this->article->prepareRevision();
        }

        $data = $this->request->fromPOST('article', [
            \fpcm\model\http\request::FILTER_STRIPSLASHES,
            \fpcm\model\http\request::FILTER_TRIM
        ]);

        $this->assignArticleFormData($data, $allTimer);

        if (!$this->article->getTitle() || !$this->article->getContent()) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLE_EMPTY');
            $this->emptyTitleContent = true;
            return false;
        }

        if (isset($data['tweettxt']) && $data['tweettxt']) {
            $this->article->setTweetOverride($data['tweettxt']);
        }

        if (!$this->article->getId() && !$this->article->getCreatetime()) {
            $this->article->setCreatetime($allTimer);
        }

        $this->article->setChangetime($allTimer);
        $this->article->setChangeuser($this->session->getUserId());

        $this->article->enableTweetCreation(isset($data['tweet']) ? true : false);
        $res = $this->article->getId() ? $this->article->update() : $this->article->save();

        if ($res && $this->article->getId()) {
            $this->article->createRevision();
        }

        \fpcm\model\articles\article::addSourcesAutocomplete($this->article->getSources());
        return $res;
    }

    /**
     * 
     * @param array $data
     * @param int $allTimer
     * @return bool
     */
    private function assignArticleFormData(array $data, $allTimer)
    {
        $this->article->setTitle($data['title']);
        $this->article->setContent($data['content']);

        $cats = $this->categoryList->getCategoriesCurrentUser();

        $categories = isset($data['categories']) && is_array($data['categories'])
                    ? array_intersect( array_keys($cats), array_map('intval', $data['categories']) )
                    : [array_shift($cats)->getId()];

        $this->article->setCategories($categories);

        if (!isset($data['archived']) && isset($data['postponed']) && \fpcm\classes\tools::validateDateString($data['postponedate'])) {
            $timer = strtotime($data['postponedate'] . ' ' . (int) $data['postponehour'] . ':' . (int) $data['postponeminute'] . ':00');

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

        $this->article->setPinned(isset($data['pinned']) ? 1 : 0);
        $this->article->setDraft(isset($data['draft']) ? 1 : 0);
        $this->article->setComments(isset($data['comments']) ? 1 : 0);
        
        $approval = $this->approvalRequired ? 1 : (isset($data['approval']) ? 1 : 0);
        $this->article->setApproval($approval);
        $this->article->setImagepath(isset($data['imagepath']) ? $data['imagepath'] : '');
        $this->article->setSources(isset($data['sources']) ? $data['sources'] : '');

        if ($this->permissions->article->archive) {
            $this->article->setArchived(isset($data['archived']) ? 1 : 0);
            if ($this->article->getArchived()) {
                $this->article->setPinned(0);
                $this->article->setDraft(0);
            }
        }
        
        
        $authorId = (isset($data['author']) && trim($data['author']) && $this->canChangeAuthor ? $data['author'] : $this->session->getUserId());
        $this->article->setCreateuser($authorId);

        return true;
    }

    /**
     * Liefert benutzerdefinierte Felder zurück, welche durch Module in Editor eingefügt werden können;
     * * möglich sind textarea, select, checkbox, radio, textinput
     * * nicht unterstütze Typen werden zu textinput
     * @return array
     */
    protected function getUserFields()
    {
        $fields = $this->events->trigger('editor\addUserFields');

        if (!is_array($fields) || !count($fields))
            return [];

        return $fields;
    }
}
