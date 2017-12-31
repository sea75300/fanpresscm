<?php
    /**
     * Public article title controller
     * @article Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\pub;
    
    class showtitle extends \fpcm\controller\abstracts\pubController {

        /**
         * auszuführende Aktion
         * @var string
         */
        protected $action = '';

        /**
         * Daten-Parameter
         * @var string
         */
        protected $param  = '';
        
        /**
         * UTF8-Encoding aktiv
         * @var bool
         */
        protected $isUtf8 = true;

        /**
         * Konstruktor
         * @param string $action
         * @param string $param
         */
        public function __construct($action, $param) {
            parent::__construct();
            
            $this->action = $action;
            $this->param  = $param;
            
            $this->isUtf8 = defined('FPCM_PUB_OUTPUT_UTF8') ? FPCM_PUB_OUTPUT_UTF8 : true;
        }
        
        public function request() {
            if (!$this->maintenanceMode()) {
                return false;
            }
            
            return parent::request();
        }
        
        /**
         * Controller ausführen
         * @return boolean
         */
        public function process() {
            switch ($this->action) {
                case 'page' :
                    $page = $this->getRequestVar('page');                    
                    if (is_null($page)) return;
                    $content = ' '.$this->param.' '.$page;
                    print $this->isUtf8 ? $content : utf8_decode($content);
                break;
                case 'title' :                    
                    if ($this->getRequestVar('module') != 'fpcm/article' || is_null($this->getRequestVar('id'))) return;
                    $article = new \fpcm\model\articles\article($this->getRequestVar('id'));
                    $content = ' '.$this->param.' '.$article->getTitle();
                    print $this->isUtf8 ? $content : utf8_decode($content);
                break;
            }            
        }
    }
?>