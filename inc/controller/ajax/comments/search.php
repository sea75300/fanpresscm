<?php
    /**
     * AJAX comment search controller
     * 
     * AJAX controller for article search
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\comments;
    
    /**
     * Kommentar Suche
     * 
     * @package fpcm\controller\ajax\comments\search
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.3
     */
    class search extends \fpcm\controller\abstracts\ajaxController {
        
        use \fpcm\controller\traits\comments\lists;
        
        /**
         *
         * @var \fpcm\model\view\ajax
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
         * Konstruktor
         */
        public function __construct() {

            parent::__construct();
            
            $this->checkPermission = array('article' => array('editall', 'edit'), 'comment' => array('editall', 'edit'));
            
            $this->view         = new \fpcm\model\view\ajax('commentlist_inner', 'comments');
            $this->view->initAssigns();

            $this->list         = new \fpcm\model\comments\commentList();
            $this->articleList  = new \fpcm\model\articles\articlelist();

        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            if (!$this->session->exists()) {
                return false;
            }
           
            $filter     = $this->getRequestVar('filter');

            $sparams             = new \fpcm\model\comments\search();
            $sparams->searchtype = (int) $filter['searchtype'];
            
            if (trim($filter['text']))      $sparams->text       = $filter['text'];
            if ($filter['datefrom'])        $sparams->datefrom   = strtotime($filter['datefrom']);
            if ($filter['dateto'])          $sparams->dateto     = strtotime($filter['dateto']);
            if ($filter['spam'] > -1)       $sparams->spam       = (int) $filter['spam'];
            if ($filter['private'] > -1)    $sparams->private    = (int) $filter['private'];
            if ($filter['approved'] > -1)   $sparams->approved   = (int) $filter['approved'];
            if ($filter['articleId'] > 0)   $sparams->articleid  = (int) $filter['articleId'];

            $sparams->combination = $filter['combination'] ? 'OR' : 'AND';

            $sparams = $this->events->runEvent('commentsPrepareSearch', $sparams);

            $list = ($sparams->hasParams()
                  ? $this->list->getCommentsBySearchCondition($sparams)
                  : $this->list->getCommentsAll());

            $this->view->assign('comments', $list);

            return true;
        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;

            $this->initCommentPermissions();
            $this->initCommentMassEditForm(true);

            $this->view->setExcludeMessages(true);
            $this->view->assign('ownArticleIds', $this->articleList->getArticleIDsByUser($this->session->getUserId()));
            $this->view->assign('commentsMode', 1);
            $this->view->assign('showPager', false);
            $this->view->render();
            
        }

    }
?>