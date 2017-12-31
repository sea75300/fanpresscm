<?php
    /**
     * Article list active controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\articles;
    
    class articlelistactive extends articlelistbase {

        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('article' => 'edit');
        }

        public function request() {

            $conditions = new \fpcm\model\articles\search();
            $conditions->draft    = -1;
            $conditions->drafts   = -1;
            $conditions->active   = -1;
            $conditions->archived = -1;
            $conditions->approval = -1;

            $this->articleCount = $this->articleList->countArticlesByCondition($conditions);

            parent::request();

            $conditions->archived = 0;
            $conditions->limit    = [$this->listShowLimit, $this->listShowStart];            
            $this->articleItems   = $this->articleList->getArticlesByCondition($conditions, true);

            return true;
        }
        
        public function process() {
            if (!parent::process()) return false;

            $this->view->assign('headlineVar', 'HL_ARTICLE_EDIT_ACTIVE');
            $this->view->assign('listAction', 'articles/listactive');            
            $this->view->assign('list', $this->articleItems);
            $this->view->assign('showArchiveStatus', false);
            $this->view->assign('listIcon', 'newspaper-o');

            $minMax = $this->articleList->getMinMaxDate(0);
            $this->view->addJsVars([
                'fpcmArticleSearchMode'   => 0,
                'fpcmArticlSearchMinDate' => date('Y-m-d', $minMax['minDate'])
            ]);

            $this->view->render();
        }

    }
?>