<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\articles;

/**
 * Artikelliste trait
 * 
 * @package fpcm\controller\traits\articles\lists
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait listsView {

    /**
     *
     * @var \fpcm\model\categories\categoryList
     */
    protected $categoryList;

    /**
     *
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     *
     * @var \fpcm\model\articles\articlelist
     */
    protected $articleList;

    /**
     *
     * @var \fpcm\model\comments\commentList
     */
    protected $commentList;

    /**
     * Berechtigungen zum Bearbeiten initialisieren
     */
    public function initEditPermisions()
    {
        if (!$this->session->exists()) {
            return false;
        }

        $this->view->assign('permEditOwn', $this->permissions->article->edit);
        $this->view->assign('permEditAll', $this->permissions->article->editall);
        $this->view->assign('permMassEdit', $this->permissions->article->massedit);
        $this->view->assign('currentUserId', $this->session->getUserId());
        $this->view->assign('isAdmin', $this->session->getCurrentUser()->isAdmin());

        $this->view->assign('canArchive', $this->permissions->article->archive);
        $this->view->assign('canApprove', $this->permissions->article->approve);
        $this->view->assign('canChangeAuthor', $this->permissions->article->authors);
    }

}
