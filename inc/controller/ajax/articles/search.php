<?php
    /**
     * AJAX article search controller
     * 
     * AJAX controller for article search
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\articles;
    
    /**
     * Artikelsuche
     * 
     * @package fpcm\controller\ajax\articles\search
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class search extends \fpcm\controller\abstracts\ajaxController {
        
        use \fpcm\controller\traits\articles\lists;
        
        /**
         * Controller-View
         * @var \fpcm\model\view\ajax
         */
        protected $view;
        
        /**
         * Kategorie-Liste
         * @var \fpcm\model\categories\categoryList
         */
        protected $categoryList;
        
        /**
         * Benutzer-Liste
         * @var \fpcm\model\users\userList
         */
        protected $userList;
        
        /**
         * Artikel-Liste
         * @var \fpcm\model\articles\articlelist
         */
        protected $articleList;
        
        /**
         * Kommentar-Liste
         * @var \fpcm\model\comments\commentList
         */
        protected $commentList;
        
        /**
         * Liste mit erlaubten Artikel-Aktionen
         * @var array
         */
        protected $articleActions = [];

        /**
         * Array mir Artikel-Objekten
         * @var array
         */
        protected $articleItems = [];
        
        /**
         * Suchparameter
         * @var array
         */
        protected $sparams = [];
        
        /**
         * Suchmodus
         * @var int
         */
        protected $mode = -1;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            
            $this->articleList  = new \fpcm\model\articles\articlelist();
            $this->categoryList = new \fpcm\model\categories\categoryList();
            $this->commentList  = new \fpcm\model\comments\commentList();
            $this->userList     = new \fpcm\model\users\userList();

            $this->view         = new \fpcm\model\view\ajax('articles', 'articles/lists');
            $this->view->initAssigns();
        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            if (!$this->session->exists()) {
                return false;
            }

            $this->mode = $this->getRequestVar('mode');
            $filter     = $this->getRequestVar('filter');

            $sparams = new \fpcm\model\articles\search();
            
            if ($filter['text'] != '') {
                switch ($filter['searchtype']) {
                    case 0 :
                        $sparams->title   = $filter['text'];
                        break;
                    case 1 :
                        $sparams->content = $filter['text'];
                        break;
                    default:
                        $sparams->title   = $filter['text'];
                        $sparams->content = $filter['text'];
                        break;
                }                
            }
            
            if ($filter['userid'] > 0)      $sparams->user       = (int) $filter['userid'];
            if ($filter['categoryid'] > 0)  $sparams->category   = (int) $filter['categoryid'];            
            if ($filter['datefrom'])        $sparams->datefrom   = strtotime($filter['datefrom']);
            if ($filter['dateto'])          $sparams->dateto     = strtotime($filter['dateto']);
            if ($filter['pinned'] > -1)     $sparams->pinned     = (int) $filter['pinned'];
            if ($filter['postponed'] > -1)  $sparams->postponed  = (int) $filter['postponed'];
            if ($filter['comments'] > -1)   $sparams->comments   = (int) $filter['comments'];
            if ($filter['draft'] > -1)      $sparams->draft      = (int) $filter['draft'];
            if ($this->mode != -1)          $sparams->archived   = (int) $this->mode;
            
            $sparams->approval    = (int) $filter['approval'];
            $sparams->combination = $filter['combination'] ? 'OR' : 'AND';
            
            $sparams = $this->events->runEvent('articlesPrepareSearch', $sparams);

            $this->articleItems = $this->articleList->getArticlesByCondition($sparams, true);
            $this->translateCategories();
            
            return true;
        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;
            
            $users = $this->userList->getUsersNameList();
            
            $this->view->setExcludeMessages(true);

            $this->view->assign('timesMode', true);
            $this->view->assign('users', array_flip($users));
            $this->view->assign('commentEnabledGlobal', $this->config->system_comments_enabled);
            $this->view->assign('showArchiveStatus', ($this->mode == -1) ? true : false);
            $this->view->assign('showDraftStatus', ($this->mode == -1) ? false : true);
            $this->view->assign('isSearch', true);
            $this->view->assign('showPager', false);

            $this->initEditPermisions();
            
            $commentCounts = $this->commentList->countComments($this->getArticleListIds());
            $this->view->assign('commentCount', $commentCounts);
            
            $this->view->assign('commentSum', $commentCounts && count($this->articleItems) ? array_sum($commentCounts) : 0);
            
            $this->view->assign('list', $this->articleItems);
            $this->view->render();
            
        }
        
        /**
         * Artike-IDs zurückgeben
         * @return array
         */
        protected function getArticleListIds() {
            $articleIds = [];
            foreach ($this->articleItems as $monthData) {
                $articleIds = array_merge($articleIds, array_keys($monthData));
            }
            
            return $articleIds;
        }

    }
?>