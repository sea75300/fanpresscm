<?php

    namespace fpcm\controller\ajax\articles;
    
    /**
     * Kommentare bzw. Revisionen asynchron laden
     * 
     * @package fpcm\controller\ajax\articles\removeeditortags
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.6
     */
    class editorlist extends \fpcm\controller\abstracts\ajaxController {
        
        use \fpcm\controller\traits\comments\lists,
            \fpcm\model\articles\permissions;

        /**
         *
         * @var int
         */
        private $oid;

        /**
         *
         * @var \fpcm\model\view\ajax
         */
        protected $view;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            $this->checkPermission = array('article' => 'edit');
        }

        
        /**
         * Request-Handler
         * @return bool
         */
        public function request() {
            return $this->session->exists();
        }

        /**
         * Controller-Processing
         */
        public function process() {

            if (!parent::process()) return false;
            
            $this->oid  = $this->getRequestVar('id', [\fpcm\classes\http::FPCM_REQFILTER_CASTINT]);
            $module     = ucfirst($this->getRequestVar('view'));
            
            $fn         = 'getView'.$module;
            if (!method_exists($this, $fn) || !$this->oid) {                
                die('');
            }
            
            call_user_func([$this, $fn]);
            
            $this->view->initAssigns();
            $this->view->render();
        }
        
        private function getViewComments() {

            $commentList = new \fpcm\model\comments\commentList();

            $search = new \fpcm\model\comments\search();
            $search->articleid  = $this->oid;
            $search->searchtype = 0;

            $this->view = new \fpcm\model\view\ajax('commentlist_inner', 'comments');
            $this->view->assign('comments', $commentList->getCommentsBySearchCondition($search));
            $this->view->assign('commentsMode', 2);
            $this->view->assign('showPager', false);
            
            $this->initCommentMassEditForm(true);
            $this->initCommentPermissions();

        }
        
        private function getViewRevisions() {

            $article = new \fpcm\model\articles\article($this->oid);
            if (!$article->exists()) {
                die();
            }
            
            $this->view = new \fpcm\model\view\ajax('revisions', 'articles/lists');
            $this->view->assign('revisions', $article->getRevisions());
            $this->view->assign('revisionCount', $article->getRevisionsCount());
            $this->view->assign('revisionPermission', $this->permissions->check(array('article' => 'revisions')));
            $this->view->assign('article', $article);

        }
    }
?>