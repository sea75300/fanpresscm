<?php
    /**
     * Article add controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\articles;
    
    class articleadd extends articlebase {

        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('article' => 'add');
            
            $this->view    = new \fpcm\model\view\acp('articleadd', 'articles');
            
            $this->article = new \fpcm\model\articles\article();
            $this->categoryList = new \fpcm\model\categories\categoryList();
        }

        public function request() {

            $checkPageToken = $this->checkPageToken();
            if ($this->buttonClicked('doAction') && !$checkPageToken) {
                $this->view->addErrorMessage('CSRF_INVALID');
            }

            if ($this->buttonClicked('articleSave') && $checkPageToken) {
                $data = $this->getRequestVar('article', array(4,7));

                $this->article->setTitle($data['title']);
                $this->article->setContent($data['content']);
                
                $cats = $this->categoryList->getCategoriesCurrentUser();
                
                $categories = isset($data['categories']) ? array_map('intval', $data['categories']) : array(array_shift($cats)->getId());
                $this->article->setCategories($categories);

                if (isset($data['postponed'])) {
                    $timer = strtotime($data['postponedate'].' '.(int) $data['postponehour'].':'.(int) $data['postponeminute'].':00');
                    
                    $postpone = 1;
                    if ($timer === false) {
                        $timer = time();
                        $postpone = 0;
                    }   
                    
                    $this->article->setPostponed($postpone);
                } else {
                    $timer = time();
                }

                $authorId = (isset($data['author']) && trim($data['author']) ? $data['author'] : $this->session->getUserId());
                
                $this->article->setCreatetime($timer);
                $this->article->setCreateuser($authorId);
                $this->article->setChangetime(time());
                $this->article->setChangeuser($this->session->getUserId());
                $this->article->setMd5path($this->article->getArticleNicePath());

                $this->article->setArchived(0);
                $this->article->setPinned(isset($data['pinned']) ? 1 : 0);
                $this->article->setComments(isset($data['comments']) ? 1 : 0);
                $this->article->setDraft(isset($data['draft']) ? 1 : 0);
                $this->article->setApproval($this->permissions->check(array('article' => 'approve')) ? 1 : 0);
                $this->article->setImagepath(isset($data['imagepath']) ? $data['imagepath'] : '');
                $this->article->setSources(isset($data['sources']) ? $data['sources'] : '');

                if (!$this->article->getTitle() || !$this->article->getContent()) {
                    $this->view->addErrorMessage('SAVE_FAILED_ARTICLE_EMPTY');
                    return true;
                }

                if (isset($data['tweettxt']) && $data['tweettxt']) {
                    $this->article->setTweetOverride($data['tweettxt']);
                }

                $this->article->enableTweetCreation(isset($data['tweet']) ? true : false);

                $this->article->prepareDataSave();
                $id = $this->article->save();
                if ($id === false) {
                    $this->view->addErrorMessage('SAVE_FAILED_ARTICLE');
                } else {
                    $addMsg = $this->permissions->check(array('article' => 'approve')) ? 2 : 1;
                    $this->redirect('articles/edit', array('articleid' => $id, 'added' => $addMsg));
                }
            }

            $this->article->enableTweetCreation($this->config->twitter_events['create']);

            return true;
            
        }
        
        public function process() {
            if (!parent::process()) return false;
            
            $this->view->assign('editorAction', 'articles/add');
            $this->view->assign('editorMode', 0);
            $this->view->assign('showComments', false);
            $this->view->assign('showRevisions', false);
            $this->view->assign('postponedTimer', time());

            $this->view->render();
        }

    }
?>