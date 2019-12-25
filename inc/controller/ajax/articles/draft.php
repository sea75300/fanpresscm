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
        $draftPath = $this->getRequestVar('path');
        if (!trim($draftPath)) {
            $this->returnCode = -1;
            $this->returnData = '';
            $this->getResponse();
        }

        $file = new \fpcm\model\files\templatefile($draftPath);
        if (!$file->exists() || !$file->loadContent()) {
            $this->returnCode = -1;
            $this->returnData = '';
            $this->getResponse();
        }

        $this->returnData = $file->getContent();
        $this->returnCode = 1;
        $this->getResponse();
    }

}

?>