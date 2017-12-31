<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\articles;
    
    /**
     * Article permissions model trait
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\traits\articles
     * @since FPCM 3.4
     */    
    trait permissions {

        /**
         * Führt Prüfung durch, ob Artikel bearbeitet werden kann
         * @param \fpcm\model\articles\article $article
         * @return boolean
         */
        public function checkEditPermissions(article &$article) {

            if (!\fpcm\classes\baseconfig::$fpcmSession->exists()) {
                return false;
            }

            if ($this->permissions === false) {
                return true;
            }

            $isAdmin     = \fpcm\classes\baseconfig::$fpcmSession->getCurrentUser()->isAdmin();
            $permEditAll = $this->permissions->check(array('article' => 'editall'));            
            $permEditOwn = $this->permissions->check(array('article' => 'edit'));
            
            if ($isAdmin || $permEditAll) {
                $article->setEditPermission(true);
                return true;
            }
            
            if (!$isAdmin && !$permEditAll && $permEditOwn &&
                $article->getCreateuser() == \fpcm\classes\baseconfig::$fpcmSession->getUserId()) {
                $article->setEditPermission(true);
                return true;                
            }

            $article->setEditPermission(false);
            return true;

        }
        

    }