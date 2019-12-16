<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\abstracts;

/**
 * Allgemeine Basis einen Controller
 * 
 * @package fpcm\controller\abstracts
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @abstract
 */
class controller implements \fpcm\controller\interfaces\controller {

    /**
     * Request-Daten
     * @var array
     */
    protected $request;

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
     * Language object
     * @var \fpcm\classes\language
     */
    protected $language;

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
     * Crypt object
     * @var \fpcm\classes\crypt
     */
    protected $crypt;

    /**
     * Notifications
     * @var \fpcm\model\theme\notifications
     */
    protected $notifications;

    /**
     * IP-Sperren-List-Objekt
     * @var \fpcm\model\ips\iplist
     */
    protected $ipList;

    /**
     * Cache name
     * @var string
     */
    protected $cacheName = false;

    /**
     * Cache name
     * @var string
     */
    protected $moduleCheckExit = true;

    /**
     * Aktive Module für Prüfung von Controlelr-Ausführung
     * @var array
     */
    protected $enabledModules = [];

    /**
     * View object
     * @var \fpcm\view\view
     */
    protected $view;

    /**
     * Page token check result
     * @var bool
     */
    protected $checkPageToken = true;

    /**
     * Execute defined actions on __destruct
     * @var bool
     */
    protected $execDestruct = true;

    /**
     * View events namespace
     * @var bool
     */
    protected $viewEvents = 'theme';

