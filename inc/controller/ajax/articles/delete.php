<?php

namespace fpcm\controller\ajax\articles;

/**
 * Setzt Inhalt auf in Bearbeitung
 * 
 * @package fpcm\controller\ajax\articles\inedit
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.5
 */
class delete extends \fpcm\controller\abstracts\ajaxController {

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['article' => 'delete'];
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $article = new \fpcm\model\articles\article($this->getRequestVar('id', [
            \fpcm\classes\http::FILTER_CASTINT
        ]));

        if ($article->exists() && $article->delete()) {
            $this->returnCode = 1;
            $this->getResponse();
        }

        $this->returnCode = 0;
        $this->getResponse();
    }

}

?>