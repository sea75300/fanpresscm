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
     * IP-Sperren-List-Objekt
     * @var \fpcm\model\ips\iplist
     */
    protected $ipList;

    /**
     * Update-Prüfung aktiv
     * @var bool
     */
    protected $updateCheckEnabled = true;

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
     *
     * @var \fpcm\view\view
     */
    protected $view;

    /**
     *
     * @var bool
     */
    protected $checkPageToken = true;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        if (\fpcm\classes\baseconfig::installerEnabled() && !\fpcm\classes\baseconfig::dbConfigExists()) {
            return $this->redirect('installer');
        }

        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return false;
        }

        $this->events           = \fpcm\classes\loader::getObject('\fpcm\events\events');
        $this->cache            = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $this->config           = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->session          = \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        $this->config->setUserSettings();

        $this->notifications    = \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications');
        $this->ipList           = \fpcm\classes\loader::getObject('\fpcm\model\ips\iplist');
        $this->crons            = \fpcm\classes\loader::getObject('\fpcm\model\crons\cronlist');
        $this->enabledModules   = \fpcm\classes\loader::getObject('\fpcm\model\modules\modulelist')->getEnabledInstalledModules();

        $rollId                 = $this->session->exists() ? $this->session->currentUser->getRoll() : 0;

        $this->permissions      = \fpcm\classes\loader::getObject('\fpcm\model\system\permissions', $rollId);
        $this->lang             = \fpcm\classes\loader::getObject('\fpcm\classes\language', $this->config->system_lang);

        $this->initActionObjects();
        $this->initView();

    }

    /**
     * Gibt Wert in $_GET, $_POST, $_FILE zurück
     * @param string $varname
     * @param array $filter
     * @return mixed
     */
    public function getRequestVar($varname = null, array $filter = [\fpcm\classes\http::FPCM_REQFILTER_STRIPTAGS, \fpcm\classes\http::FPCM_REQFILTER_HTMLENTITIES, \fpcm\classes\http::FPCM_REQFILTER_STRIPSLASHES, \fpcm\classes\http::FPCM_REQFILTER_TRIM])
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
        $btnName = 'btn' . ucfirst($buttonName);
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
     * Initialises view object
     * @return boolean
     */
    protected function initView()
    {
        $viewPath = $this->getViewPath();
        if (!$viewPath) {
            return false;
        }

        $this->view = new \fpcm\view\view($viewPath);
        $this->view->setHelpLink($this->getHelpLink());
        $this->view->setActiveNavigationElement($this->getActiveNavigationElement());

        return true;
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
        $redirectString = 'Location: ' . ($controller ? \fpcm\classes\tools::getFullControllerLink($controller, $params) : 'index.php');
        if (is_object($this->events)) {
            $redirectString = $this->events->runEvent('controllerRedirect', $redirectString);
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
        if (!$this->config->system_maintenance || ($this->session->exists() && $this->session->getCurrentUser()->isAdmin()) ) {
            return true;
        }

        if ($simplemsg) {
            print $this->lang->translate('MAINTENANCE_MODE_ENABLED');
            return false;
        }

        $this->view = new \fpcm\view\error('MAINTENANCE_MODE_ENABLED', null, 'lightbulb-o');
        $this->view->render($this->moduleCheckExit);

        return false;
    }

    /**
     * Page-Token prüfen
     * @return boolean
     */
    protected function checkPageToken()
    {
        if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], \fpcm\classes\dirs::getRootUrl()) === false) {
            $this->checkPageToken = false;
            return $this->checkPageToken;
        }

        $fieldname = \fpcm\classes\security::pageTokenCacheModule . '/' . \fpcm\classes\security::getPageTokenFieldName();
        $tokenData = \fpcm\classes\loader::getObject('\fpcm\classes\crypt')->decrypt($this->cache->read($fieldname));
        $this->cache->cleanup($fieldname);

        $this->checkPageToken = (\fpcm\classes\http::getPageToken() == $tokenData ? true : false);
        return $this->checkPageToken;
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
     * @return boolean
     */
    public function process()
    {
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

        if (!is_object($this->session) || !$this->session->exists()) {
            return $this->redirectNoSession();
        }

        if ($this->getIpLockedModul() && $this->ipList->ipIsLocked($this->getIpLockedModul())) {
            return false;
        }

        $permissions = $this->getPermissions();
        if ($this->permissions && count($permissions) && !$this->permissions->check($permissions)) {
            $view = new \fpcm\view\error('PERMISSIONS_REQUIRED');
            $view->render($this->moduleCheckExit);
        }

        return $this->hasActiveModule();
    }

    /**
     * Filter
     * @param string $filterString
     * @param array $filters
     * @return string
     */
    public static function filterRequest($filterString, array $filters)
    {
        return \fpcm\classes\http::filter($filterString, $filters);
    }

    /**
     * Magische Methode für nicht vorhandene Methoden
     * @param string $name
     * @param mixed $arguments
     * @return boolean
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
     * @return boolean
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
        if (\fpcm\classes\baseconfig::isCli()) {
            return;
        }
        
        if ($this->view instanceof \fpcm\view\view && !$this->view->wasRendered()) {
            $this->view->render();
        }
    }

    /**
     * Check if active module was called
     * @return boolean
     */
    final protected function hasActiveModule()
    {
        $currentClass = get_class($this);
        if (strpos($currentClass, 'fpcm\\modules\\') !== false) {
            $modulename = explode('\\', $currentClass, 3)[2];
            if (!in_array($modulename, $this->enabledModules)) {
                trigger_error("Request for controller '{$currentClass}' of disabled module '{$modulename}'!");
                $view = new \fpcm\view\error("The controller '{$this->getRequestVar('module')}' is not enabled for execution!");
                $view->render($this->moduleCheckExit);
                return false;
            }
        }

        return true;
    }

}

?>