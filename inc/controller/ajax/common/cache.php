<?php
    /**
     * AJAX cache controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\common;
    
    /**
     * AJAX controller zum Cache leeren 
     * 
     * @package fpcm\controller\ajax\common\cache
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */    
    class cache extends \fpcm\controller\abstracts\ajaxController {

        /**
         *
         * @var string
         */
        private $module;

        /**
         *
         * @var string
         */
        private $objid;

        /**
         * Request-Handler
         * @return bool
         */
        public function request() {

            if (!$this->session->exists()) {
                return false;
            }

            $this->module = $this->getRequestVar('cache', [\fpcm\classes\http::FPCM_REQFILTER_URLDECODE, \fpcm\classes\http::FPCM_REQFILTER_DECRYPT]);
            $this->objid  = $this->getRequestVar('objid', [\fpcm\classes\http::FPCM_REQFILTER_CASTINT]);

            return true;
        }
        
        /**
         * Controller-Processing
         * @return bool
         */
        public function process() {
            if (!parent::process()) return false;

            if ($this->module) {

                $fn = 'cleanup'.ucfirst($this->module);
                if (method_exists($this, $fn)) {
                    call_user_func([$this, $fn]);
                }

            }
            else {
                $this->cache->cleanup();
            }
            
            $this->events->runEvent('clearCache', [
                'module' => $this->module,
                'objid'  => $this->objid
            ]);

            $this->returnData[] = array(
                'txt'  => $this->lang->translate('CACHE_CLEARED_OK'),
                'type' => 'notice',
                'id'   => md5(uniqid()),
                'icon' => 'info-circle'
            );

            $this->getResponse();
        }

        /**
         * 
         * @return boolean
         */
        private function cleanupArticle() {

            $this->cache->cleanup(\fpcm\model\articles\article::CACHE_ARTICLE_SINGLE.$this->objid,
                                  \fpcm\model\articles\article::CACHE_ARTICLE_MODULE);

            return true;
            
        }

        /**
         * 
         * @return boolean
         */
        private function cleanupArticles() {

            $this->cache->cleanup(false, \fpcm\model\articles\article::CACHE_ARTICLE_MODULE);

            return true;
            
        }

    }
?>