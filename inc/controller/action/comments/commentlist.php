<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\comments;
    
    /**
     * Comment list controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    class commentlist extends \fpcm\controller\abstracts\controller {
        
        use \fpcm\controller\traits\comments\lists;
        
        /**
         *
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         *
         * @var \fpcm\model\comments\commentList
         */
        protected $list;

        /**
         *
         * @var \fpcm\model\articles\articlelist
         */
        protected $articleList;
        
        /**
         *
         * @var int
         */
        protected $listShowLimit = 0;
        
        /**
         *
         * @var int
         */
        protected $listShowStart = 0;
        
        /**
         *
         * @var int
         */
        protected $commentCount = 0;

        /**
         * @see \fpcm\controller\abstracts\controller::__construct()
         */
        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('article' => array('editall', 'edit'), 'comment' => array('editall', 'edit'));
            
            $this->view          = new \fpcm\model\view\acp('commentlist', 'comments');            
            $this->list          = new \fpcm\model\comments\commentList();
            $this->articleList   = new \fpcm\model\articles\articlelist();

            $this->listShowLimit = $this->config->articles_acp_limit;
        }

        /**
         * @see \fpcm\controller\abstracts\controller::request()
         * @return boolean
         */
        public function request() {

            if (!$this->buttonClicked('deleteComment')) {
                return true;
            }

            if (!$this->checkPageToken()) {
                $this->view->addErrorMessage('CSRF_INVALID');
                return true;
            }
           
            return $this->processCommentActions($this->list);
        }
        
        /**
         * @see \fpcm\controller\abstracts\controller::process()
         * @return mixed
         */        
        public function process() {

            if (!parent::process()) return false;

            $this->initCommentPermissions();
            $this->initSearchForm();
            $this->initCommentMassEditForm();
            $this->initPagination();

            $this->view->setViewJsFiles(['comments.js']);
            
            $comments           = $this->list->getCommentsByLimit($this->listShowLimit, $this->listShowStart);
            $this->commentCount = count($comments);
            $this->view->assign('comments', $comments);
            
            $this->view->assign('commentsMode', 1);
            $this->view->assign('showPager', true);
            $this->view->setHelpLink('hl_comments_mng');
            $this->view->render();
        }
        
        /**
         * Initialisiert Suchformular-Daten
         * @param array $users
         */
        private function initSearchForm() {

            $this->view->assign('searchTypes', array(
                $this->lang->translate('COMMENTS_SEARCH_TYPE_ALL')  => 0,
                $this->lang->translate('COMMENTS_SEARCH_TYPE_TEXT') => 1
            ));

            $this->view->assign('searchApproval', array(
                $this->lang->translate('COMMMENT_APPROVE') => -1,
                $this->lang->translate('GLOBAL_YES')  => 1,
                $this->lang->translate('GLOBAL_NO') => 0
            ));

            $this->view->assign('searchSpam', array(
                $this->lang->translate('COMMMENT_SPAM') => -1,
                $this->lang->translate('GLOBAL_YES')  => 1,
                $this->lang->translate('GLOBAL_NO') => 0
            ));

            $this->view->assign('searchPrivate', array(
                $this->lang->translate('COMMMENT_PRIVATE') => -1,
                $this->lang->translate('GLOBAL_YES')  => 1,
                $this->lang->translate('GLOBAL_NO') => 0
            ));
            $this->view->assign('searchCombination', array(
                $this->lang->translate('ARTICLE_SEARCH_LOGICAND') => 0,
                $this->lang->translate('ARTICLE_SEARCH_LOGICOR')  => 1
            ));
            
            $this->view->addJsLangVars(array(
                'searchWaitMsg'      => $this->lang->translate('SEARCH_WAITMSG'),
                'searchHeadline'     => $this->lang->translate('ARTICLES_SEARCH'),
                'searchStart'        => $this->lang->translate('ARTICLE_SEARCH_START')
            ));

            $this->view->addJsVars(array('fpcmCommentsLastSearch' => 0));
        }

        protected function initPagination() {

            $this->view->assign('listAction', 'comments/list');  

            $page       = $this->getRequestVar('page', [\fpcm\classes\http::FPCM_REQFILTER_CASTINT]);
            $pagerData  = \fpcm\classes\tools::calcPagination(
                $this->listShowLimit,
                $page,
                $this->list->countCommentsByCondition(new \fpcm\model\comments\search()),
                $this->commentCount
            );

            $this->listShowStart = \fpcm\classes\tools::getPageOffset($page, $this->listShowLimit);

            $this->view->assign('showPager', true);
            foreach ($pagerData as $key => $value) {
                $this->view->assign($key, $value);
            }
            
            $this->view->addJsVars(['fpcmCurrentModule'=> $this->getRequestVar('module')]);
        }

    }
?>