<?php
/**
 * FanPress CM 3.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

require_once __DIR__.'/inc/controller/main.php';
require_once __DIR__.'/inc/common.php';

/**
 * FanPress CM API class
 * Class for integration of FanPress CM into a website
 * 
 * @package fpcmapi
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */    
class fpcmAPI {

    /**
     * API-Controller
     * @var array
     */
    protected $controllers = array();

    /**
     * Ausführung unter PHP 5.4+
     * @var bool
     */
    protected $versionFailed = false;

    /**
     * Konstruktor, prüft PHP-Version, Installer-Status und Datenbank-Config-Status
     * @return void
     */
    public function __construct() {

        if (version_compare(PHP_VERSION, FPCM_PHP_REQUIRED, '<') || !\fpcm\classes\baseconfig::dbConfigExists() || \fpcm\classes\baseconfig::installerEnabled()) {
            $this->versionFailed = true;
            return;
        }            

        \fpcm\classes\http::init();        
    }

    /**
     * Lädt FPCM-Controller
     */
    public function registerController() {
        $this->controllers = \fpcm\classes\baseconfig::getControllers();
    }

    /**
     * Artikel anzeigen
     * @return boolean
     */
    public function showArticles() {

        if ($this->versionFailed) return false;

        $this->registerController();

        $module = (!is_null(\fpcm\classes\http::get('module'))) ? \fpcm\classes\http::get('module', array(1,4,7)) : 'fpcm/list';
        if (strpos($module, 'fpcm/') === false || !in_array($module, array('fpcm/list', 'fpcm/article', 'fpcm/archive'))) return false;

        $controllerName  = "fpcm/controller/";        
        $controllerName .= (isset($this->controllers[$module])) ? $this->controllers[$module] : ($module ? $module : 'action\system\login');
        $controllerName  = str_replace('/', '\\', $controllerName);       

        if (!class_exists($controllerName)) {
            trigger_error('Undefined controller called: '.$module);
            return false;
        }

        /**
         * @var abstracts\controller
         */
        $controller = new $controllerName(true);    

        if (!is_a($controller, 'fpcm\controller\abstracts\controller')) {
            die("ERROR: The controller for <b>$module</b> must be an instance of <b>fpcm\controller\abstracts\controller</b>. ;)");
            return false;
        }

        if (!$controller->request()) return false;

        $controller->process();
    }

    /**
     * Latest News anzeigen
     * @return boolean
     */
    public function showLatestNews() {

        if ($this->versionFailed) return false;

        /**
         * @var abstracts\controller
         */
        $controller = new \fpcm\controller\action\pub\showlatest(true);

        if (!is_a($controller, 'fpcm\controller\abstracts\controller')) {
            die("ERROR: The controller for <b>$module</b> must be an instance of <b>fpcm\controller\abstracts\controller</b>. ;)");
            return false;
        }

        if (!$controller->request()) return false;

        $controller->process();
    }

    /**
     * aktuelle Seitennummer anzeigen
     * @param string $divider
     */
    public function showPageNumber($divider = "&bull; Page") {            
        $controller = new fpcm\controller\action\pub\showtitle('page', $divider);
        $controller->process();
    }

    /**
     * Title des aktuellen Artikels anzeigen
     * @param string $divider
     */
    public function showTitle($divider = "&bull;") {            
        $controller = new fpcm\controller\action\pub\showtitle('title', $divider);
        $controller->process();
    }

    /**
     * Weiterleitung für alte Artikel-Liste bis FanPress CM 2.5.x
     * @param int $articlesPerPage Artikel pro Seite
     * @return boolean
     * @since 3.0.3
     */
    public function legacyRedirect($articlesPerPage = 5) {

        if (isset($_GET['fn']) && trim($_GET['fn']) == 'cmt' && isset($_GET['nid'])) {
            $aricleId = (int) $_GET['nid'];
            header("HTTP/1.1 302 Temporary Redirect");
            header("Location: index.php?module=fpcm/article&id={$aricleId}#contentmarker");
            return true;
        }

        if (isset($_GET['fn']) && trim($_GET['fn']) == 'archive') {
            $page = isset($_GET['apid']) ? (int) $_GET['apid'] / $articlesPerPage : 1;
            $page = ($page > 1 ? '&page='.$page : '');
            header("HTTP/1.1 302 Temporary Redirect");
            header("Location: index.php?module=fpcm/archive{$page}#contentmarker");
            return true;
        }

        if (isset($_GET['npid'])) {
            $page = (int) $_GET['npid'] / $articlesPerPage;
            $page = ($page > 1 ? '&page='.$page : '');
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
    public function __call($name, $arguments) {

        /* @var $eventList fpcm\model\events\eventList */
        $eventList = fpcm\classes\baseconfig::$fpcmEvents;

        $params = array(
            'name'   => $name,
            'args' => $arguments
        );

        return $eventList->runEvent('apiCallFunction', $params);
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
    public static function __callStatic($name, $arguments) {
        /* @var $eventList fpcm\model\events\eventList */
        $eventList = fpcm\classes\baseconfig::$fpcmEvents;

        $params = array(
            'name' => $name,
            'args' => $arguments
        );

        return $eventList->runEvent('apiCallFunction', $params);
    }

    /**
     * FPCM-Login für externe Anwendungen nutzen
     * @param array $credentials
     * @return boolean|string
     * @since FPCM 3.4
     */
    public function loginExternal(array $credentials) {

        $session = new \fpcm\model\system\session(false);
        if (isset($credentials['sessionId']) && trim($credentials['sessionId']) && $session->pingExternal($credentials['sessionId'])) {
            return true;
        }

        if (isset($credentials['username']) && isset($credentials['password']) && trim($credentials['username']) && trim($credentials['password'])) {
            $result = $session->checkUser($credentials['username'], $credentials['password'], true);
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
    public function logoutExternal($sessionId) {

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
    public function fpcmEnCrypt($value) {
        $crypt = new \fpcm\classes\crypt();
        return $crypt->encrypt($value);
    }

    /**
     * FPCM-interne Verschlüsselung nutzen - Entschlüsseln
     * @param string $value
     * @return string
     * @since FPCM 3.5
     */
    public function fpcmDeCrypt($value) {
        $crypt = new \fpcm\classes\crypt();
        return $crypt->decrypt($value);
    }

}

?>