    /**
     * Check if controller was defined in module
     * @var bool
     */
    protected $moduleController = null;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        if (\fpcm\classes\baseconfig::installerEnabled() && !\fpcm\classes\baseconfig::dbConfigExists()) {
            return $this->redirect('installer');
            exit;
        }

        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return false;
        }

        $this->events = \fpcm\classes\loader::getObject('\fpcm\events\events');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->session = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        $this->config->setUserSettings();

        $this->notifications = \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications');
        $this->ipList = \fpcm\classes\loader::getObject('\fpcm\model\ips\iplist');
        $this->crons = \fpcm\classes\loader::getObject('\fpcm\model\crons\cronlist');

        $this->enabledModules = \fpcm\classes\loader::getObject('\fpcm\module\modules')->getEnabledDatabase();
        $this->crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');

        $this->permissions = \fpcm\classes\loader::getObject(
            '\fpcm\model\system\permissions',
            $this->session->exists() ? $this->session->getCurrentUser()->getRoll() : 0
        );

        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language', $this->config->system_lang);
        
        $this->hasActiveModule();

        $this->initActionObjects();
        $this->initView();
    }

    /**
     * Gibt Wert in $_GET, $_POST, $_FILE zurück
     * @param string $varname
     * @param array $filter
     * @return mixed
     */
    final public function getRequestVar($varname = null, array $filter = [\fpcm\classes\http::FILTER_STRIPTAGS, \fpcm\classes\http::FILTER_HTMLENTITIES, \fpcm\classes\http::FILTER_STRIPSLASHES, \fpcm\classes\http::FILTER_TRIM])
    {
        return \fpcm\classes\http::get($varname, $filter);
    }

    /**
     * Prüft ob Button gesendet wurde
     * @param string $buttonName
     * @return string
     */
    final public function buttonClicked($buttonName)
    {
        $btnName = 'btn' . ucfirst($buttonName);
        return !is_null(\fpcm\classes\http::postOnly($btnName)) && is_null(\fpcm\classes\http::getOnly($btnName));
    }

    /**
     * Session zurückgeben
     * @return \model\session
     */
    final public function getSession()
    {
        return $this->session;
    }

    /**
     * Initialises view object
     * @return bool
     */
    protected function initView()
    {
        $viewPath = $this->getViewPath();
        if (!$viewPath) {
            return false;
        }

        $this->view = new \fpcm\view\view($viewPath, $this->moduleController ? $this->moduleController : false);
        $this->view->setHelpLink($this->getHelpLink());
        $this->view->setActiveNavigationElement($this->getActiveNavigationElement());
        $this->view->triggerFilesEvents($this->viewEvents);
        return true;
    }

    /**
     * Redirect if user is not logged in
     * @return bool
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
        $redirectString = 'Location: ' . ($controller ? \fpcm\classes\tools::getFullControllerLink($controller, $params) : 'index.php');
        if (is_object($this->events)) {
            $redirectString = $this->events->trigger('controllerRedirect', $redirectString);
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
     * @return bool
     */
    final protected function maintenanceMode($simplemsg = true)
    {
        if (!$this->config->system_maintenance || ($this->session->exists() && $this->session->getCurrentUser()->isAdmin())) {
            return true;
        }

        if ($simplemsg) {
            $this->view = null;
            print $this->language->translate('MAINTENANCE_MODE_ENABLED');
            return false;
        }

        $this->view = new \fpcm\view\error('MAINTENANCE_MODE_ENABLED', null, 'lightbulb');
        $this->view->render($this->moduleCheckExit);

        return false;
    }

    /**
     * Check page token
     * @param string $name
     * @return bool
     */
    protected function checkPageToken($name = '')
    {
        if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], \fpcm\classes\dirs::getRootUrl()) === false) {
            trigger_error('Page token check failed, no referer available or referrer mismatch in '. get_class($this));
            $this->checkPageToken = false;
            return $this->checkPageToken;
        }

        $this->checkPageToken = (new \fpcm\classes\pageTokens())->validate($name);
        return $this->checkPageToken;
    }

    /**
     * Additional frontend request check
     * @param array $vars
     * @return bool
     */
    final protected function requestExit(array $vars) : bool
    {
        if (!\fpcm\classes\security::requestExit($vars)) {
            exit;
        }

        return true;
    }

    /**
     * Get view path for controller
     * @return string
     */
    protected function getViewPath() : string
    {
        return '';
    }

    /**
     * Get help link
     * @return string
     */
    protected function getHelpLink()
    {
        return '';
    }

    /**
     * Get controller access lock module
     * @return array()
     */
    protected function getIpLockedModul()
    {
        return 'noaccess';
    }

    /**
     * Get controller permissions
     * @return array()
     */
    protected function getPermissions()
    {
        return [];
    }

    /**
     * Get active navigation item id
     * @return string
     */
    protected function getActiveNavigationElement()
    {
        return '';
    }

    /**
     * Init action objects
     * @return bool
     */
    protected function initActionObjects()
    {
        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        return true;
    }

    /**
     * Request processing
     * @return bool, false prevent execution of @see process()
     */
    public function request()
    {
        return true;
    }

    /**
     * Access check processing
     * @return bool, false prevent execution of @see request() @see process()
     */
    public function hasAccess()
    {
        if (!$this->maintenanceMode(false) && !$this->session->exists()) {
            $this->execDestruct = false;
            return false;
        }

        if (!is_object($this->session) || !$this->session->exists()) {
            $this->execDestruct = false;
            return $this->redirectNoSession();
        }

        if ($this->getIpLockedModul() && $this->ipList->ipIsLocked($this->getIpLockedModul())) {
            $this->execDestruct = false;
            return false;
        }

        $accessResult   = $this instanceof \fpcm\controller\interfaces\isAccessible
                        ? $this->isAccessible()
                        : $this->permissions && count($this->getPermissions()) && !$this->permissions->check($this->getPermissions());

        if ($accessResult) {
            $this->execDestruct = false;
            $this->view = new \fpcm\view\error('PERMISSIONS_REQUIRED');
            $this->view->render($this->moduleCheckExit);
        }

        return $this->moduleController === null ? true : in_array($this->moduleController, $this->enabledModules);
    }

    /**
     * Filter
     * @param string $filterString
     * @param array $filters
     * @return string
     * @deprecated since version 4.1
     */
    public static function filterRequest($filterString, array $filters)
    {
        return \fpcm\classes\http::filter($filterString, $filters);
    }

    /**
     * Magische Methode für nicht vorhandene Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {
        print "Function not found! {$name}";
        return false;
    }

    /**
     * Magische Methode für nicht vorhandene, statische Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     */
    public static function __callStatic($name, $arguments)
    {
        print "Function not found! {$name}";
        return false;
    }

    /**
     * Destruktor
     * @return void
     */
    public function __destruct()
    {
        if (\fpcm\classes\baseconfig::isCli() || !$this->execDestruct) {
            return;
        }

        if ($this->view instanceof \fpcm\view\view && !$this->view->wasRendered()) {
            $this->view->render();
        }
    }

    /**
     * Check if active module was called
     * @return bool
     */
    final protected function hasActiveModule()
    {
        $class = get_class($this);

        $module = \fpcm\module\module::getKeyFromClass($class);
        if ($module === false) {
            $this->moduleController = null;
            return true;
        }

        if (!in_array($module, $this->enabledModules)) {
            $this->execDestruct = false;
            trigger_error("Request for controller '{$this->getRequestVar('module')}' of disabled module '{$module}'!");
            $view = new \fpcm\view\error("The controller '{$this->getRequestVar('module')}' is not enabled for execution!");
            $view->render($this->moduleCheckExit);
            return false;
        }
        
        $parent = get_parent_class($class);        
        if ($module && in_array($parent, ['fpcm\controller\abstracts\controller', 'fpcm\controller\abstracts\ajaxController']) ) {
            trigger_error("The use of \"{$parent}\" for module-defined controllers is deprecated as of FPCM 4.1! ".
                          "\"{$class}\" should be an instance of ". str_replace("controller\\abstracts\\", "controller\\abstracts\\module\\", $parent)." instead.");
        }

        $this->moduleController = trim($module) && $module !== false ? $module : null;
        return true;
    }

    /**
     * Returns active tab ID, jQuery UI zero-based index
     * @return int
     * @since FPCM 4.1
     */
    final protected function getActiveTab() : int
    {
        $activeTab = $this->getRequestVar('rg');
        if ($activeTab !== null) {
            return (int) $activeTab;
        }

        $activeTab = $this->getRequestVar('activeTab');
        if ($activeTab !== null) {
            return (int) $activeTab;
        }
        
        return 0;
    }
    
    /**
     * Executes function by param from GET-request in current controller
     * @param string $prefix
     * @param string $actionFrom
     * @return real|bool
     * @since FPCM 4.3
     */
    final protected function processByParam(string $prefix = 'process', string $actionFrom = 'fn')
    {
        $actionName = $this->getRequestVar($actionFrom, [
            \fpcm\classes\http::FILTER_REGEX_REPLACE,
            \fpcm\classes\http::FILTER_FIRSTUPPER,
            'regex' => '/([A-Za-z0-9\_]{3,})/',
            'regexReplace' => '$0'
        ]);

        $fn = trim($prefix.$actionName);
        if (!method_exists($this, $fn)) {
            trigger_error('Request for undefined function '.$fn);
            return 0x404;
        }

        return call_user_func([$this, $fn]);
    }

}