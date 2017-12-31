<?php
    /**
     * Error view
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\view;
    
    /**
     * Error View Objekt
     * 
     * @package fpcm\model\view
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class error extends \fpcm\model\abstracts\view {
        
        /**
         * Error Message String
         * @var string
         */
        protected $errorMessage;

        /**
         * Konstruktor
         * @param string $viewName View-Name, ohne Endung .php
         * @param string $viewPath View-Pfad unterhalb von core/views/
         */
        public function __construct() {
            parent::__construct('error', 'common/');
        }

        /**
         * Lädt Datei, fügt View-Element, Header & Footer zusammen und erstellt Variablen für View
         * @see view
         * @return void
         */
        public function render() {            

            if (!parent::render()) {
                return false;
            }

            $this->initAssigns();

            $viewVars = $this->getViewVars();
            foreach ($viewVars as $key => $value) {
                $$key = $value;                    
            }

            $message = $this->errorMessage;
            include_once $this->getViewFile();
        }
        
        /**
         * View-Variablen initialisieren
         */
        protected function initAssigns() {

            /**
             * CSS und JS Files
             */
            $this->assign('FPCM_CSS_FILES', $this->getViewCssFiles());
            $this->assign('FPCM_JS_FILES', $this->getViewJsFiles());
            $this->assign('FPCM_JS_VARS', $this->getJsVars());
            
            /**
             * Pfade
             */
            $this->assign('FPCM_BASELINK', \fpcm\classes\baseconfig::$rootPath);
            $this->assign('FPCM_THEMEPATH', \fpcm\classes\baseconfig::$themePath);
            $this->assign('FPCM_BASEMODULELINK', \fpcm\classes\baseconfig::$rootPath.'index.php?module=');
            $this->assign('FPCM_SHORTHELP_LINK', $this->helpLink);
            
            /**
             * Sprache
             */
            $this->assign('FPCM_LANG', $this->language);
            
            /**
             * Meldungen
             */
            $this->assign('FPCM_MESSAGES', $this->getMessages());
            
            helper::init($this->config->system_lang);
        }
        
        /**
         * auszugebende Nachricht festlegen
         * @param string $message
         */
        public function setMessage($message) {
            $this->errorMessage = $message;
    }
}
?>