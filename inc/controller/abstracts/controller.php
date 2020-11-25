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
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @abstract
 */
class controller implements \fpcm\controller\interfaces\controller {
    
    const ERROR_PROCESS_BYPARAMS = 0x404;

    const BYPARAM_DEFAULT_PREFIX = 'process';

    const BYPARAM_DEFAULT_ACTION = 'fn';

    /**
     * Request object
     * @var \fpcm\model\http\request
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
     * @var \fpcm\model\permissions\permissions
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
     * View object
     * @var \fpcm\view\view
     */
    protected $view;

    /**
     * View events namespace
     * @var string
     */
    protected $viewEvents = 'theme';

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
     * Check if controller was defined in module
     * @var bool
     */
    protected $moduleElement = false;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->request = \fpcm\classes\loader::getObject('\fpcm\model\http\request');

        if (\fpcm\classes\baseconfig::installerEnabled() && !\fpcm\classes\baseconfig::dbConfigExists()) {
            $this->redirect('installer');
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

        $this->crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');
        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language', $this->config->system_lang);

        $this->initPermissionObject();
        $this->hasActiveModule();
        $this->initActionObjects();
        $this->initView();
    }

    /**
     * Gibt Wert in $_GET, $_POST, $_FILE zurück
     * @param string $varname
     * @param array $filter
     * @return mixed
     * @deprecated FPCM 4.4, use $this->request instead
     */
    final public function getRequestVar
    (        
        $varname = null,
        array $filter = [
            \fpcm\classes\http::FILTER_STRIPTAGS,
            \fpcm\classes\http::FILTER_HTMLENTITIES,
            \fpcm\classes\http::FILTER_STRIPSLASHES,
            \fpcm\classes\http::FILTER_TRIM
        ]
    )
    {
        trigger_error(__METHOD__.' is deprecated as of FPCM 4.4, use $this->request instead! This method will be removed in future  releases.', E_USER_DEPRECATED);
        
        if (is_object($this->request)) {
            return $this->request->fetchAll($varname, $filter);
        }

        /**
         * @todo usage removal of old HTTP wrapper
         */
        return \fpcm\classes\http::get($varname, $filter);
    }

    /**
     * Prüft ob Button gesendet wurde
     * @param string $buttonName
     * @return string
     */
    final public function buttonClicked($buttonName)
    {
        if ($this->request->fromPOST('btn' . ucfirst($buttonName), []) !== null) {
            return true;
        }
        
        return false;
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
        
        $this->view = new \fpcm\view\view($viewPath, $this->moduleElement ? $this->getObject()->getKey() : false );
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
     * @return bool
     */
    protected function redirect($controller = '', array $params = [])
    {
        $this->execDestruct = false;

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
            trigger_error('Page token check failed, no referer available or referrer mismatch in '. get_class($this), E_USER_ERROR);
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
        if ($this instanceof \fpcm\controller\interfaces\viewByNamespace) {
            return str_replace(['fpcm\\controller\\action\\', '\\'], ['', DIRECTORY_SEPARATOR], get_called_class());
        }

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
     * @return string
     */
    protected function getIpLockedModul()
    {
        return 'noaccess';
    }

    /**
     * Get controller permissions
     * @return array
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
     * Controller processing
     * @return bool
     * @see \fpcm\controller\interfaces\controller
     */
    public function process()
    {
        return true;
    }

    /**
     * Request processing,
     * false prevent execution
     * @return bool
     * @see \fpcm\controller\interfaces\controller
     */
    public function request()
    {
        return true;
    }

    /**
     * Access check processing,
     * false prevent execution of request() and process()
     * @return bool
     * @see \fpcm\controller\interfaces\controller
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
                        : ( $this->permissions && count($this->getPermissions()) && !$this->permissions->check($this->getPermissions()) ? false : true );

        if (!$accessResult) {
            $this->execDestruct = false;
            $this->view = new \fpcm\view\error('PERMISSIONS_REQUIRED');
            $this->view->render($this->moduleCheckExit);
            return false;
        }

        return true;
    }

    /**
     * Process click of form items as function
     * @return bool
     * @since 4.4 
     * @todo experimental
     */
    public function processButtons() : bool
    {
        $items = $this->request->getPOSTItems();
        if (!is_array($items) || !count($items)) {
            return false;
        }

        $items = array_filter($items, function ($item) {
            return substr($item, 0, 3) !== \fpcm\view\helper\button::NAME_PREFIX ? false : true;
        });
        
        if (!count($items)) {
            return true;
        }
        
        foreach ($items as $item) {

            $func = 'on'.substr($item, 3);
            if (!method_exists($this, $func)) {
                continue;
            }
            
            call_user_func([$this, $func]);
   
        }        
        
        return true;
    }

    /**
     * Magische Methode für nicht vorhandene Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     */
    public function __call($name, $arguments)
    {
        http_response_code(404);
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
        http_response_code(404);
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
        $this->moduleElement = !(\fpcm\module\module::getKeyFromClass(get_class($this)) == false);
        return true;
    }

    /**
     * Returns active tab ID, jQuery UI zero-based index
     * @return int
     * @since 4.1
     */
    final protected function getActiveTab() : int
    {
        $activeTab = $this->request->fromGET('rg', [ \fpcm\model\http\request::FILTER_CASTINT ]);

        if ($activeTab !== null) {
            return (int) $activeTab;
        }

        $activeTab = $this->request->fromGET('activeTab', [ \fpcm\model\http\request::FILTER_CASTINT ]);

        if ($activeTab !== null) {
            return $activeTab;
        }
        
        return 0;
    }
    
    /**
     * Executes function by param from GET-request in current controller
     * @param string $prefix
     * @param string $actionFrom
     * @return real|bool
     * @since 4.3
     */
    final protected function processByParam(string $prefix = self::BYPARAM_DEFAULT_PREFIX, string $actionFrom = self::BYPARAM_DEFAULT_ACTION)
    {
        $actionName = $this->request->fetchAll($actionFrom, [
            \fpcm\model\http\request::FILTER_REGEX_REPLACE,
            \fpcm\model\http\request::FILTER_FIRSTUPPER,
            \fpcm\model\http\request::PARAM_REGEX => '/([A-Za-z0-9\_]{3,})/',
            \fpcm\model\http\request::PARAM_REGEX_REPLACE => '$0'
        ]);

        if (property_exists($this, $actionFrom)) {
            $this->{$actionFrom} = $actionName;
        }
        
        $fn = trim($prefix.$actionName);
        if (!method_exists($this, $fn)) {
            trigger_error('Request for undefined function '.$fn.' in '. get_called_class(), E_USER_WARNING);
            return self::ERROR_PROCESS_BYPARAMS;
        }

        return call_user_func([$this, $fn]);
    }

    /**
     * Initialize permission object
     * @return bool
     * @since 4.4
     */    
    protected function initPermissionObject() : bool
    {
        if ($this instanceof \fpcm\controller\interfaces\isAccessible) {
            $this->permissions = \fpcm\classes\loader::getObject('\fpcm\model\permissions\permissions');
            return true;
        }

        trigger_error(get_called_class(). ' :: Permissions objects of instance \\fpcm\\model\\system\\permissions are deprecated. Use \\fpcm\\model\\permissions\\permissions instead', E_USER_DEPRECATED);

        $this->permissions = \fpcm\classes\loader::getObject(
            '\fpcm\model\system\permissions',
            $this->session->exists() ? $this->session->getCurrentUser()->getRoll() : 0
        );

        return true;
    }

}