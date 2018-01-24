<?php
    /**
     * Help view controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class help extends \fpcm\controller\abstracts\controller {

        protected function getViewPath() {
            return 'system/help';
        }
        
        /**
         * Controller-Processing
         * @return boolean
         */
        public function process() 
        {
            $this->cacheName  = 'helpcache_'.$this->config->system_lang;
            $chapterHeadline  = $this->getRequestVar('ref');

            $contents = $this->cache->read($this->cacheName);
            if (!is_array($contents)) {
                $contents = [];
            }

            if ($this->cache->isExpired($this->cacheName)) {

                $xml = simplexml_load_string($this->lang->getHelp());
                foreach ($xml->chapter as $chapter) {
                    $headline = trim($chapter->headline);
                    $contents[$headline] = trim($chapter->text);
                }

                $this->cache->write($this->cacheName, $contents);
            }            
            
            $contents = $this->events->runEvent('extendHelp', $contents);
            $this->view->assign('chapters', $contents);

            $pos = $chapterHeadline ? (int) array_search(strtoupper(base64_decode($chapterHeadline)), array_keys($contents)) : 0;
            $this->view->addJsVars(['fpcmDefaultCapter' => $pos]);
            $this->view->addJsFiles(['help.js']);
            
            $this->view->render();
        }
        
    }
?>