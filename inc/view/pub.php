<?php
    /**
     * Public view
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\view;
    
    /**
     * Public View Objekt
     * 
     * @package fpcm\model\view
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class pub extends \fpcm\model\abstracts\view {

        /**
         * Laden von CSS-Dateien erzwingen
         * @var bool
         * @since FPCM 3.2.0
         */
        private $forceCss = false;

        /**
         * DIV für Messages ausblenden
         * @var bool
         * @since FPCM 3.4
         */
        private $hideMessages = false;

        /**
         * Konstruktor
         * @param string $viewName View-Name, ohne Endung .php
         * @param string $viewPath View-Pfad unterhalb von core/views/
         */
        public function __construct($viewName = '', $viewPath = '') {
            parent::__construct($viewName, trim($viewPath, '/').'/');
        }        
        
        /**
         * Header angezeigen Status
         * @return bool
         */
        public function getShowHeader() {
            return $this->showHeader;
        }

        /**
         * Footer angezeigen Status
         * @return bool
         */
        public function getShowFooter() {
            return $this->showFooter;
        }
        
        /**
         * Status vom Laden von CSS-Dateien erzwingen abrufen
         * @return bool
         * @since FPCM 3.2.0
         */
        public function getForceCss() {
            return $this->forceCss;
        }

        /**
         * Status ausgeben, ob Messages-DIV angezeigt werden soll
         * @return bool
         * @since FPCM 3.4
         */
        function getHideMessages() {
            return $this->hideMessages;
        }

        /**
         * Header angezeigen Status setzen
         * @param bool $showHeader
         */
        public function setShowHeader($showHeader) {
            $this->showHeader = $showHeader;
        }

        /**
         * Footer angezeigen Status setzen
         * @param bool $showFooter
         */
        public function setShowFooter($showFooter) {
            $this->showFooter = $showFooter;
        }
        
        /**
         * Erzeugt "Nicht gefunden" View
         * @param string $message
         * @param string $action
         */
        public function setNotFound($message, $action) {
            $this->setViewName('notfound.');
            $this->setViewPath('common/');
            
            $this->addErrorMessage($message);
            $this->assign('messageVar', $message);
            $this->assign('backaction', $action);
        }

        /**
         * Laden von CSS-Dateien erzwingen aktivieren
         * @param bool $forceCss
         * @since FPCM 3.2.0
         */
        public function setForceCss($forceCss) {
            $this->forceCss = (bool) $forceCss;
        }

        /**
         * Status setzen, ob Messages-DIV angezeigt werden soll
         * @param bool $hideMessages
         * @since FPCM 3.4
         */
        function setHideMessages($hideMessages) {
            $this->hideMessages = (bool) $hideMessages;
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
            $viewVars = $this->events->runEvent('viewRenderBefore', $viewVars);

            if (!isset($viewVars['hideDebug'])) {
                $viewVars['hideDebug'] = false;
            }

            foreach ($viewVars as $key => $value) { $$key = $value; }

            if ($this->getShowHeader()) include_once \fpcm\classes\baseconfig::$viewsDir.'common/headersimple.php';

            if (!$this->hideMessages) {
                include_once \fpcm\classes\baseconfig::$viewsDir.'common/messages.php';
            }

            if ($this->getViewFile()) include_once $this->getViewFile();

            if ($this->getShowFooter()) include_once \fpcm\classes\baseconfig::$viewsDir.'common/footersimple.php';

            $this->events->runEvent('viewRenderAfter');
        }
        
        /**
         * View-Variablen initialisieren
         */
        protected function initAssigns() {

            /**
             * CSS und JS Files
             */
            $this->assign('FPCM_CSS_FILES', $this->config->system_mode && !$this->forceCss ? array() : $this->getViewCssFiles());
            
            $jsFiles = $this->getViewJsFiles();

            if ($this->config->system_mode && !$this->config->system_loader_jquery) {
                unset($jsFiles[0]);
            }
            
            $this->assign('FPCM_JS_FILES', $jsFiles);
            $this->assign('FPCM_JS_VARS', $this->getJsVars());
            
            /**
             * Pfade
             */
            $this->assign('FPCM_BASELINK', \fpcm\classes\baseconfig::$rootPath);
            $this->assign('FPCM_THEMEPATH', \fpcm\classes\baseconfig::$themePath);
            $this->assign('FPCM_BASEMODULELINK', \fpcm\classes\baseconfig::$rootPath.'index.php?module=');
            $this->assign('FPCM_SELF', $_SERVER['PHP_SELF']);
            
            /**
             * Sprache
             */
            $this->assign('FPCM_LANG', $this->language);
            
            /**
             * Meldungen
             */
            $this->assign('FPCM_MESSAGES', $this->getMessages());
            
            /**
             * Login-Status
             */
            $this->assign('FPCM_LOGGEDIN', $this->session->exists());
            
            /**
             * System config data
             */
            $this->assign('FPCM_VERSION', $this->config->system_version);
            $this->assign('FPCM_FRONTEND_LINK', $this->config->system_url);
            $this->assign('FPCM_DATETIME_MASK', $this->config->system_dtmask);
            $this->assign('FPCM_DATETIME_ZONE', $this->config->system_timezone);
            
            /**
             * Current module
             */
            $this->assign('FPCM_CURRENT_MODULE', \fpcm\classes\http::get('module'));
            
            helper::init($this->config->system_lang);
        }
    }
?>