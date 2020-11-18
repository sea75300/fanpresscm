<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\articles;

/**
 * Article permissions model trait
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\traits\articles
 * @since 3.4
 */
trait permissions {

    /**
     * Führt Prüfung durch, ob Artikel bearbeitet werden kann
     * @param \fpcm\model\articles\article $article
     * @return bool
     */
    public function checkEditPermissions(article &$article)
    {
        $session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        if (!$session->exists()) {
            return false;
        }

        if ($this->permissions === false) {
            return true;
        }

        if ($session->getCurrentUser()->isAdmin() || $this->permissions->article->editall) {
            $article->setEditPermission(true);
            return true;
        }

        if ($this->permissions->article->edit && $article->getCreateuser() == $session->getUserId()) {
            $article->setEditPermission(true);
            return true;
        }

        $article->setEditPermission(false);
        return true;
    }

}
