<?php
    /**
     * Allgemeines View Objekt
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;
    
    /**
     * View basis model
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class view extends \fpcm\model\abstracts\staticModel {

        /**
         * Pfad zur View
         * @var string
         */
        protected $viewPath = '';
        
        /**
         * Name zur View
         * @var string
         */
        protected $viewName = '';
        
        /**
         * Pfad + Datename zur View
         * @var string
         */
        protected $viewFile = '';

        /**
         * View-Variablen
         * @var array
         */
        protected $viewVars       = [];
        
        /**
         * View-Javascript-Dateien
         * @var array
         */
        protected $viewJsFiles    = [];
        
        /**
         * View-CSS-Dateien
         * @var array
         */
        protected $viewCssFiles   = [];

        /**
         * View-Message-Informationen
         * @var array
         */
        protected $messages       = [];
        
        /**
         * View-Javascript-Variablen
         * @var array
         */
        protected $jsvars         = [];
        
        /**
         * View Filelib
         * @var \fpcm\model\system\fileLib
         */
        protected $fileLib        = null;
        
        /**
         * Erfolgt Aufruf durch mobile Endgerät aufgerufen
         * @var bool
         */
        protected $isMobile       = false;
        
        /**
         * Module-View-Type
         * @var string
         */
        protected $moduleViewType     = 'acp';

        /**
         * Hilfe-Link-String
         * @var string
         * @since FPCM 3.5
         */
        protected $helpLink     = '';

        /**
         * Notifications
         * @var \fpcm\model\theme\notifications
         * @since FPCM 3.6
         */
        protected $notifications;

        /**
         * Konstruktor
         * @param string $viewName View-Name, ohne Endung .php
         * @param string $viewPath View-Pfad unterhalb von core/views/
         */
        public function __construct($viewName = '', $viewPath = '') {
            parent::__construct();
            if (!$this->viewPath) {
                $this->viewPath = \fpcm\classes\baseconfig::$viewsDir.$viewPath;                
            }

            if (!empty($viewName)) $this->viewName = $viewName.'.php';
            
            $this->fileLib = new \fpcm\model\system\fileLib();
            
            $className = explode('\\', get_class($this));
            call_user_func(array($this, 'initFileLib'.ucfirst(array_pop($className))));
        }
        
        /**
         * ACP-Dateilibrary initialisieren
         */
        private function initFileLibAcp() {
            
            if (is_object($this->language)) {
                $this->jsvars = array(
                    'fpcmLang' => array(
                        'confirmHL'        => $this->language->translate('GLOBAL_CONFIRM'),
                        'confirmMessage'   => $this->language->translate('CONFIRM_MESSAGE'),
                        'close'            => $this->language->translate('GLOBAL_CLOSE'),
                        'yes'              => $this->language->translate('GLOBAL_YES'),
                        'no'               => $this->language->translate('GLOBAL_NO'),
                        'newWindow'        => $this->language->translate('GLOBAL_OPENNEWWIN'),
                        'extended'         => $this->language->translate('GLOBAL_EXTENDED'),
                        'ajaxErrorMessage' => $this->language->translate('AJAX_REQUEST_ERROR'),
                        'ajaxResponseErrorMessage' => $this->language->translate('AJAX_REPONSE_ERROR'),
                        'jquiDateDays'             => $this->language->getDays(),
                        'jquiDateDaysShort'        => $this->language->getDaysShort(),
                        'jquiDateMonths'           => array_values($this->language->getMonths())
                    )
                );
            }
            
            
            $this->viewCssFiles = $this->fileLib->getCsslib();
            $this->viewJsFiles  = $this->fileLib->getJslib();
        }
        
        /**
         * Dateilibrary für Modul-View initialisieren in Abhängzigkeiten von übergebenem Typ
         */
        private function initFileLibModule() {
            call_user_func(array($this, 'initFileLib'.ucfirst($this->moduleViewType)));
        }

        /**
         * Öffentliche Dateilibrary initialisieren
         */
        private function initFileLibPub() {
            $this->viewCssFiles = $this->fileLib->getCssPubliclib();
            $this->viewJsFiles  = $this->fileLib->getJsPubliclib();
        }
        
        /**
         * Dateilibrary in AJAX-View initialisieren
         */
        private function initFileLibAjax() {
            $this->viewCssFiles = [];
            $this->viewJsFiles  = [];
        }
        
        /**
         * Dateilibrary in Error-View initialisieren
         */
        private function initFileLibError() {
            $this->viewCssFiles = $this->fileLib->getCsslib();
            $this->viewJsFiles  = $this->fileLib->getJslib();
        }

        /**
         * Prüft, ob übergebener JS-Path schon in Elementen enthalten ist
         * @param string $item
         * @since FPCM 3.6
         */
        private function checkJsPath($item) {

            if (strpos($item, \fpcm\classes\baseconfig::$jsPath) === 0) {
                return $item;
            }

            $cache  = new \fpcm\classes\cache('jspaths', 'system');
            $checks = [];
            
            if (!$cache->isExpired()) {
                $checks = $cache->read();
            }
            
            $hash = hash(\fpcm\classes\security::defaultHashAlgo, $item);
            if (isset($checks[$hash])) {
                return $checks[$hash];
            }
            
            try {
                $file_headers = get_headers(\fpcm\classes\baseconfig::$jsPath.$item);
                if (isset($file_headers[0]) && $file_headers[0] === 'HTTP/1.1 200 OK') {
                    $checks[$hash] = \fpcm\classes\baseconfig::$jsPath.$item;
                    $cache->write($checks, FPCM_LANGCACHE_TIMEOUT);
                    return $checks[$hash];
                }
            } catch (\Exception $e) {
                trigger_error($e->getMessage());
                return '';
            }

            try {
                $file_headers = get_headers($item);
                if (isset($file_headers[0]) && $file_headers[0] === 'HTTP/1.1 200 OK') {
                    $checks[$hash] = $item;
                    $cache->write($checks, FPCM_LANGCACHE_TIMEOUT);
                    return $checks[$hash];
                }
            } catch (\Exception $e) {
                trigger_error($e->getMessage());
                return '';
            }

            return '';
        }

        /**
         * System-eigene Notifications setzen
         * @return boolean
         */
        protected function prepareNotifications() {
            
            $notifications = \fpcm\classes\baseconfig::$fpcmNotifications;

            if ($this->config->system_maintenance) {
                $notification = new \fpcm\model\theme\notificationItem(
                    'SYSTEM_OPTIONS_MAINTENANCE', 
                    'fa fa-lightbulb-o fa-lg fa-fw',
                    'fpcm-ui-important-text'
                );
                
                $notifications->addNotification($notification);
            }
            
            if (!\fpcm\classes\baseconfig::asyncCronjobsEnabled()) {
                $notification = new \fpcm\model\theme\notificationItem(
                    'SYSTEM_OPTIONS_CRONJOBS', 
                    'fa fa-terminal fa-lg fa-fw',
                    'fpcm-ui-important-text'
                );
                
                $notifications->addNotification($notification);
            }

            $this->assign('notificationsString', $notifications->getNotificationsString());
            return true;
        }

        /**
         * View-Pfad zurückgeben
         * @return string
         */
        public function getViewPath() {
            return $this->viewPath;
        }

        /**
         * View-Name zurückgeben
         * @return string
         */
        public function getViewName() {
            return $this->viewName;
        }

        /**
         * View-Name setzen
         * @param string $viewPath
         */
        public function setViewPath($viewPath) {
            $this->viewPath = \fpcm\classes\baseconfig::$viewsDir.'/'.$viewPath;
        }

        /**
         * View-Name setzen
         * @param string $viewName
         */
        public function setViewName($viewName) {
            $this->viewName = $viewName.'php';
        }

        /**
         * View-Datei zurücliefern
         * @return string
         */
        public function getViewFile() {
            return $this->viewFile;
        }

        /**
         * View-Variablen ausgeben
         * @return string
         */
        public function getViewVars() {
            return $this->viewVars;
        }

       /**
        * View-Datei setzen
        * @param string $viewFile
        */
        public function setViewFile($viewFile) {
            $this->viewFile = $viewFile;
        }

        /**
         * View-Variablen überschreiben
         * @param array $viewVars
         */
        public function setViewVars(array $viewVars) {
            $this->viewVars = $viewVars;
        }                     
        
        /**
         * JavaScript-Variablen in View auslesen
         * @return array
         */
        public function getViewJsFiles() {
            return $this->viewJsFiles;
        }

        /**
         * JavaScript-Variablen in View setzen
         * @param array $viewJsFiles
         */
        public function setViewJsFiles(array $viewJsFiles) {
            $this->viewJsFiles = array_merge($this->viewJsFiles, array_map([$this, 'checkJsPath'], $viewJsFiles));
        }
 
        /**
         * CSS-Dateien in View auslesen
         * @return array
         */        
        public function getViewCssFiles() {
            return $this->viewCssFiles;
        }

        /**
         * CSS-Dateien in View erweitern
         * @param array $viewCssFiles
         */
        public function setViewCssFiles(array $viewCssFiles) {
            $this->viewCssFiles = array_merge($this->viewCssFiles, $viewCssFiles);
        }
        
        /**
         * Gibt registrierte Nachrichten zurück
         * @return array
         */
        public function getMessages() {
            return $this->messages;
        }

        /**
         * JS-Variable zur Nutzung hinzufügen
         * @param mixed $jsvars
         */
        public function addJsVars(array $jsvars) {
            unset($jsvars['fpcmLang']);
            $this->jsvars = array_merge($this->jsvars, $jsvars);
        }

        /**
         * Sprachvariable zur Nutzung via Javascript hinzufügen
         * @param mixed $jsvars
         */
        public function addJsLangVars(array $jsvars) {
            $this->jsvars['fpcmLang'] = array_merge($this->jsvars['fpcmLang'], $jsvars);
        }

        /**
         * Force to load jQuery in Pub-Controllers before other JS-Files if not already done
         * @since FPCM 3.2.0
         */
        public function prependjQuery() {
            if ($this->config->system_loader_jquery) return false;
            array_unshift($this->viewJsFiles, \fpcm\classes\loader::libGetFileUrl('jquery', 'jquery-3.2.0.min.js'));
        }

        /**
         * JS-Variable zur Nutzung abrufen
         * @return array
         */
        protected function getJsVars() {
            return $this->jsvars;
        }
        
        /**
         * Weißt Variable in View Wert zu
         * @param string $varName
         * @param mixes $varValue
         */       
        public function assign($varName, $varValue) {
            $this->viewVars[$varName] = $varValue;
        }
        
        /**
         * rote Fehlermeldungen ausgeben
         * @param string $messageText
         * @param string $params
         * @return void
         */
        public function addErrorMessage($messageText, $params= array()) {

            $msg  = $this->language->translate($messageText, $params);
            if (!$msg) {
                $msg = $messageText;
            }

            $type = 'error';
            
            $this->messages[] = array(
                'txt'  => $msg,
                'type' => $type,
                'id'   => md5($type.$msg),
                'icon' => 'exclamation-triangle'
            );

        }
        
        /**
         * blaue Info-Meldungen ausgeben
         * @param string $messageText
         * @param string $params
         * @return void
         */
        public function addNoticeMessage($messageText, $params= array()) {

            $msg  = $this->language->translate($messageText, $params);
            if (!$msg) {
                $msg = $messageText;
            }

            $type = 'notice';
            
            $this->messages[] = array(
                'txt'  => $msg,
                'type' => $type,
                'id'   => md5($type.$msg),
                'icon' => 'check'
            );

        }
        
        /**
         * glebe Meldungen ausgeben
         * @param string $messageText
         * @param string $params
         * @return void
         */
        public function addMessage($messageText, $params= array()) {

            $msg  = $this->language->translate($messageText, $params);
            if (!$msg) {
                $msg = $messageText;
            }

            $type = 'neutral';
            
            $this->messages[] = array(
                'txt'  => $msg,
                'type' => $type,
                'id'   => md5($type.$msg),
                'icon' => 'info-circle'
            );

        }

        /**
         * Hilfe-Link setzen
         * @param string $helpLink
         * @since FPCM 3.5
         */
        public function setHelpLink($helpLink) {
            $this->helpLink = $helpLink;
        }
        
        /**
         * Prüft, ob View-Datei vorhanden ist und lädt diese
         * @return bool
         */        
        public function render() {
            $this->viewFile = $this->viewPath.$this->viewName;
            
            if (!file_exists($this->viewFile)) {
                trigger_error("View file {$this->viewFile} not found!");
                return false;
            }
            
            return true;
        }
        
        /**
         * Prüft ob aktuelle Browser einem bestimmten Browser entspricht (nicht unbedingt 100%-tig zuverlässig!)
         * @param string $key
         * @return boolean
         * @static
         */
        public static function isBrowser($key) {            
            if (!isset($_SERVER['HTTP_USER_AGENT'])) return true;
            return preg_match("/($key)/is", $_SERVER['HTTP_USER_AGENT']) === 1 ? true : false;
        }
        
    }
?>