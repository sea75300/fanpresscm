<?php
    /**
     * Base controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\abstracts;

    /**
     * Allgemeine Basis einen Controller
     * 
     * @package fpcm\controller\abstracts\controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @abstract
     */
    class controller implements \fpcm\controller\interfaces\controller {
        
        /**
         * Request-Daten
         * @var array
         */
        protected $request;
        
        /**
         * Array mit zu prüfenden Berchtigungen
         * @var array
         */
        protected $checkPermission = [];


        /**
         * Aktuelle Sessions
         * @var \fpcm\model\system\session
         */
        protected $session;
        
        /**
         * System-Configuration
         * @var \fpcm\model\system\config
         */
        protected $config;
        
        /**
         * Sprachen
         * @var \fpcm\classes\language
         */
        protected $lang;
        
        /**
         * Berechtigungen
         * @var \fpcm\model\system\permissions
         */
        protected $permissions;

        /**
         * Events
         * @var \fpcm\events\events
         */
        protected $events;

        /**
         * Cronjobs
         * @var \fpcm\model\crons\cronlist
         */
        protected $crons;

        /**
         * Cache
         * @var \fpcm\classes\cache
         */
        protected $cache;

        /**
         * Notifications
         * @var \fpcm\model\theme\notifications
         */
        protected $notifications;
        
        /**
         * Update-Prüfung aktiv
         * @var bool
         */
        protected $updateCheckEnabled = true;
        
        /**
         * Cache name
         * @var string
         */
        protected $cacheName        = false;
        
        /**
         * Cache Modul
         * @var string
         * @since FPCM 3.4
         */
        protected $cacheModule    = '';

        /**
         * Aktive Module für Prüfung von Controlelr-Ausführung
         * @var array
         */
        protected $enabledModules   = [];
        
        /**
         *
         * @var \fpcm\view\view
         */
        protected $view;

        /**
         * Konstruktor
         */        
        public function __construct()
        {
            
            if (\fpcm\classes\baseconfig::installerEnabled() && !\fpcm\classes\baseconfig::dbConfigExists()) {
                $this->redirect('installer');
            }
            
            if (\fpcm\classes\baseconfig::installerEnabled()) return false;

            $this->events        = \fpcm\classes\loader::getObject('\fpcm\events\events');
            $this->cache         = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
            $this->config        = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
            $this->session       = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
            $this->notifications = \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications');
            $this->crons         = new \fpcm\model\crons\cronlist();
            
            $moduleList           = new \fpcm\model\modules\modulelist();
            $this->enabledModules = $moduleList->getEnabledInstalledModules();

            if ($this->session->getCurrentUser()) {
                $this->permissions  = new \fpcm\model\system\permissions($this->session->currentUser->getRoll());
            }
            
            $this->config->setUserSettings();
            
            $this->lang         = \fpcm\classes\loader::getObject('fpcm\classes\language', $this->config->system_lang);
            
            $viewPath           = $this->getViewPath();
            if (!$viewPath) {
                return;
            }
            
            $this->view         = new \fpcm\view\view($viewPath);
        }
        
        /**
         * Gibt Wert in $_GET, $_POST, $_FILE zurück
         * @param string $varname
         * @param array $filter
         * @return mixed
         */
        public function getRequestVar($varname = null, array $filter = [\fpcm\classes\http::FPCM_REQFILTER_STRIPTAGS,\fpcm\classes\http::FPCM_REQFILTER_HTMLENTITIES, \fpcm\classes\http::FPCM_REQFILTER_STRIPSLASHES,\fpcm\classes\http::FPCM_REQFILTER_TRIM])
        {
            return \fpcm\classes\http::get($varname, $filter);
        }
        
        /**
         * Prüft ob Button gesendet wurde
         * @param string $buttonName
         * @return string
         */
        public function buttonClicked($buttonName)
        {
            $btnName = 'btn'.ucfirst($buttonName);
            return !is_null(\fpcm\classes\http::postOnly($btnName)) && is_null(\fpcm\classes\http::getOnly($btnName));
        }

        /**
         * Session zurückgeben
         * @return \model\session
         */        
        public function getSession()
        {
            return $this->session;
        }
        
        /**
         * Redirect if user is not logged in
         * @return boolean
         */
        protected function redirectNoSession()
        {
            return $this->redirect('system/login', ['nologin' => 1]);
        }

        /**
         * Controller Redirect
         * @param string $controller
         * @param array $params
         */
        protected function redirect($controller = '', array $params = [])
        {
            $redirectString = 'Location: '.($controller ? \fpcm\classes\tools::getFullControllerLink($controller, $params) : 'index.php');
            if (is_object($this->events)) {
                $this->events->runEvent('controllerRedirect', $redirectString);
                return false;
            }

            header($redirectString);
            return false;
        }

        /**
         * Create controller link
         * @param string $controller
         * @param array $params
         * @return string
         */
        protected function getControllerLink($controller = '', array $params = [])
        {
            return \fpcm\classes\tools::getControllerLink($controller, $params);
        }
        
        /**
         * Hinweis das Wartungsmodus aktiv ist
         * @param boolean $simplemsg
         * @return boolean
         */
        protected function maintenanceMode($simplemsg = true)
        {
            if (!$this->config->system_maintenance) {
                return true;
            }

            if ($simplemsg) {
                print $this->lang->translate('MAINTENANCE_MODE_ENABLED');
                return false;
            }

            $view = new \fpcm\view\error('MAINTENANCE_MODE_ENABLED');
            $view->render();

            return false;
        }
        
        /**
         * Page-Token prüfen
         * @return boolean
         */
        protected function checkPageToken()
        {
            if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], \fpcm\classes\dirs::getRootUrl()) === false) {
                return false;
            }

            $fieldname = \fpcm\classes\security::pageTokenCacheModule.DIRECTORY_SEPARATOR.\fpcm\classes\security::getPageTokenFieldName();
            $tokenData = $this->cache->read($fieldname);
            $this->cache->cleanup($fieldname);

            if (\fpcm\classes\http::getPageToken() == $tokenData) {
                return true;
            }

            return false;
        }

        /**
         * Get view path for controller
         * @return string
         */
        protected function getViewPath()
        {
            return '';
        }

        /**
         * Controller-Processing
         * @return boolean
         */
        public function process()
        {
           
            $currentClass = get_class($this);
            if (strpos($currentClass, 'fpcm\\modules\\') !== false) {
                $modulename = explode('\\', $currentClass);
                $modulename = $modulename[2].'/'.$modulename[3];

                if (!in_array($modulename, $this->enabledModules)) {
                    trigger_error("Request for controller '{$currentClass}' of disabled module '{$modulename}'!");                    
                    $view = new \fpcm\view\error();
                    $view->setMessage("The controller '{$this->getRequestVar('module')}' is not enabled for execution!");
                    $view->render();
                    die();
                }
            }
            
            return true;
        }
        
        /**
         * Request processing
         * @return boolean, false prevent execution of @see process()
         */ 
        public function request()
        {
            return true;
        }
        
        /**
         * Access check processing
         * @return boolean, false prevent execution of @see request() @see process()
         */ 
        public function hasAccess()
        {
            if (!$this->maintenanceMode(false) && !$this->session->exists()) {
                return false;
            }

            if (!$this->session->exists()) {
                return $this->redirectNoSession();
            }
            
            if ($this->permissions && count($this->checkPermission) && !$this->permissions->check($this->checkPermission)) {
                $view = new \fpcm\view\error('PERMISSIONS_REQUIRED');
                $view->render();
            }

            return true;
        }
        
        /**
         * Filter
         * @param string $filterString
         * @param array $filters
         * @return string
         */
        public static function filterRequest($filterString, array $filters) {            
            return \fpcm\classes\http::filter($filterString, $filters);         
        }
        
        /**
         * Magische Methode für nicht vorhandene Methoden
         * @param string $name
         * @param mixed $arguments
         * @return boolean
         */
        public function __call($name, $arguments) {
            print "Function not found! {$name}";
            return false;
        }

        /**
         * Magische Methode für nicht vorhandene, statische Methoden
         * @param string $name
         * @param mixed $arguments
         * @return boolean
         */        
        public static function __callStatic($name, $arguments) {
            print "Function not found! {$name}";
            return false;
        }

        /**
         * Destruktor
         * @return void
         */
        public function __destruct() {
            $this->cache       = null;
            $this->config      = null;
            $this->session     = null;
            $this->lang        = null;
            $this->events      = null;
            $this->request     = null;
            $this->crons       = null;            
            $this->permissions = null;
            
            return;
        }
    }
?>