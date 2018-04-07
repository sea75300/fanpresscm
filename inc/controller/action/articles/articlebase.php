<?php

/**
 * Article controller base
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

class articlebase extends \fpcm\controller\abstracts\controller {

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
    protected $showRevision = false;

    /**
     *
     * @var bool
     */
    protected $approvalRequired = false;

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
    protected function getViewPath()
    {
        return 'articles/editor';
    }

    /**
     * 
     * @return void
     */
    protected function initObject()
    {
        $id = $this->getRequestVar('articleid', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        $this->article = new \fpcm\model\articles\article($id);
    }

    /**
     * 
     * @return void
     */
    public function request()
    {
        $this->approvalRequired = $this->permissions->check(['article' => 'approve']);
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
        $this->editorPlugin = \fpcm\components\components::getArticleEditor();
        if (!$this->editorPlugin) {
            $this->view = new \fpcm\view\error('Error loading article editor component '.$this->config->system_editor);
            $this->view->render();
        }
        
        $this->view->addJsFiles(array_merge([
                'editor.js',
                'editor_videolinks.js'
            ],
            $this->editorPlugin->getJsFiles()
        ));

        $this->view->addCssFiles($this->editorPlugin->getCssFiles());

        if (!$this->showRevision) {
            $viewVars = $this->editorPlugin->getViewVars();
            foreach ($viewVars as $key => $value) {
                $this->view->assign($key, $value);
            }            
        }

        $changeAuthor = $this->permissions->check(['article' => 'authors']);
        $this->view->assign('changeAuthor', $changeAuthor);
        if ($changeAuthor) {
            $userlist = new \fpcm\model\users\userList();
            $changeuserList = ['EDITOR_CHANGEAUTHOR' => ''] + $userlist->getUsersNameList();
            $this->view->assign('changeuserList', $changeuserList);
        }

        $this->view->assign('approvalRequired', $this->approvalRequired);
        $this->view->assign('isRevision', false);
        $this->view->assign('article', $this->article);
        $this->view->assign('categories', $this->categoryList->getCategoriesCurrentUser());
        $this->view->assign('commentEnabledGlobal', $this->config->system_comments_enabled);
        $this->view->assign('showArchiveStatus', true);
        $this->view->assign('showDraftStatus', true);
        $this->view->assign('userfields', $this->getUserFields());

        $twitter    = new \fpcm\model\system\twitter();
        $twitterOk  = $twitter->checkRequirements();

        $twitterReplacements = '';
        if ($twitterOk) {
            $tweetTpl = new \fpcm\model\pubtemplates\tweet();
            $tags = $tweetTpl->getReplacementTranslations('TEMPLATE_ARTICLE_');

            $twitterReplacements = [];
            foreach ($tags as $tag => $descr) {
                $twitterReplacements[] = $descr . ': ' . $tag;
            }


            $twitterReplacements = implode(' &bull; ' . PHP_EOL . ' ', $twitterReplacements);
        }

        $this->view->assign('twitterReplacements', $twitterReplacements);
        $this->view->assign('showTwitter', $twitterOk);

        $this->jsVars  = $this->editorPlugin->getJsVars();
        $this->jsVars += array(
            'filemanagerUrl' => \fpcm\classes\tools::getFullControllerLink('files/list', [
                'mode' => ''
            ]),
            'filemanagerMode' => 2
        );

        $this->view->addJsLangVars(array_merge(['HL_FILES_MNG', 'ARTICLES_SEARCH'], $this->editorPlugin->getJsLangVars()));
        $this->view->addJsVars($this->jsVars);

        if (!$this->getRequestVar('rev')) {
            $this->view->addButton((new \fpcm\view\helper\saveButton('articleSave'))->setClass('fpcm-ui-maintoolbarbuttons-tab1'));
        }

        return true;
    }

    /**
     * 
     * @return boolean
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

        $data = $this->getRequestVar('article', [
            \fpcm\classes\http::FILTER_STRIPSLASHES,
            \fpcm\classes\http::FILTER_TRIM
        ]);

        $this->assignArticleFormData($data, $allTimer);

        if (!$this->article->getTitle() || !$this->article->getContent()) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLE_EMPTY');
            return false;
        }

        if (isset($data['tweettxt']) && $data['tweettxt']) {
            $this->article->setTweetOverride($data['tweettxt']);
        }

        if (!$this->article->getId()) {
            $this->article->setCreatetime($allTimer);
        }

        $this->article->setChangetime($allTimer);
        $this->article->setChangeuser($this->session->getUserId());
        $this->article->setMd5path($this->article->getArticleNicePath());

        $this->article->enableTweetCreation(isset($data['tweet']) ? true : false);
        $res = $this->article->getId() ? $this->article->update() : $this->article->save();

        if ($res && $this->article->getId()) {
            $this->article->createRevision();
        }

        return $res;
    }

    /**
     * 
     * @param array $data
     * @param int $allTimer
     * @return boolean
     */
    private function assignArticleFormData(array $data, $allTimer)
    {
        $this->article->setTitle($data['title']);
        $this->article->setContent($data['content']);

        $cats = $this->categoryList->getCategoriesCurrentUser();

        $categories = isset($data['categories']) && is_array($data['categories']) ? array_map('intval', $data['categories']) : [array_shift($cats)->getId()];
        $this->article->setCategories($categories);

        if (isset($data['postponed']) && !isset($data['archived'])) {
            $timer = strtotime($data['postponedate'] . ' ' . (int) $data['postponehour'] . ':' . (int) $data['postponeminute'] . ':00');

            $postpone = 1;
            if ($timer === false) {
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
        
        $approval = $this->permissions->check(['article' => 'approve']) ? 1 : (isset($data['approval']) ? 1 : 0);
        $this->article->setApproval($approval);
        $this->article->setImagepath(isset($data['imagepath']) ? $data['imagepath'] : '');
        $this->article->setSources(isset($data['sources']) ? $data['sources'] : '');

        $this->article->setArchived(isset($data['archived']) ? 1 : 0);
        if ($this->article->getArchived()) {
            $this->article->setPinned(0);
            $this->article->setDraft(0);
        }
        
        $authorId = (isset($data['author']) && trim($data['author']) ? $data['author'] : $this->session->getUserId());
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

?>