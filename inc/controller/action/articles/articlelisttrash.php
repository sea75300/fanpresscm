<?php
    /**
     * Article trash controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5
     */
    namespace fpcm\controller\action\articles;
    
    class articlelisttrash extends articlelistbase {

        protected function getViewPath()
        {
            return 'articles/listtrash';
        }

        protected function getPermissions()
        {
            return ['article' => 'edit', 'article' => 'editall'];
        }

        public function request() {

            $res = parent::request();

            $this->articleActions   = [$this->lang->translate('ARTICLE_LIST_RESTOREARTICLE') => 'restore', $this->lang->translate('ARTICLE_LIST_EMPTYTRASH') => 'trash'];
            $this->articleItems     = $this->articleList->getArticlesDeleted(true);

            return $res;
        }
        
        public function process() {
            
            parent::process();

            $this->view->assign('listAction', 'articles/trash');
            $this->view->assign('listIcon', 'trash');

            $this->view->render();
        }

    }
?>