<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

/**
 * FPCM frontend api flag
 */
define('FPCM_FE', true);

/**
 * Include of base files
 */
require_once __DIR__ . '/inc/controller/main.php';
require_once __DIR__ . '/inc/common.php';

/**
 * FanPress CM API, class for integration into a website
 * 
 * @package fpcmapi
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class fpcmAPI {

    /**
     * API-Controller
     * @var array
     */
    protected $controllers = [];

    /**
     * Ausführung unter PHP 7+
     * @var bool
     */
    protected $versionFailed = false;

    /**
     * Konstruktor, prüft PHP-Version, Installer-Status und Datenbank-Config-Status
     * @return void
     */
    public function __construct()
    {
        if (version_compare(PHP_VERSION, FPCM_PHP_REQUIRED, '<') || !\fpcm\classes\baseconfig::dbConfigExists() || \fpcm\classes\baseconfig::installerEnabled()) {
            $this->versionFailed = true;
            return;
        }

        \fpcm\classes\http::init();
    }
    
    /**
     * Initializes controllers
     * @return bool
     */
    private function registerController() : bool
    {
        $this->controllers = \fpcm\classes\baseconfig::getControllers();
        return true;
    }

    /**
     * Initialized objects required by system
     * @return bool
     * @since FPCM 4.2.1
     */
    private function initObjects() : bool
    {
        \fpcm\classes\loader::getObject('\fpcm\classes\database');
        \fpcm\classes\loader::getObject('\fpcm\classes\crypt');
        \fpcm\classes\loader::getObject('\fpcm\module\modules')->getEnabledDatabase();

        \fpcm\classes\loader::getObject('\fpcm\model\system\session');
        \fpcm\classes\loader::getObject('\fpcm\model\system\config')->setUserSettings();
        \fpcm\classes\loader::getObject('\fpcm\classes\language', \fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_lang);
        \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications');
        return true;
    }

    /**
     * Artikel anzeigen
     * @param array $params params @see \fpcm\controller\action\pub\showcommon
     * @return boolean
     */
    public function showArticles(array $params = [])
    {
        if ($this->versionFailed) {
            return false;
        }

        $this->registerController();
        $this->initObjects();

        $module = \fpcm\classes\http::get('module', [
            fpcm\classes\http::FILTER_STRIPTAGS,
            fpcm\classes\http::FILTER_STRIPSLASHES,
            fpcm\classes\http::FILTER_TRIM
        ]);
        
        if ($module === null) {
            $module = 'fpcm/list';
        }

        if (strpos($module, 'fpcm/') === false || !in_array($module, ['fpcm/list', 'fpcm/article', 'fpcm/archive']) || !$this->controllers[$module]) {
            return false;
        }

        $controllerName  = str_replace('/', '\\', "fpcm/controller/".$this->controllers[$module]);
        if (!class_exists($controllerName)) {
            trigger_error('Undefined controller called: ' . $module);
            return false;
        }

        $params['apiMode'] = true;

        /**
         * @var abstracts\controller
         */
        $controller = new $controllerName($params);
        if (!$controller instanceof fpcm\controller\abstracts\pubController) {
            exit("ERROR: The controller for <b>$module</b> must be an instance of <b>fpcm\controller\abstracts\pubController</b>. ;)");
            return false;
        }

        if (!$controller->request()) {
            return false;
        }

        $controller->process();
    }

    /**
     * 
     * Exec Latest News display
     * @param array $params params @see \fpcm\controller\action\pub\showlatest
     * @return boolean
     */
    public function showLatestNews(array $params = [])
    {
        if ($this->versionFailed) {
            return false;
        }

        $params['apiMode'] = true;
        $this->initObjects();

        /**
         * @var abstracts\controller
         */
        $controller = new \fpcm\controller\action\pub\showlatest($params);
        if (!$controller instanceof fpcm\controller\abstracts\pubController) {
            exit("ERROR: The controller for <b>$module</b> must be an instance of <b>fpcm\controller\abstracts\pubController</b>. ;)");
            return false;
        }

        if (!$controller->request()) {
            return false;
        }

        $controller->process();
    }

    /**
     * Display current page number
     * @param string $divider
     * @param bool $isUtf8
     */
    public function showPageNumber($divider = "&bull; Page", $isUtf8 = true)
    {
        if ($this->versionFailed) {
            return false;
        }

        $this->initObjects();

        (new fpcm\controller\action\pub\showtitle('page', $divider, $isUtf8))->process();
    }

    /**
     * Display title of currently requested article
     * @param string $divider
     * @param bool $isUtf8
     */
    public function showTitle($divider = "&bull;", $isUtf8 = true)
    {
        if ($this->versionFailed) {
            return false;
        }

        $this->initObjects();

        (new fpcm\controller\action\pub\showtitle('title', $divider, $isUtf8))->process();
    }

    /**
     * Executes article redirect for article urls of FanPress CM 2.5.x and older
     * @param int $articlesPerPage Artikel pro Seite
     * @return boolean
     * @since 3.0.3
     * @deprecated FPCm 4.2.2
     */
    public function legacyRedirect($articlesPerPage = 5)
    {
        if ($this->versionFailed) {
            return false;
        }

        if (isset($_GET['fn']) && trim($_GET['fn']) == 'cmt' && isset($_GET['nid'])) {
            $aricleId = (int) $_GET['nid'];
            header("HTTP/1.1 302 Temporary Redirect");
            header("Location: index.php?module=fpcm/article&id={$aricleId}#contentmarker");
            return true;
        }

        if (isset($_GET['fn']) && trim($_GET['fn']) == 'archive') {
            $page = isset($_GET['apid']) ? (int) $_GET['apid'] / $articlesPerPage : 1;
            $page = ($page > 1 ? '&page=' . $page : '');
            header("HTTP/1.1 302 Temporary Redirect");
            header("Location: index.php?module=fpcm/archive{$page}#contentmarker");
            return true;
        }

        if (isset($_GET['npid'])) {
            $page = (int) $_GET['npid'] / $articlesPerPage;
            $page = ($page > 1 ? '&page=' . $page : '');
            header("HTTP/1.1 302 Temporary Redirect");
            header("Location: index.php?module=fpcm/list{$page}#contentmarker");
            return true;
        }

        return false;
    }

    /**
     * PHP-Magic-Methode __call, ruft Event apiCallFunction auf
     * Form: MODULEKEY mit _ statt _ + "_" +  FUNCTIONNAME (Bsp.: nkorg_example_foo)
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @since FPCM 3.1.5
     */
    public function __call($name, $arguments)
    {
        if ($this->versionFailed) {
            return false;
        }

        $this->initObjects();

        return fpcm\classes\loader::getObject('fpcm\events\events')->trigger('apiCallFunction', [
                    'name' => $name,
                    'args' => $arguments
        ]);
    }

    /**
     * PHP-Magic-Methode __callStatic, ruft Event apiCallFunction auf
     * Form: MODULEKEY mit _ statt _ + "_" +  FUNCTIONNAME (Bsp.: nkorg_example_foo)
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @since FPCM 3.1.5
     */
    public static function __callStatic($name, $arguments)
    {
        if ($this->versionFailed) {
            return false;
        }

        $this->initObjects();

        return fpcm\classes\loader::getObject('fpcm\events\events')->trigger('apiCallFunction', [
                    'name' => $name,
                    'args' => $arguments
        ]);
    }

    /**
     * FPCM-Login für externe Anwendungen nutzen
     * @param array $credentials
     * @return boolean|string
     * @since FPCM 3.4
     */
    public function loginExternal(array $credentials)
    {
        if ($this->versionFailed) {
            return false;
        }

        $session = new \fpcm\model\system\session(false);
        if (isset($credentials['sessionId']) && trim($credentials['sessionId']) && $session->pingExternal($credentials['sessionId'])) {
            return true;
        }

        $credentials['external'] = true;
        if (isset($credentials['username']) && isset($credentials['password']) && trim($credentials['username']) && trim($credentials['password'])) {
            $result = $session->authenticate($credentials, true);
            if ($result === true && $session->save()) {
                return $session->getSessionId();
            }
        }

        trigger_error('Invalid login credentials');
        return false;
    }

    /**
     * Logout für externe Anwendungen nutzen
     * @param string $sessionId
     * @return boolean|string
     * @since FPCM 3.4
     */
    public function logoutExternal($sessionId)
    {
        if ($this->versionFailed) {
            return false;
        }

        $session = new \fpcm\model\system\session(false);
        if (!trim($sessionId) || !$session->pingExternal($sessionId)) {
            trigger_error('Invalid session id');
            return false;
        }

        $session->setLogout(time());
        $session->update();

        return true;
    }

    /**
     * FPCM-interne Verschlüsselung nutzen - Verschlüsseln
     * @param string $value
     * @return string
     * @since FPCM 3.5
     */
    public function fpcmEnCrypt($value)
    {
        if ($this->versionFailed) {
            return false;
        }

        return fpcm\classes\loader::getObject('fpcm\classes\crypt')->encrypt($value);
    }

    /**
     * FPCM-interne Verschlüsselung nutzen - Entschlüsseln
     * @param string $value
     * @return string
     * @since FPCM 3.5
     */
    public function fpcmDeCrypt($value)
    {
        if ($this->versionFailed) {
            return false;
        }

        return fpcm\classes\loader::getObject('fpcm\classes\crypt')->decrypt($value);
    }

    /**
     * Exit script execution on request
     * @param array $param
     * @return bool
     * @since FPCM 4.3
     */
    public function exitOnRequest(array $param) : bool
    {
        if (!\fpcm\classes\security::requestExit($vars)) {
            exit;
        }

        return true;
    }

}

?>