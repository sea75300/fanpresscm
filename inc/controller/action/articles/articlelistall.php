<?php
    /**
     * Article list all controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\articles;
    
    class articlelistall extends articlelistbase {

        protected function getPermissions()
        {
            return ['article' => 'edit', 'article' => 'editall'];
        }

        public function request() {
            
            $this->listAction   = 'articles/listall';

            $conditions = new \fpcm\model\articles\search();
            $conditions->draft    = -1;
            $conditions->drafts   = -1;
            $conditions->approval = -1;
            
            $this->articleCount = $this->articleList->countArticlesByCondition($conditions);
            
            parent::request();            
            
            $conditions->limit  = [$this->listShowLimit, $this->listShowStart];
            $this->articleItems = $this->articleList->getArticlesByCondition($conditions, true);

            return true;
        }
        
        public function process() {
            
            parent::process();

            $this->view->assign('list', $this->articleItems);
            
            $minMax = $this->articleList->getMinMaxDate();
            $this->view->addJsVars(array(
                'articleSearchMode'   => -1,
                'articleSearchMinDate' => date('Y-m-d', $minMax['minDate'])
            ));
            
            $this->view->render();
        }

    }
?>