<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\comments;
    
    /**
     * Comments permissions model trait
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\traits\comments
     * @since FPCM 3.4.4
     */    
    trait permissions {

        /**
         * Führt Prüfung durch, ob Artikel bearbeitet werden kann
         * @param \fpcm\model\comments\comment $comment
         * @return boolean
         */
        public function checkEditPermissions(comment &$comment) {

            if (!\fpcm\classes\baseconfig::$fpcmSession->exists()) {
                return false;
            }

            if ($this->permissions === false) {
                return true;
            }

            if (!is_array($this->ownArticleIds)) {                
                $this->articleList   = new \fpcm\model\articles\articlelist();
                $this->ownArticleIds = $this->articleList->getArticleIDsByUser(\fpcm\classes\baseconfig::$fpcmSession->getUserId());
            }

            $isAdmin     = \fpcm\classes\baseconfig::$fpcmSession->getCurrentUser()->isAdmin();
            $permEditAll = $this->permissions->check(array('comment' => 'editall'));            
            $permEditOwn = $this->permissions->check(array('comment' => 'edit'));
            
            if ($isAdmin || $permEditAll) {
                $comment->setEditPermission(true);
                return true;
            }
            
            if (!$isAdmin && !$permEditAll && $permEditOwn && in_array($comment->getArticleid(), $this->ownArticleIds)) {
                $comment->setEditPermission(true);
                return true;                
            }

            $comment->setEditPermission(false);
            return true;

        }
        

    }