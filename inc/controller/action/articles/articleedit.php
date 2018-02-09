<?php
    /**
     * Article edit controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\articles;
    
    class articleedit extends articlebase {
        
        use \fpcm\controller\traits\comments\lists,
            \fpcm\model\articles\permissions;
        
        /**
         *
         * @var \fpcm\model\users\userList
         */
        protected $userList;
        
        /**
         *
         * @var \fpcm\model\comments\commentList
         */
        protected $commentList;  
        
        /**
         *
         * @var bool
         */
        protected $showRevision = false;
        
        /**
         *
         * @var bool
         */
        protected $checkPageToken = true;
        
        /**
         *
         * @var \fpcm\model\articles\article
         */
        protected $revisionArticle = null;
        
        /**
         *
         * @var int
         */
        protected $revisionId = 0;
        
        protected function getViewPath()
        {
            return 'articles/articleedit';
        }

        protected function getPermissions()
        {
            return ['article' => 'edit'];
        }

        protected function getActiveNavigationElement()
        {
            return 'itemnav-id-editnews';
        }
        
        /**
         * @see \fpcm\controller\abstracts\controller::request()
         * @return boolean
         */        
        public function request() {

            $this->userList    = new \fpcm\model\users\userList();
            $this->commentList = new \fpcm\model\comments\commentList();
            
            if (is_null($this->getRequestVar('articleid'))) {
                $this->redirect('articles/list');
            }
            
            $this->article = new \fpcm\model\articles\article($this->getRequestVar('articleid'));

            if (!$this->article->exists()) {
                $this->view->setNotFound('LOAD_FAILED_ARTICLE', 'articles/list');                
                return true;
            }

            $this->checkEditPermissions($this->article);
            if (!$this->article->getEditPermission()) {
                $this->view = new \fpcm\view\error();
                $this->view->setMessage($this->lang->translate('PERMISSIONS_REQUIRED'));
                $this->view->render();
                return false;
            }

            $this->checkPageToken = $this->checkPageToken();
            if ($this->buttonClicked('doAction') && !$this->checkPageToken) {
                $this->view->addErrorMessage('CSRF_INVALID');
                
                $data = $this->getRequestVar('article', [
                    \fpcm\classes\http::FPCM_REQFILTER_STRIPSLASHES,
                    \fpcm\classes\http::FPCM_REQFILTER_TRIM
                ]);

                $this->assignArticleFormData($data, time());
            }

            $this->handleRevisionActions();
            $this->handleDeleteAction();
            $this->handleSaveAction();
            $this->handleCommentActions();

            if (!$this->revisionId) {
                $this->article->prepareDataLoad();
                $this->article->enableTweetCreation( $this->config->twitter_events['update']);
            }
            
            return true;
            
        }
        
        /**
         * @see \fpcm\controller\abstracts\controller::process()
         * @return mixed
         */        
        public function process()
        {
            parent::process();

            $this->view->setFormAction('articles/edit', ['articleid' => $this->article->getId()]);
            $this->view->assign('editorMode', 1);
            $this->view->assign('showRevisions', true);
            $this->view->assign('postponedTimer', $this->article->getCreatetime());
            $this->view->assign('users', $this->userList->getUsersByIds(array($this->article->getCreateuser(), $this->article->getChangeuser())));
            $this->view->assign('commentCount', array_sum($this->commentList->countComments( [$this->article->getId()] )));

            $this->view->assign('commentsMode', 2);
            $this->view->assign('revisionCount', $this->article->getRevisionsCount());
            
            $this->view->addJsVars([
                'canConnect'               => \fpcm\classes\baseconfig::canConnect() ? 1 : 0,
                'articleId'                => $this->article->getId(),
                'checkTimeout'             => FPCM_ARTICLE_LOCKED_INTERVAL * 1000,
                'checkLastState'           => -1
            ]);

            $this->view->addJsLangVars(['EDITOR_STATUS_INEDIT', 'EDITOR_STATUS_NOTINEDIT', 'COMMENTS_EDIT']);
            
            if (!$this->permissions->check(array('article' => 'approve')) && $this->article->getApproval()) {
                $this->view->addMessage('SAVE_SUCCESS_APPROVAL_SAVE');
            }

            if ($this->article->isInEdit()) {
                
                $data = $this->article->getInEdit();

                $username = $this->lang->translate('GLOBAL_NOTFOUND');
                if (is_array($data)) {
                    $user = new \fpcm\model\users\author($data[1]);
                    if ($user->exists())$username = $user->getDisplayname();
                }
                
                $this->view->addMessage('EDITOR_STATUS_INEDIT', ['{{username}}' => $username]);
            }
            
            $this->initPermissions();

            if ($this->showRevision) {
                $this->view->assign('revisionArticle', $this->revisionArticle);
                $this->view->assign('editorFile', \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_VIEWS, 'articles/editors/revisiondiff.php'));
                $this->view->assign('isRevision', true);
                $this->view->assign('showRevisions', false);
                $this->view->assign('showComments', false);
                $this->view->assign('editorAction', 'articles/edit&articleid='.$this->article->getId().'&rev='.$this->getRequestVar('rev'));

                if ($this->permissions->check(['article' => 'revisions'])) {
                    $this->view->addButton( (new \fpcm\view\helper\submitButton('articleRevisionRestore'))->setText('EDITOR_REVISION_RESTORE')->setIcon('undo') );
                }

                $this->view->addButton( (new \fpcm\view\helper\linkButton(''))->setUrl($this->article->getEditLink())->setText('EDITOR_BACKTOCURRENT')->setIcon('chevron-circle-left') );
            }
            else {                
                $this->view->addButtons([
                    (new \fpcm\view\helper\openButton('articlefe'))->setUrlbyObject($this->article)->setTarget('_blank')->setIconOnly(true),
                    (new \fpcm\view\helper\linkButton('shortlink'))->setUrl($this->article->getArticleShortLink())->setText('EDITOR_ARTICLE_SHORTLINK')->setIcon('external-link')->setIconOnly(true),
                ]);
                
                if ($this->article->getImagepath()) {
                    $this->view->addButton( (new \fpcm\view\helper\linkButton('articleimg'))->setUrl($this->article->getImagepath())->setText('EDITOR_ARTICLEIMAGE_SHOW')->setIcon('picture-o')->setIconOnly(true)->setClass('fpcm-editor-articleimage') );
                }
            }
            
            
            $this->view->render();
        }
        
        /**
         * Initialisiert Berechtigungen
         */
        protected function initPermissions() {
            
            $editComments = $this->permissions->check(array('article' => array('editall', 'edit'), 'comment' => array('editall', 'edit')));

            $this->view->assign('showComments', $editComments);
            
            if ($editComments) {
                $this->view->addJsFiles(['comments.js']);
                $this->initCommentMassEditForm();
            }
            
            $deletePermissions = $this->permissions->check(array('article' => 'delete'));
            
            if ($deletePermissions) {
                $this->view->addButton(new \fpcm\view\helper\deleteButton('articleDelete'));
            }
            
            $this->view->assign('permDeleteArticle', $deletePermissions);
            $this->view->assign('permApprove', $this->permissions->check(array('comment' => 'approve')));
            $this->view->assign('permPrivate', $this->permissions->check(array('comment' => 'private')));            
            $this->view->assign('permEditOwn', $this->permissions->check(array('comment' => 'edit')));
            $this->view->assign('permEditAll', $this->permissions->check(array('comment' => 'editall')));
            $this->view->assign('currentUserId', $this->session->getUserId());
            $this->view->assign('isAdmin', $this->session->getCurrentUser()->isAdmin());            
        }
        
        /**
         * Kommentar-Aktionen ausführen
         * @return boolean
         */
        protected function handleCommentActions() {

            if (!$this->checkPageToken || !$this->buttonClicked('deleteComment')) {
                return false;
            }

            $this->processCommentActions($this->commentList);

        }

        /**
         * 
         * @return boolean
         */
        private function handleDeleteAction() {
            
            if (!$this->buttonClicked('articleDelete') || $this->showRevision || !$this->checkPageToken) {
                return true;
            }
            
            if ($this->article->isInEdit()) {
                return false;
            }
            
            if ($this->article->delete()) {
                $this->redirect('articles/listall');
                return true;
            }

            $this->view->addErrorMessage('DELETE_FAILED_ARTICLE');
            return false;

        }

        /**
         * 
         * @return boolean
         */
        private function handleSaveAction() {

            $res = false;

            $allTimer = time();
            
            if ($this->buttonClicked('articleSave') && !$this->showRevision && $this->checkPageToken && !$this->article->isInEdit()) {

                $this->article->prepareRevision();
                
                $data = $this->getRequestVar('article', [
                    \fpcm\classes\http::FPCM_REQFILTER_STRIPSLASHES,
                    \fpcm\classes\http::FPCM_REQFILTER_TRIM
                ]);

                $this->assignArticleFormData($data, $allTimer);
                
                if (!$this->article->getTitle() || !$this->article->getContent()) {
                    $this->view->addErrorMessage('SAVE_FAILED_ARTICLE_EMPTY');
                    return true;
                }

                if (isset($data['tweettxt']) && $data['tweettxt']) {
                    $this->article->setTweetOverride($data['tweettxt']);
                }

                $this->article->setChangetime($allTimer);
                $this->article->setChangeuser($this->session->getUserId());
                $this->article->setMd5path($this->article->getArticleNicePath());
                $this->article->prepareDataSave();
                
                $saved = true;
                $this->article->enableTweetCreation(isset($data['tweet']) ? true : false);
                $res   = $this->article->update();
                
                if ($res) {
                    $this->article->createRevision();
                }
            }

            if ($res || $this->getRequestVar('added') == 1) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLE');
                return true;
            }
            
            if ($this->getRequestVar('added') == 2) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLE_APPROVAL');
                return true;
            }

            if (isset ($saved) && !$res) {
                $this->view->addErrorMessage('SAVE_FAILED_ARTICLE');
                return false;
            }

            return true;
        }

        /**
         * 
         * @param array $data
         * @param int $allTimer
         * @return boolean
         */
        private function assignArticleFormData(array $data, $allTimer) {

            $this->article->setTitle($data['title']);
            $this->article->setContent($data['content']);

            $cats = $this->categoryList->getCategoriesCurrentUser();

            $categories = isset($data['categories']) ? array_map('intval', $data['categories']) : array(array_shift($cats)->getId());
            $this->article->setCategories($categories);

            if (isset($data['postponed']) && !isset($data['archived'])) {
                $timer = strtotime($data['postponedate'].' '.(int) $data['postponehour'].':'.(int) $data['postponeminute'].':00');

                $postpone = 1;
                if ($timer === false) {
                    $timer = $allTimer;
                    $postpone = 0;
                }   

                $this->article->setPostponed($postpone);
                $this->article->setCreatetime($timer);
            }
            else {
                if ($this->article->getPostponed() || ($this->article->getDraft() && !isset($data['draft']))) {
                    $this->article->setCreatetime($allTimer);
                }

                $this->article->setPostponed(0);
            }

            $this->article->setPinned(isset($data['pinned']) ? 1 : 0);
            $this->article->setDraft(isset($data['draft']) ? 1 : 0);
            $this->article->setComments(isset($data['comments']) ? 1 : 0);
            $this->article->setApproval($this->permissions->check(array('article' => 'approve')) ? 1 : 0);
            $this->article->setImagepath(isset($data['imagepath']) ? $data['imagepath'] : '');
            $this->article->setSources(isset($data['sources']) ? $data['sources'] : '');

            if (isset($data['archived'])) {
                $this->article->setArchived(1);
                $this->article->setPinned(0);
                $this->article->setDraft(0);
            } else {
                $this->article->setArchived(0);
            }

            if (isset($data['author']) && trim($data['author'])) {
                $this->article->setCreateuser($data['author']);
            }

            return true;
        }

        /**
         * 
         * @return boolean
         */
        private function handleRevisionActions() {

            if ($this->getRequestVar('revrestore')) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLEREVRESTORE');
            }

            $revisionIdsArray   = !is_null($this->getRequestVar('revisionIds'))
                                ? array_map('intval', $this->getRequestVar('revisionIds'))
                                : false;

            if ($this->buttonClicked('revisionDelete') && $revisionIdsArray && !$this->showRevision && $this->checkPageToken) {
                if ($this->article->deleteRevisions($revisionIdsArray)) {
                    $this->view->addNoticeMessage('DELETE_SUCCESS_REVISIONS');
                }
                else {
                    $this->view->addErrorMessage('DELETE_FAILED_REVISIONS');
                }
            }
            
            $this->revisionId   = !is_null($this->getRequestVar('rev'))
                                ? (int) $this->getRequestVar('rev')
                                : (is_array($revisionIdsArray) ? array_shift($revisionIdsArray) : false);
            
            if ($this->buttonClicked('articleRevisionRestore') && $this->revisionId && $this->checkPageToken) {
                
                if ($this->article->restoreRevision($this->revisionId)) {
                    $this->redirect('articles/edit&articleid='.$this->article->getId().'&revrestore=1');
                } else {
                    $this->view->addErrorMessage('SAVE_FAILED_ARTICLEREVRESTORE');
                }

                return true;
            }            
            
            if (!$this->revisionId) {
                return false;
            }

            include_once \fpcm\classes\loader::libGetFilePath('PHP-FineDiff/finediff.php');

            $this->revisionArticle = clone $this->article;

            if (!$this->revisionId) {
                $this->revisionId = (int) $this->getRequestVar('rev');
            }                

            $this->showRevision   = ($this->revisionArticle->getRevision($this->revisionId) ? true : false);

            $from = $this->revisionArticle->getContent();
            $opcode = \FineDiff::getDiffOpcodes($from, $this->article->getContent(), \FineDiff::$characterGranularity);
            $this->view->assign('textDiff', \FineDiff::renderDiffToHTMLFromOpcodes($from, $opcode));

            return true;
        }
    }
?>
