<?php
    /**
     * Article list active controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\articles;
    
    class articlelistarchive extends articlelistbase {

        /**
         *
         * @var bool
         */
        protected $showDraftStatus = false;

        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('article' => 'edit', 'article' => 'editall', 'article' => 'archive');
            
            unset(
                $this->articleActions[$this->lang->translate('EDITOR_PINNED')],
                $this->articleActions[$this->lang->translate('EDITOR_ARCHIVE')]
            );            
        }
        
        public function request() {
            $this->articleCount = $this->articleList->getArticlesArchived(false, [], true);
            
            parent::request();

            $this->articleItems = $this->articleList->getArticlesArchived(true, [$this->listShowLimit, $this->listShowStart]);

            return true;
        }
        
        public function process() {
            if (!parent::process()) return false;

            $this->view->assign('headlineVar', 'HL_ARTICLE_EDIT_ARCHIVE');
            $this->view->assign('listAction', 'articles/listarchive');
            $this->view->assign('showArchiveStatus', false);
            $this->view->assign('listIcon', 'archive');
            
            $minMax = $this->articleList->getMinMaxDate(1);
            $this->view->addJsVars(array(
                'fpcmArticleSearchMode'   => 1,
                'fpcmArticlSearchMinDate' => date('Y-m-d', $minMax['minDate'])
            ));
            $this->view->assign('permAdd', false);

            $this->view->render();
        }

    }
?>
