<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

/**
 * FPCM frontend api flag
 */
define('FPCM_FE', true);
define('FPCM_MODE_NOPAGETOKEN', true);

/**
 * Include of base files
 */
require_once __DIR__ . '/inc/controller/main.php';
require_once __DIR__ . '/inc/common.php';

/**
 * FanPress CM API, class for integration into a website
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2021, Stefan Seehafer
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
        $this->versionFailed = version_compare(PHP_VERSION, FPCM_PHP_REQUIRED, '<') || !\fpcm\classes\baseconfig::dbConfigExists() || \fpcm\classes\baseconfig::installerEnabled();
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
     * @since 4.2.1
     */
    private function initObjects() : bool
    {
        \fpcm\classes\loader::getObject('\fpcm\model\http\request');
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
     * @return bool
     */
    public function showArticles(array $params = [])
    {
        if ($this->versionFailed) {
            return false;
        }

        $this->registerController();
        $this->initObjects();

        $module = (new \fpcm\model\http\request)->getModule();

        if (!trim($module)) {
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
     * @return bool
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
     */
    public function showPageNumber($divider = "&bull; Page")
    {
        if ($this->versionFailed) {
            return false;
        }

        $this->initObjects();

        (new fpcm\controller\action\pub\showtitle('page', $divider))->process();
    }

    /**
     * Display title of currently requested article
     * @param string $divider
     */
    public function showTitle($divider = "&bull;")
    {
        if ($this->versionFailed) {
            return false;
        }

        $this->initObjects();

        (new fpcm\controller\action\pub\showtitle('title', $divider))->process();
    }

    /**
     * PHP-Magic-Methode __call, ruft Event apiCallFunction auf
     * Form: MODULEKEY mit _ statt _ + "_" +  FUNCTIONNAME (Bsp.: nkorg_example_foo)
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @since 3.1.5
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
        ])->getData();

    }

    /**
     * PHP-Magic-Methode __callStatic, ruft Event apiCallFunction auf
     * Form: MODULEKEY mit _ statt _ + "_" +  FUNCTIONNAME (Bsp.: nkorg_example_foo)
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @since 3.1.5
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
        ])->getData();
    }

    /**
     * FPCM-Login für externe Anwendungen nutzen
     * @param array $credentials
     * @return bool|string
     * @since 3.4
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
     * @return bool|string
     * @since 3.4
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
     * @since 3.5
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
     * @since 3.5
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
     * @since 4.3
     */
    public function exitOnRequest(array $param) : bool
    {
        if (!\fpcm\classes\security::requestExit($vars)) {
            exit;
        }

        return true;
    }

    /**
     * Display messages div in frontend
     * @return void
     * @since 4.4
     */
    public function showMessagesBox()
    {
        require_once fpcm\classes\dirs::getCoreDirPath(fpcm\classes\dirs::CORE_VIEWS, 'common/messagesTpl.php');
    }

    /**
     * Check if IP adress is locked
     * @param string $lockType
     * @return bool
     * @see \fpcm\model\ips\iplist::ipIsLocked
     * @since 4.4.1
     */
    public function checkLockedIp($lockType = 'noaccess') : bool
    {
        return (new \fpcm\model\ips\iplist)->ipIsLocked($lockType) ? true : false;
    }

    /**
     * Send e-mail via system settings
     * @param array $param
     * @since 4.5.2
     */
    public function sendMail(array $param) : bool
    {
        if (empty($param['to'])) {
            trigger_error('No message recipient set!');
            return false;
        }

        if (empty($param['subject'])) {
            trigger_error('No message subject set!');
            return false;
        }

        if (empty($param['text'])) {
            trigger_error('No message content set!');
            return false;
        }

        if (!isset($param['html'])) {
            $param['html'] = false;
        }


        $mail = new fpcm\classes\email($param['to'], $param['subject'], $param['text'], false, $param['html']);
        if (isset($param['attachments'])) {
            $mail->setAttachments($param['attachments']);
        }

        return $mail->submit();
    }

    /**
     * Check if maintenance mode is enabled
     * @since 4.5.2
     */
    public function isMaintenance() : bool
    {
        return \fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_maintenance;
    }

    /**
     * Returns url of fanpress/js/fpcm(.min).js
     * @return string
     * @since 4.5.2
     */
    public function getPublicJsFile() : string
    {
        define('FPCM_PUBJS_LOADED', 1);

        if ( defined('FPCM_DEBUG') && FPCM_DEBUG ||
             !file_exists(\fpcm\classes\dirs::getFullDirPath('js/fpcm.min.js') ) )  {
            return \fpcm\classes\dirs::getRootUrl('js/fpcm.js');
        }

        return \fpcm\classes\dirs::getRootUrl('js/fpcm.min.js');
    }

}

?>
