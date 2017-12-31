<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\common;
    
    /**
     * AJAX autocomplete controller
     * 
     * @package fpcm\controller\ajax\commom.addmsg
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.6
     */
    class autocomplete extends \fpcm\controller\abstracts\ajaxController {

        use \fpcm\controller\traits\articles\editor;

        /**
         * Modul-String
         * @var string
         */
        protected $module = null;

        /**
         * Suchbegriff
         * @var string
         */
        protected $term  = '';

        /**
         * Request-Handler
         * @return bool
         */
        public function request() {
            
            if (!$this->session->exists()) {
                return false;
            }
            
            $this->module = ucfirst($this->getRequestVar('src'));
            $this->term   = $this->getRequestVar('term', [\fpcm\classes\http::FPCM_REQFILTER_STRIPTAGS, \fpcm\classes\http::FPCM_REQFILTER_STRIPSLASHES, \fpcm\classes\http::FPCM_REQFILTER_TRIM, \fpcm\classes\http::FPCM_REQFILTER_URLDECODE]);

            return true;
        }

        /**
         * Controller-Processing
         */
        public function process() {

            $fn         = 'autocomplete'.$this->module;
            if (!method_exists($this, $fn)) {                
                $this->getSimpleResponse();
            }

            call_user_func([$this, $fn]);            
            $this->returnData = $this->events->runEvent('autocompleteGetData', [
                'module'     => $this->module,
                'returnData' => $this->returnData
            ]);

            $this->getSimpleResponse();
        }

        /**
         * Autocomplete von Artikeln
         * @return boolean
         */
        private function autocompleteArticles() {

            if (!$this->permissions->check(['article' => ['edit', 'editall']])) {
                $this->returnData = [];
                return false;
            }
            
            
            $list = new \fpcm\model\articles\articlelist();

            $conditions = new \fpcm\model\articles\search();
            $conditions->title    = $this->term;
            $conditions->approval = -1;
            $conditions->limit    = [200,0];
            $conditions->orderby  = ['createtime DESC'];
            
            $result = $list->getArticlesByCondition($conditions);
            if (!$result || !count($result)) {
                $this->returnData = [];
                return false;
            }

            /* @var \fpcm\model\articles\article $article */
            foreach ($result as $article) {
                $this->returnData[] = [
                    'value' => $article->getId(),
                    'label' => $article->getTitle().' ('.date($this->config->system_dtmask, $article->getCreatetime()).')'
                ];
            }

            return true;
        }

        /**
         * Autocomplete der Bild-Liste im Editor
         * @return boolean
         */
        private function autocompleteEditorfiles() {
            $this->returnData   = $this->getEditorPlugin()->getFileList();
            return true;
        }

        /**
         * Autocomplete der Link-Liste im Editor
         * @return boolean
         */
        private function autocompleteEditorlinks() {
            $this->returnData = $this->getEditorPlugin()->getEditorLinks();
            return true;
        }
    }
?>