<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * Setzt Inhalt auf in Bearbeitung
 * 
 * @package fpcm\controller\ajax\articles\inedit
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since 3.5
 */
class delete extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

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
        $isMultiple = $this->request->fromPOST('multiple', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        $id = $this->request->fromPOST('id', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if ($isMultiple) {
            $this->response->setReturnData(new \fpcm\model\http\responseData( (new \fpcm\model\articles\articlelist())->deleteArticles($id) ? 1 : 0 ))->fetch();
        }
        
        $article = new \fpcm\model\articles\article($id);

        $this->response->setReturnData(new \fpcm\model\http\responseData(
            $article->exists() && $article->delete() ? 1 : 0
        ))->fetch();
    }

}

?>