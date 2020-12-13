<?php

/**
 * Article list all controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

class articlelistall extends articlelistbase {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->article->edit || $this->permissions->article->editall;
    }

    protected function getListAction() : void
    {
        $this->listAction = 'articles/listall';
    }

    protected function getSearchMode() : string
    {
        return \fpcm\controller\ajax\articles\lists::MODE_ALL;
    }

    protected function showDraftStatus() : bool
    {
        return true;
    }

}
