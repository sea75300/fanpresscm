<?php
    /**
     * AJAX article editor smileys controller
     * 
     * Editor Smiley controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
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
            $smileyList = new \fpcm\model\files\smileylist();
            
            $view = new \fpcm\model\view\ajax('smileys', 'articles/editors');
            
            $view->assign('smileys', array_values($smileyList->getDatabaseList()));
            $view->setExcludeMessages(true);
            
            $view->render();
        }
    }
?>