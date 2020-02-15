<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\comments;

/**
 * Comments permissions model trait
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\traits\comments
 * @since FPCM 3.4.4
 */
trait permissions {

    /**
     * Führt Prüfung durch, ob Artikel bearbeitet werden kann
     * @param \fpcm\model\comments\comment $comment
     * @return bool
     */
    public function checkEditPermissions(comment &$comment)
    {
        if (!\fpcm\classes\loader::getObject('\fpcm\model\system\session')->exists()) {
            return false;
        }

        if ($this->permissions === false) {
            return true;
        }

        if (!is_array($this->ownArticleIds) || !count($this->ownArticleIds)) {
            $this->articleList = new \fpcm\model\articles\articlelist();
            $this->ownArticleIds = $this->articleList->getArticleIDsByUser(\fpcm\classes\loader::getObject('\fpcm\model\system\session')->getUserId());
        }

        $isAdmin = \fpcm\classes\loader::getObject('\fpcm\model\system\session')->getCurrentUser()->isAdmin();
        if ($isAdmin || $this->permissions->comment->editall) {
            $comment->setEditPermission(true);
            return true;
        }
        
        if ($this->permissions->comment->edit && in_array($comment->getArticleid(), $this->ownArticleIds)) {
            $comment->setEditPermission(true);
            return true;
        }

        $comment->setEditPermission(false);
        return true;
    }

}
