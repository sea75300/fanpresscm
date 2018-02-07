<?php
    /**
     * FanPress CM 4
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\view;
    
    /**
     * Default view object
     * 
     * @package fpcm\view
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */ 
    class view {

        const INCLUDE_HEADER_FULL   = 0b00001;

        const INCLUDE_HEADER_SIMPLE = 0b00010;

        const INCLUDE_HEADER_NONE   = 0b00100;
        
        /**
         * Complete view path
         * @var string
         */
        protected $viewPath = '';
        
        /**
         * View file name
         * @var string
         */
        protected $viewName = '';
        
        /**
         * Include header and footer in view::render
         * @var int
         */
        protected $showHeader;

        /**
         * View vars
         * @var array
         */
        protected $viewVars       = [];
        
        /**
         * View JS files
         * @var array
         */
        protected $viewJsFiles    = [];
        
        /**
         * View CSS files
         * @var array
         */
        protected $viewCssFiles   = [];

        /**
         * View messages
         * @var array
         */
        protected $messages       = [];
        
        /**
         * View JS vars
         * @var array
         */
        protected $jsvars         = [];
        
        /**
         * View JS language vars
         * @var array
         */
        protected $jsLangVars       = [];
        
        /**
         * File library object
         * @var \fpcm\model\system\fileLib
         */
        protected $fileLib;

        /**
         * Notifications
         * @var \fpcm\model\theme\notifications
         */
        protected $notifications;

        /**
         * Default vars object
         * @var viewVars
         */
        protected $defaultViewVars;

        /**
         * Cache object
         * @var \fpcm\classes\cache
         */
        protected $cache;

        /**
         * Session object
         * @var \fpcm\model\system\session
         */
        protected $session;

        /**
         * Config
         * @var \fpcm\model\system\config
         */
        protected $config;

        /**
         * Config
         * @var \fpcm\classes\language
         */
        protected $language;

        /**
         * View was already rendered
         * @var bool
         */
        protected $rendered;

        /**
         * Konstruktor
         * @param string $viewName View-Name, ohne Endung .php
         * @param string $viewPath View-Pfad unterhalb von core/views/
         */
        public function __construct($viewName = '')
        {
            $this->setViewPath($viewName);
            
            $this->showHeader       = self::INCLUDE_HEADER_FULL;

            $this->session          = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
            $this->config           = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
            $this->notifications    = \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications');
            $this->language         = \fpcm\classes\loader::getObject('fpcm\classes\language');
            $this->cache            = \fpcm\classes\loader::getObject('fpcm\classes\cache');
            
            $this->fileLib          = new \fpcm\model\system\fileLib();
            $this->defaultViewVars  = new viewVars();

            $this->initFileLib();
        }
        
        protected function initFileLib()
        {
            if ($this->showHeader === self::INCLUDE_HEADER_NONE) {
                return true;                
            }

            $this->viewCssFiles = $this->fileLib->getCsslib();
            $this->viewJsFiles  = $this->fileLib->getJslib();

            if (!is_object($this->language)) {
                return true;                
            }
            
            $this->addJsLangVars([
                'GLOBAL_CONFIRM', 'GLOBAL_CLOSE', 'GLOBAL_YES', 'GLOBAL_NO',
                'GLOBAL_OPENNEWWIN', 'GLOBAL_EXTENDED', 'AJAX_REQUEST_ERROR', 'AJAX_RESPONSE_ERROR',
                'CONFIRM_MESSAGE',
            ]);

            $this->jsLangVars['calendar']['days']       =  $this->language->getDays();
            $this->jsLangVars['calendar']['daysShort']  =  $this->language->getDaysShort();
            $this->jsLangVars['calendar']['months']     =  array_values($this->language->getMonths());
        }

        /**
         * Prüft, ob übergebener JS-Path schon in Elementen enthalten ist
         * @param string $item
         * @since FPCM 3.6
         */
        private function checkJsPath($item)
        {
            if (strpos($item, \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::CORE_JS)) === 0) {
                return $item;
            }

            $cacheName  = 'system/'.__METHOD__;

            $checks     = [];
            
            if (!$this->cache->isExpired($cacheName)) {
                $checks = $this->cache->read($cacheName);
            }
            
            $hash = hash(\fpcm\classes\security::defaultHashAlgo, $item);
//            if (isset($checks[$hash])) {
//                return $checks[$hash];
//            }
            
            try {
                if (file_exists(\fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_JS, $item))) {
                    $checks[$hash] = \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_JS, $item);
                    $this->cache->write($cacheName, $checks);
                    return $checks[$hash];
                }
            } catch (\Exception $e) {
                trigger_error($e->getMessage());
                return '';
            }

            try {
                
                if (substr($item, 0, 4) !== 'http') {
                    fpcmDump(substr($item, 0, 4), $item);
                }

                $file_headers = get_headers($item);
                if (isset($file_headers[0]) && $file_headers[0] === 'HTTP/1.1 200 OK') {
                    $checks[$hash] = $item;
                    $this->cache->write($cacheName, $checks);
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
        protected function prepareNotifications()
        {
            if ($this->config->system_maintenance) {
                $this->notifications->addNotification(new \fpcm\model\theme\notificationItem(
                    'SYSTEM_OPTIONS_MAINTENANCE', 
                    'fa fa-lightbulb-o fa-lg fa-fw',
                    'fpcm-ui-important-text'
                ));
            }
            
            if (!\fpcm\classes\baseconfig::asyncCronjobsEnabled()) {
                $this->notifications->addNotification(new \fpcm\model\theme\notificationItem(
                    'SYSTEM_OPTIONS_CRONJOBS', 
                    'fa fa-terminal fa-lg fa-fw',
                    'fpcm-ui-important-text'
                ));
            }

            $this->defaultViewVars->notificationString = $this->notifications->getNotificationsString();
            return true;
        }                   

        /**
         * JavaScript-Variablen in View setzen
         * @param array $viewJsFiles
         */
        public function addJsFiles(array $viewJsFiles)
        {
            $this->viewJsFiles = array_merge($this->viewJsFiles, array_map([$this, 'checkJsPath'], $viewJsFiles));
        }

        /**
         * CSS-Dateien in View erweitern
         * @param array $viewCssFiles
         */
        public function addCssFiles(array $viewCssFiles)
        {
            $this->viewCssFiles = array_merge($this->viewCssFiles, $viewCssFiles);
        }

        /**
         * Add new JS vars
         * @param mixed $jsvars
         */
        public function addJsVars(array $jsvars)
        {
            $this->jsvars = array_merge($this->jsvars, $jsvars);
        }

        /**
         * Add new JS language vars
         * @param mixed $jsvars
         */
        public function addJsLangVars(array $jsvars)
        {
            $keys   = array_map('strtolower', array_values($jsvars));
            $values = array_map([$this->language, 'translate'], array_values($jsvars));

            $this->jsLangVars = array_merge( $this->jsLangVars, array_combine($keys, $values) );
        }

        /**
         * Force to load jQuery in Pub-Controllers before other JS-Files if not already done
         * @since FPCM 3.2.0
         */
        public function prependjQuery()
        {
            if ($this->config->system_loader_jquery) {
                return false;
            }

            array_unshift($this->viewJsFiles, \fpcm\classes\loader::libGetFileUrl('jquery', 'jquery-3.2.0.min.js'));
        }
        
        /**
         * Assign new variable to view
         * @param string $varName
         * @param mixes $varValue
         */       
        public function assign($varName, $varValue)
        {
            $this->viewVars[$varName] = $varValue;
        }
        
        /**
         * Add red error message
         * @param string $messageText
         * @param string $params
         * @return void
         */
        public function addErrorMessage($messageText, $params = [])
        {
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
         * Add blue notification message
         * @param string $messageText
         * @param string $params
         * @return void
         */
        public function addNoticeMessage($messageText, $params= [])
        {
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
         * Add yellow message
         * @param string $messageText
         * @param string $params
         * @return void
         */
        public function addMessage($messageText, $params = [])
        {
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
         * Set link for help button
         * @param string $entry
         * @since FPCM 3.5
         */
        public function setHelpLink($entry)
        {
            if (!trim($entry)) {
                return false;
            }
            
            $this->defaultViewVars->helpLink = \fpcm\classes\tools::getFullControllerLink('system/help', [
                'ref' => base64_encode(strtolower($entry))
            ]);
            
            return true;
        }

        /**
         * Include header and footer into view
         * @param int $showHeader, valid values are \fpcm\view\view::INCLUDE_HEADER_FULL, \fpcm\view\view::INCLUDE_HEADER_SIMPLE, \fpcm\view\view::INCLUDE_HEADER_NONE
         */
        public function showHeaderFooter($showHeader)
        {
            $this->showHeader = $showHeader;
        }
        
        /**
         * Renders a set up view
         * @return bool
         */        
        public function render()
        {   
            if (!file_exists($this->viewPath)) {
                trigger_error("View file {$this->viewName} not found!");
                return false;
            }

            $this->initAssigns();
            
//            foreach ($this->events->runEvent('view/renderBefore', $this->viewVars) as $key => $value) {
//                $$key = $value;
//            }

            foreach ($this->viewVars as $key => $value) {
                $$key = $value;
            }            

            switch ($this->showHeader) {
                case self::INCLUDE_HEADER_FULL :
                    include_once \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'common/header.php');
                    break;
                case self::INCLUDE_HEADER_SIMPLE :
                    include_once \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'common/headersimple.php');
                    break;
            }

            include_once $this->viewPath;

            switch ($this->showHeader) {
                case self::INCLUDE_HEADER_FULL :
                    include_once \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'common/footer.php');
                    break;
                case self::INCLUDE_HEADER_SIMPLE :
                    include_once \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, 'common/footersimple.php');
                    break;
            }

            //$this->events->runEvent('view/renderAfter');
            
            $this->rendered = true;
            return true;
        }

        /**
         * Initializes basic view vars
         * @return boolean
         */
        protected function initAssigns()
        {
            if ($this->session->exists()) {
                
                $this->addJsLangVars(['SESSION_TIMEOUT']);
                $this->addJsVars([
                    'sessionCheckEnabled' => true
                ]);

                $this->defaultViewVars->currentUser              = $this->session->getCurrentUser();
                $this->defaultViewVars->navigation               = (new \fpcm\model\theme\navigation())->render();
                $this->defaultViewVars->navigationActiveModule   = \fpcm\classes\tools::getNavigationActiveCheckStr();
            }

            $this->defaultViewVars->version       = $this->config->system_version;
            $this->defaultViewVars->dateTimeMask  = $this->config->system_dtmask;
            $this->defaultViewVars->dateTimeZone  = $this->config->system_timezone;
            $this->defaultViewVars->self          = $_SERVER['PHP_SELF'];
            $this->defaultViewVars->frontEndLink  = $this->config->system_url;
            $this->defaultViewVars->basePath      = \fpcm\classes\tools::getFullControllerLink();
            $this->defaultViewVars->themePath     = \fpcm\classes\dirs::getCoreUrl(\fpcm\classes\dirs::CORE_THEME);
            $this->defaultViewVars->currentModule = \fpcm\classes\http::get('module');

            $this->defaultViewVars->loggedIn      = $this->session->exists();
            $this->defaultViewVars->lang          = \fpcm\classes\loader::getObject('fpcm\classes\language');            
            
            $this->defaultViewVars->filesCss      = $this->viewCssFiles;
            $this->defaultViewVars->filesJs       = $this->viewJsFiles;
            $this->defaultViewVars->varsJs        = [
                'vars'                  => [
                    'ui'                => [
                        'messages'      => $this->messages,
                        'lang'          => $this->jsLangVars,
                    ],
                    'jsvars'            => $this->jsvars,
                    'actionPath'        => \fpcm\classes\tools::getFullControllerLink(''),
                    'ajaxActionPath'    => \fpcm\classes\tools::getFullControllerLink('ajax/'),
                ]
            ];

            $this->prepareNotifications();
            
            /* @var $theView viewVars */
            $this->assign('theView', $this->defaultViewVars);

            helper::init($this->config->system_lang);
            
            return true;
        }

        /**
         * Returns view path
         * @return string
         */
        public function getViewPath()
        {
            return $this->viewPath;
        }

        /**
         * Sets view path
         * @param string $viewName
         */
        public function setViewPath($viewName)
        {
            $viewName .= '.php';

            $this->viewPath         = \fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS, $viewName);
            $this->viewName         = $viewName;
        }
        
        /**
         * Return assigned view vars
         * @return array
         */
        public function getViewVars()
        {
            return $this->viewVars;
        }

        /**
         * Overrides assigned view vars
         * @param array $viewVars
         */
        public function setViewVars(array $viewVars)
        {
            $this->viewVars = $viewVars;
        }

        /**
         * Auto focus element
         * @param string $elementId
         */
        public function setFieldAutofocus($elementId)
        {
            $this->jsvars['fieldAutoFocus'] = (string) $elementId;
        }

        /**
         * Auto focus element
         * @param string $elementId
         */
        public function setActiveNavigationElement($elementId)
        {
            $this->jsvars['navigationActive'] = (string) $elementId;
        }

        /**
         * Check if view was already rendered
         * @return bool
         */
        public function wasRendered() {
            return $this->rendered;
        }

        /**
         * Checks User Agent for a certain browser
         * @param string $key
         * @return boolean
         * @static
         */
        public static function isBrowser($key)
        {            
            if (!isset($_SERVER['HTTP_USER_AGENT'])) return true;
            return preg_match("/($key)/is", $_SERVER['HTTP_USER_AGENT']) === 1 ? true : false;
        }

    }
?>