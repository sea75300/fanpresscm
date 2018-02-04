<?php
    /**
     * AJAX article editor smileys controller
     * 
     * Editor Smiley controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\articles;
    
    /**
     * Editor smiley ajax controller
     * 
     * @package fpcm\controller\ajax\articles\smileys
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class smileys extends \fpcm\controller\abstracts\ajaxController {
        
        protected function getViewPath() 
        {
            return 'articles/editors/smileys';
        }
        
        /**
         * Controller-Processing
         */
        public function process()
        {
            $this->view->assign('smileys', array_values( (new \fpcm\model\files\smileylist())->getDatabaseList() ) );
        }
    }
?>