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

            $dataView = new \fpcm\components\dataView\dataView('articlelistall');
            
            $dataView->addColumns([
                (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all') ))->setSize(1),
                (new \fpcm\components\dataView\column('button', ''))->setSize(2),
                (new \fpcm\components\dataView\column('title', 'ARTICLE_LIST_TITLE'))->setSize(4),
                (new \fpcm\components\dataView\column('categories', 'HL_CATEGORIES_MNG'))->setSize(2)->setAlign('center'),
                (new \fpcm\components\dataView\column('metadata', ''))->setSize(1),
            ]);

            /* @var $article \fpcm\model\articles\article */
            foreach ($this->articleItems as $articleMonth => $articles) {

                $titleStr  = $this->lang->writeMonth(date('n', $articleMonth), true);
                $titleStr .= ' '.$this->lang->writeMonth(date('Y', $articleMonth), true);
                $titleStr .= ' ('.count($articles).')';

                $dataView->addRow([
                    new \fpcm\components\dataView\rowCol('select', (string) (new \fpcm\view\helper\checkbox('fpcm-ui-list-checkbox-sub', 'fpcm-ui-list-checkbox-sub'.$articleMonth))->setClass('fpcm-ui-list-checkbox-sub')->setValue($articleMonth) ),
                    new \fpcm\components\dataView\rowCol('button', ' ' ),
                    new \fpcm\components\dataView\rowCol('title', $titleStr ),
                    new \fpcm\components\dataView\rowCol('categories', '' ),
                    new \fpcm\components\dataView\rowCol('metadata', '' ),
                ]);
                
                foreach ($articles as $articleId => $article) {

                    $desc   = $this->lang->translate('EDITOR_STATUS_POSTPONETO').($article->getPostponed()
                            ? ' '.new \fpcm\view\helper\dateText($article->getCreatetime())
                            : '');

                    $metaData = [    
                        (new \fpcm\view\helper\icon('thumb-tack fa-rotate-90 fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getPinned())->setText('EDITOR_STATUS_PINNED')->setStack('square'),
                        (new \fpcm\view\helper\icon('file-text-o fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getDraft())->setText('EDITOR_STATUS_DRAFT')->setStack('square'),
                        (new \fpcm\view\helper\icon('clock-o fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getPostponed())->setText($desc)->setStack('square'),
                        (new \fpcm\view\helper\icon('thumbs-o-up fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getApproval())->setText('EDITOR_STATUS_APPROVAL')->setStack('square'),
                        (new \fpcm\view\helper\icon('comments-o fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getComments())->setText('EDITOR_STATUS_COMMENTS')->setStack('square'),
                        (new \fpcm\view\helper\icon('archive fa-inverse'))->setClass('fpcm-ui-editor-metainfo fpcm-ui-status-'.$article->getArchived())->setText('EDITOR_STATUS_ARCHIVE')->setStack('square')
                    ];

                    $buttons = [
                        (new \fpcm\view\helper\openButton('articlefe'))->setUrlbyObject($article)->setTarget('_blank'),
                        (new \fpcm\view\helper\editButton('articleedit'))->setUrlbyObject($article),
                        (new \fpcm\view\helper\clearArticleCacheButton('cac'))->setDatabyObject($article)
                    ];
                    
                    $nameList = $article->getEditPermission() ? 'ids' : 'ro';
                    
                    $dataView->addRow([
                        new \fpcm\components\dataView\rowCol('select', (string) (new \fpcm\view\helper\checkbox('actions['.$nameList.'][]', 'chbx'.$articleId))->setClass('fpcm-ui-list-checkbox fpcm-ui-list-checkbox-subitem'.$articleMonth)->setValue($articleId)->setReadonly(!$article->getEditPermission()) ),
                        new \fpcm\components\dataView\rowCol('button', implode('', $buttons) ),
                        new \fpcm\components\dataView\rowCol('title', strip_tags($article->getTitle()) ),
                        new \fpcm\components\dataView\rowCol('categories', implode(', ', $article->getCategories()) ),
                        new \fpcm\components\dataView\rowCol('metadata', implode('', $metaData) ),
                    ]);
                }
                
                
            }
            
            //$this->view->assign('list', $this->articleItems);
            
            $minMax = $this->articleList->getMinMaxDate();
            
            $this->view->addDataView($dataView);
            
            $this->view->addJsVars(array_merge([
                'articleSearchMode'   => -1,
                'articleSearchMinDate' => date('Y-m-d', $minMax['minDate'])]
            ));
            
            $this->view->render();
        }

    }
?>