<?php
    /**
     * Base controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
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
         * @var \fpcm\model\events\eventList
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
         * Konstruktor
         */        
        public function __construct() {
            
            if (\fpcm\classes\baseconfig::installerEnabled() && !\fpcm\classes\baseconfig::dbConfigExists()) {
                $this->redirect('installer');
            }
            
            if (\fpcm\classes\baseconfig::installerEnabled()) return false;

            $this->events        = \fpcm\classes\baseconfig::$fpcmEvents;
            $this->cache         = new \fpcm\classes\cache($this->cacheName ? $this->cacheName : md5(microtime(false)), $this->cacheModule);
            $this->config        = \fpcm\classes\baseconfig::$fpcmConfig;
            $this->session       = \fpcm\classes\baseconfig::$fpcmSession;
            $this->notifications = \fpcm\classes\baseconfig::$fpcmNotifications;
            $this->crons         = new \fpcm\model\crons\cronlist();
            
            $moduleList           = new \fpcm\model\modules\modulelist();
            $this->enabledModules = $moduleList->getEnabledInstalledModules();

            if ($this->session->getCurrentUser()) {
                $this->permissions  = new \fpcm\model\system\permissions($this->session->currentUser->getRoll());
            }
            
            $this->config->setUserSettings();
            
            $this->lang         = \fpcm\classes\baseconfig::$fpcmLanguage;
        }
        
        /**
         * Gibt Wert in $_GET, $_POST, $_FILE zurück
         * @param string $varname
         * @param array $filter
         * @return mixed
         */
        public function getRequestVar($varname = null, array $filter = [\fpcm\classes\http::FPCM_REQFILTER_STRIPTAGS,\fpcm\classes\http::FPCM_REQFILTER_HTMLENTITIES, \fpcm\classes\http::FPCM_REQFILTER_STRIPSLASHES,\fpcm\classes\http::FPCM_REQFILTER_TRIM]) {
            return \fpcm\classes\http::get($varname, $filter);
        }
        
        /**
         * Prüft ob Button gesendet wurde
         * @param string $buttonName
         * @return string
         */
        public function buttonClicked($buttonName) {
            $btnName = 'btn'.ucfirst($buttonName);
            return !is_null(\fpcm\classes\http::postOnly($btnName)) && is_null(\fpcm\classes\http::getOnly($btnName));
        }

        /**
         * Session zurückgeben
         * @return \model\session
         */        
        public function getSession() {
            return $this->session;
        }
        
        /**
         * Redirect wenn nicht eingeloggt
         * @param string $subDir
         */
        protected function redirectNoSession($subDir = false) {
            $subDir = ($subDir) ? '../' : '';
            header("Location: ".$subDir."index.php?module=system/login&nologin");
        }

        /**
         * Controller Redirect
         * @param string $controller
         * @param array $params
         */
        protected function redirect($controller = '', array $params = []) {
            $redirectString = empty($controller) ? "Location: index.php" : "Location: index.php?module=$controller";
            if (count($params)) {
                $redirectString .= '&'.http_build_query($params);
            }
            
            if (is_object($this->events)) {
                $this->events->runEvent('controllerRedirect', $redirectString);                
            }

            header($redirectString);
        }

        /**
         * Create controller link
         * @param string $controller
         * @param array $params
         * @return string
         */
        protected function getControllerLink($controller = '', array $params = []) {
            return \fpcm\classes\tools::getControllerLink($controller, $params);
        }

        /**
         * Prüft ob neue System-Updates vorhanden sind, erzeugt ggf. Meldung mit Möglichkeit, Update zu starten
         * @return void
         */
        protected function checkUpdates() {

            if (!$this->updateCheckEnabled) return;
            
            $asyncMail = $this->session->exists() ? false : true;            
            $res = $this->crons->registerCron('updateCheck', $asyncMail);

            if ($res === \fpcm\model\updater\system::SYSTEMUPDATER_FURLOPEN_ERROR || !\fpcm\classes\baseconfig::canConnect()) {
                $updater = new \fpcm\model\updater\system();
                $this->view->addJsVars(array(
                    'fpcmManualCheckUrl'      => $updater->getManualCheckAddress(),
                    'fpcmManualCheckHeadline' => $this->lang->translate('HL_PACKAGEMGR_SYSUPDATES')
                ));                
                $this->view->assign('includeManualCheck', true);
                
                if (!\fpcm\classes\baseconfig::canConnect() && $updater->checkManual()) {
                    $this->view->assign('autoDialog', is_null($res) ? false : true);
                } else {
                    $this->view->assign('autoDialog', false);
                }
                
                return;
                
            }
            
            if ($res === \fpcm\model\updater\system::SYSTEMUPDATER_FORCE_UPDATE) {
                $this->redirect('package/sysupdate');
                return;
            }
            
            if ($res === false) {

                $systemUpdates = new \fpcm\model\updater\system();
                $replace = array(
                    '{{versionlink}}' => $this->getControllerLink('package/sysupdate'),
                    '{{version}}'     => $systemUpdates->getRemoteData('version')
                );
                $this->view->addErrorMessage('UPDATE_VERSIONCHECK_NEW', $replace);
            }

        }
        
        /**
         * Hinweis das Wartungsmodus aktiv ist
         * @param boolean $simplemsg
         * @return boolean
         */
        protected function maintenanceMode($simplemsg = true) {
            
            if ($this->config->system_maintenance) {
                
                if ($simplemsg) {
                    print $this->lang->translate('MAINTENANCE_MODE_ENABLED');
                } else {
                    $view = new \fpcm\model\view\error();
                    $view->setMessage($this->lang->translate('MAINTENANCE_MODE_ENABLED'));
                    $view->render();
                }
                
                return false;
            }

            return true;
        }
        
        /**
         * Page-Token prüfen
         * @return boolean
         */
        protected function checkPageToken() {
            if (isset($_SERVER['HTTP_REFERER']) && !is_null($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], \fpcm\classes\baseconfig::$rootPath) === false) {
                return false;
            }
            
            $fieldname = \fpcm\classes\security::getPageTokenFieldName();
            $cache     = new \fpcm\classes\cache($fieldname, \fpcm\classes\security::pageTokenCacheModule);
            
            $tokenData = $cache->read();
            $cache->cleanup($fieldname, \fpcm\classes\security::pageTokenCacheModule);
            
            if (\fpcm\classes\http::getPageToken() == $tokenData) {
                return true;
            }

            return false;
        }

        /**
         * Controller-Processing
         * @return boolean
         */
        public function process() {
           
            $currentClass = get_class($this);
            if (strpos($currentClass, 'fpcm\\modules\\') !== false) {
                $modulename = explode('\\', $currentClass);
                $modulename = $modulename[2].'/'.$modulename[3];

                if (!in_array($modulename, $this->enabledModules)) {
                    trigger_error("Request for controller '{$currentClass}' of disabled module '{$modulename}'!");                    
                    $view = new \fpcm\model\view\error();
                    $view->setMessage("The controller '{$this->getRequestVar('module')}' is not enabled for execution!");
                    $view->render();
                    die();
                }
            }
            
            if (!$this->session->exists()) {
                $this->redirectNoSession();
                return false;
            }
            
            if ($this->permissions) {
                if (count($this->checkPermission) && !$this->permissions->check($this->checkPermission)) {
                    $view = new \fpcm\model\view\error();
                    $view->setMessage($this->lang->translate('PERMISSIONS_REQUIRED'));
                    $view->render();
                    die();
                }
                
                if ($this->session->getCurrentUser()->isAdmin() && $this->permissions->check(array('system' => 'update'))) {
                    $this->checkUpdates();
                }
            }
            
            return true;
        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {
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