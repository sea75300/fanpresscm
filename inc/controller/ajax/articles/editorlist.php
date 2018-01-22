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
         * @var string
         */
        private $module;
        
        protected $checkPermission = ['article' => 'edit'];

        public function request() {
            
            $this->oid      = $this->getRequestVar('id', [\fpcm\classes\http::FPCM_REQFILTER_CASTINT]);
            $this->module   = ucfirst($this->getRequestVar('view'));

            return true;
        }

        /**
         * Get view path for controller
         * @return string
         */
        protected function getViewPath() {

            if ($this->module === 'comments') {
                return 'comments/commentlist_inner';                
            }

            return 'articles/lists/revisions';

        }

        /**
         * Controller-Processing
         */
        public function process() {

            $fn         = 'getView'.$this->module;
            if (!method_exists($this, $fn) || !$this->oid) {                
                die('');
            }
            
            call_user_func([$this, $fn]);

            $this->view->render();
        }
        
        private function getViewComments() {

            $commentList = new \fpcm\model\comments\commentList();

            $search = new \fpcm\model\comments\search();
            $search->articleid  = $this->oid;
            $search->searchtype = 0;

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

            $this->view->assign('revisions', $article->getRevisions());
            $this->view->assign('revisionCount', $article->getRevisionsCount());
            $this->view->assign('revisionPermission', $this->permissions->check(array('article' => 'revisions')));
            $this->view->assign('article', $article);

        }
    }
?>