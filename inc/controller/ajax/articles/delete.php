<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * Setzt Inhalt auf in Bearbeitung
 * 
 * @package fpcm\controller\ajax\articles\inedit
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.5
 */
class delete extends \fpcm\controller\abstracts\ajaxControllerJSON implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->article->delete;
    }

    /**
     * 
     * @return bool
     */
    public function request() : bool
    {
        if (!$this->checkPageToken('articles/delete')) {
            return false;
        }
        
        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $isMultiple = $this->getRequestVar('multiple', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);
        
        $id = $this->getRequestVar('id', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        if ($isMultiple) {
            $this->returnCode = (new \fpcm\model\articles\articlelist())->deleteArticles($id) ? 1 : 0;
            $this->getResponse();            
        }
        
        $article = new \fpcm\model\articles\article($id);
        if ($article->exists() && $article->delete()) {
            $this->returnCode = 1;
            $this->getResponse();
        }

        $this->returnCode = 0;
        $this->getResponse();
    }

}

?>