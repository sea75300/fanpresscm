<?php
    /**
     * Help view controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class help extends \fpcm\controller\abstracts\controller {
        
        /**
         * ID des automatisch offenen Kapitels
         * @var int
         */
        protected $chapterHeadline = '';

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();

            $this->checkPermission  = [];
            $this->view             = new \fpcm\view\view('help', 'system');
            $this->cacheName        = 'helpcache_'.$this->config->system_lang;
        }

        public function request() {
            $this->chapterHeadline = $this->getRequestVar('ref');
            return parent::request();
        }
        
        /**
         * Controller-Processing
         * @return boolean
         */
        public function process() {
            if (!parent::process()) return false;

            $contents = $this->cache->read($this->cacheName);
            if ($this->cache->isExpired($this->cacheName) || !is_array($contents)) {

                $xml = simplexml_load_string($this->lang->getHelp());
                foreach ($xml->chapter as $chapter) {
                    $headline = trim($chapter->headline);
                    $contents[$headline] = trim($chapter->text);
                }

                $this->cache->write($this->cacheName, $contents);
            }            
            
            $contents = $this->events->runEvent('extendHelp', $contents);
            $this->view->assign('chapters', $contents);

            $pos = $this->chapterHeadline ? (int) array_search(strtoupper(base64_decode($this->chapterHeadline)), array_keys($contents)) : 0;
            $this->view->addJsVars(['fpcmDefaultCapter' => $pos]);
            $this->view->addJsFiles(['help.js']);
            
            $this->view->render();
        }
        
    }
?>