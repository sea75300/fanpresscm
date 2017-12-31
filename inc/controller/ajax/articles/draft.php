<?php

    namespace fpcm\controller\ajax\articles;
    
    /**
     * Fügt den Inhalt einer ausgewählten HTML-Vorlage in Editor ein (HTML-Ansicht)
     * 
     * @package fpcm\controller\ajax\articles\removeeditortags
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.3
     */
    class draft extends \fpcm\controller\abstracts\ajaxController {

        /**
         * Request-Handler
         * @return bool
         */
        public function request() {
            return $this->session->exists();
        }

        /**
         * Controller-Processing
         */
        public function process() {

            if (!parent::process()) return false;
            
            $draftPath = $this->getRequestVar('path');
            
            $file = \fpcm\classes\baseconfig::$articleTemplatesDir.$draftPath;
            if (!trim($draftPath) || !file_exists($file)) {
                $this->returnCode = -1;
                $this->returnData = '';
                $this->getResponse();
            }
            
            $this->returnData = file_get_contents($file);
            $this->returnCode = 1;

            $this->getResponse();
        }
    }
?>