<?php
    /**
     * Article list active controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\articles;
    
    class articlelistarchive extends articlelistbase {

        /**
         *
         * @var bool
         */
        protected $showDraftStatus = false;

        protected function getPermissions()
        {
            return ['article' => 'edit', 'article' => 'editall', 'article' => 'archive'];
        }
        
        public function request() {
            
            $this->listAction   = 'articles/listarchive';

            unset($this->articleActions[$this->lang->translate('EDITOR_PINNED')], $this->articleActions[$this->lang->translate('EDITOR_ARCHIVE')]);

            $this->articleCount = $this->articleList->getArticlesArchived(false, [], true);
            
            parent::request();

            $this->articleItems = $this->articleList->getArticlesArchived(true, [$this->listShowLimit, $this->listShowStart]);

            return true;
        }
        
        public function process() {
            
            parent::process();

            $this->view->assign('showArchiveStatus', false);
            
            $minMax = $this->articleList->getMinMaxDate(1);
            $this->view->addJsVars(array(
                'articleSearchMode'   => 1,
                'articleSearchMinDate' => date('Y-m-d', $minMax['minDate'])
            ));

            $this->view->render();
        }

    }
?>
