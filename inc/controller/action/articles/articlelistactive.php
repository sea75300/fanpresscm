<?php
    /**
     * Article list active controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\articles;
    
    class articlelistactive extends articlelistbase {

        protected function getPermissions()
        {
            return ['article' => 'edit'];
        }

        public function request() {
            
            $this->listAction   = 'articles/listactive';

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
            
            parent::process();
            
            $this->view->assign('list', $this->articleItems);
            $this->view->assign('showArchiveStatus', false);

            $minMax = $this->articleList->getMinMaxDate(0);
            $this->view->addJsVars([
                'articleSearchMode'   => 0,
                'articleSearchMinDate' => date('Y-m-d', $minMax['minDate'])
            ]);

            $this->view->render();
        }

    }
?>