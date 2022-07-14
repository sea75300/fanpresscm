<?php

/**
 * Article list active controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles;

class articlelistarchive extends articlelistbase {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        if (!$this->permissions->article->edit && !$this->permissions->article->editall) {
            return false;
        }

        return $this->permissions->article->archive;
    }
    
    protected function getListAction() : void
    {
        $this->listAction = 'articles/listarchive';
    }

    protected function getSearchMode() : string
    {
        return \fpcm\controller\ajax\articles\lists::MODE_ARCHIVE;
    }

    protected function showDraftStatus() : bool
    {
        return false;
    }

    public function request()
    {
        unset($this->articleActions[$this->language->translate('EDITOR_PINNED')], $this->articleActions[$this->language->translate('EDITOR_ARCHIVE')]);
        return parent::request();
    }
}
