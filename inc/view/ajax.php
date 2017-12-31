<?php
    /**
     * AJAX view
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\view;
    
    /**
     * AJAX View Objekt
     * 
     * @package fpcm\model\view
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class ajax extends \fpcm\model\abstracts\view {

        /**
         * Messages-PHP ausschließen
         * @var bool
         */
        private $excludeMessages = false;
        
        /**
         * Konstruktor
         * @param string $viewName View-Name, ohne Endung .php
         * @param string $viewPath View-Pfad unterhalb von core/views/
         */
        public function __construct($viewName = 'ajax', $viewPath = 'common') {
            parent::__construct($viewName, trim($viewPath, '/').'/');
        }        

        /**
         * Lädt Datei, fügt View-Element, Header & Footer zusammen und erstellt Variablen für View
         * @see view
         * @return void
         */
        public function render() {

            if (!parent::render()) {
                die();
            }

            ob_start();

            $this->assign('FPCM_MESSAGES', $this->getMessages());

            $viewVars = $this->getViewVars();                
            $viewVars = $this->events->runEvent('viewRenderBefore', $viewVars);

            foreach ($viewVars as $key => $value) {
                $$key = $value;
            }

            if (!$this->excludeMessages) {
                include_once \fpcm\classes\baseconfig::$viewsDir.'common/messages.php';
            }

            if ($this->getViewFile()) include_once $this->getViewFile();

            $this->events->runEvent('viewRenderAfter');

            $data = ob_get_contents();
            ob_end_clean();
            die($data);                
        }

        /**
         * kein Messages-DIV in View laden
         * @return bool
         * @since FPCM 3.5
         */
        function getExcludeMessages() {
            return $this->excludeMessages;
        }

        /**
         * Status, ob Messages-DIV in View laden, setzen
         * @param bool $excludeMessages
         * @since FPCM 3.5
         */
        function setExcludeMessages($excludeMessages) {
            $this->excludeMessages = $excludeMessages;
        }

        /**
         * View-Variablen initialisieren
         */
        public function initAssigns() {
            
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
            
            if ($this->session->exists()) {
                $this->assign('FPCM_USER', $this->session->currentUser->getDisplayName());
                $this->assign('FPCM_SESSION_LOGIN', $this->session->getLogin());
            }
            
            helper::init($this->config->system_lang);
        }
    }
?>