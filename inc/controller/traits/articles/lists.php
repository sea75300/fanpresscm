<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\controller\traits\articles;
    
    /**
     * Artikelliste trait
     * 
     * @package fpcm\controller\traits\articles\lists
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */    
    trait lists {

        /**
         * Berechtigungen zum Bearbeiten initialisieren
         */
        public function initEditPermisions() {
            $this->view->assign('permAdd', $this->permissions->check(array('article' => 'add')));
            $this->view->assign('permEditOwn', $this->permissions->check(array('article' => 'edit')));
            $this->view->assign('permEditAll', $this->permissions->check(array('article' => 'editall')));
            $this->view->assign('currentUserId', $this->session->getUserId());
            $this->view->assign('isAdmin', $this->session->getCurrentUser()->isAdmin());  
            
            $this->view->assign('canArchive', $this->permissions->check(['article' => 'archive']));
            $this->view->assign('canApprove', $this->permissions->check(['article' => 'approve']));
            $this->view->assign('canChangeAuthor', $this->permissions->check(['article' => 'authors']));
            
            $this->deleteActions = $this->permissions->check(['article' => 'delete']);
        }
        
        /**
         * Kategorien übersetzen
         * @return void
         */
        protected function translateCategories() {
            
            if (!count($this->articleItems) || !$this->session->exists()) {
                return false;
            }

            $categories = $this->categoryList->getCategoriesNameListAll();
            foreach ($this->articleItems as $articles) {

                /* @var $article \fpcm\model\articles\article */
                foreach ($articles as &$article) {
                    $article->setCategories( array_keys( array_intersect( $categories, $article->getCategories() ) ) );
                }
            }
        }        
    
    }