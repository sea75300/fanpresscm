<?php

namespace fpcm\controller\ajax\articles;

/**
 * Fügt den Inhalt einer ausgewählten HTML-Vorlage in Editor ein (HTML-Ansicht)
 * 
 * @package fpcm\controller\ajax\articles\removeeditortags
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.3
 */
class draft extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->editArticles() || $this->permissions->article->add;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->response = new \fpcm\model\http\response;
        
        $draftPath = $this->request->fetchAll('path');
        if (!trim($draftPath)) {
            $this->response->setReturnData(new \fpcm\model\http\responseData(-1, ''))->fetch();
        }

        $file = new \fpcm\model\files\templatefile($draftPath);
        if (!$file->exists() || !$file->loadContent()) {
            $this->response->setReturnData(new \fpcm\model\http\responseData(-1, ''))->fetch();
        }

        $this->response->setReturnData(new \fpcm\model\http\responseData(1, $file->getContent()))->fetch();        
    }

}

?>