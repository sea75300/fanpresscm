<?php

/**
 * Article list active controller
 * @article Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\articles\articlelist;

class active extends base {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->article->edit;
    }
    
    protected function getListAction() : void
    {
        $this->listAction = 'articles/listactive';
    }

    protected function getSearchMode() : string
    {
        return \fpcm\controller\ajax\articles\lists::MODE_ACTIVE;
    }

    protected function showDraftStatus() : bool
    {
        return false;
    }

}
