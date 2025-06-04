<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

/**
 * Session Objekt
 *
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class session extends \fpcm\model\abstracts\dataset implements \fpcm\model\interfaces\isObjectInstancable {

    use \fpcm\model\traits\eventModuleEmpty,
        \fpcm\model\traits\getObjectInstance;

    /**
     * Session-ID
     * @var string
     */
    protected $sessionid;

    /**
     * Benutzer-ID
     * @var int
     */
    protected $userid;

    /**
     * Login-Zeit
     * @var int
     */
    protected $login;

    /**
     * Logout-Zeit
     * @var int
     */
    protected $logout;

    /**
     * Letzte Aktualisierung
     * @var int
     */
    protected $lastaction;

    /**
     * IP-Adresse der Session
     * @var string
     */
    protected $ip;

    /**
     * Kam Session durch externen Login via API zustande?
     * @var bool
     * @since 3.5
     */
    protected $external;

    /**
     * Session user agent string
     * @var string
     * @since 4
     */
    protected $useragent = '';

    /**
     * Existiert Session
     * @var bool
     */
    protected $sessionExists = false;

    /**
     * Klassen-Eigenschaften, die nicht gespeichert werden sollen
     * @var array
     */
    protected $dbExcludes = array('sessionExists', 'currentUser', 'permissions');

    /**
     * Aktueller Benutzer
     * @var \fpcm\model\users\author
     */
    public $currentUser;

    /**
     * Initialisiert Session
     * @param int $init
     */
    public function __construct($init = true)
    {
        parent::__construct();

        $this->table = \fpcm\classes\database::tableSessions;

        if (!$init || \fpcm\classes\security::getSessionCookieValue() === null) {
            return false;
        }

        $this->sessionid = \fpcm\classes\security::getSessionCookieValue();
        $this->init();

        if (!$this->sessionExists) {
            return false;
        }

        \fpcm\classes\loader::stackPush('currentUser', $this->currentUser);
        if ($this->lastaction > time() - 60) {
            return true;
        }

        $this->lastaction = time();
        $this->update();
        $this->setCookie();
    }

    /**
     * Session-ID-String zurückgeben
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionid;
    }

    /**
     * ID des aktuellen Benutzers
     * @return int
     */
    public function getUserId()
    {
        return (int) $this->userid;
    }

    /**
     * Login-Zeit zurückgeben
     * @return int
     */
    public function getLogin()
    {
        return (int) $this->login;
    }

    /**
     * Logout-Zeit zurückgeben
     * @return int
     */
    public function getLogout()
    {
        return (int) $this->logout;
    }

    /**
     * Zeit der letzten Aktion +/- 60sec
     * @return int
     */
    public function getLastaction()
    {
        return (int) $this->lastaction;
    }

    /**
     * IP-Adresse des aktuellen Benutzers
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Flag auslesen, ob externe Session
     * @return int
     * @since 3.5
     */
    public function getExternal()
    {
        return (int) $this->external;
    }

    /**
     * User agent string
     * @return string
     */
    public function getUseragent()
    {
        return $this->useragent;
    }

    /**
     * Session-ID-String setzen
     * @param string $sessionid
     */
    public function setSessionId($sessionid)
    {
        $this->sessionid = $sessionid;
    }

    /**
     * Benutzer-ID setzen
     * @param int $userid
     */
    public function setUserId($userid)
    {
        $this->userid = (int) $userid;
    }

    /**
     * Login-Zeit setzen
     * @param int $login
     */
    public function setLogin($login)
    {
        $this->login = (int) $login;
    }

    /**
     * Logout-Zeit setzen
     * @param int $logout
     */
    public function setLogout($logout)
    {
        $this->logout = (int) $logout;
    }

    /**
     * Zeitpunkt letzter Aktion setzen
     * @param int $lastaction
     */
    public function setLastaction($lastaction)
    {
        $this->lastaction = (int) $lastaction;
    }

    /**
     * IP-Adresse setzen
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Flag externer Login via API setzen
     * @param bool $external
     */
    public function setExternal($external)
    {
        $this->external = (int) $external;
    }

    /**
     * Set user agent data
     * @param string $useragent
     */
    public function setUseragent($useragent)
    {
        $this->useragent = $useragent;
    }

    /**
     * Session-Status setzen
     * @param bool $sessionExists
     */
    public function setSessionExists($sessionExists)
    {
        $this->sessionExists = $sessionExists;
    }

    /**
     * Gibt aktuellen Benutzer dieser Session zurück
     * @return \fpcm\model\users\author
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Prüft, ob Session existiert
     * @return bool
     */
    public function exists()
    {
        return (bool) $this->sessionExists;
    }

    /**
     * Speichert
     * @return void
     */
    public function save()
    {
        $ev = $this->events->trigger('session\create', $this->getPreparedSaveParams());
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event session\create failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        return $this->dbcon->insert(
            $this->table,
            $ev->getData()
        ) ? true : false;
    }

    /**
     * Aktualisiert
     * @return void
     */
    public function update()
    {
        $params = $this->getPreparedSaveParams();
        $params[] = $this->getSessionId();

        $ev = $this->events->trigger('session\update', $params);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event session\update failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $params = $ev->getData();
        $fields = $this->getFieldFromSaveParams($params);

        $return = false;
        if ($this->dbcon->update($this->table, $fields, array_values($params), 'sessionid = ?')) {
            $return = true;
        }

        $this->init();

        return $return;
    }

    /**
     * not used
     * @return void
     */
    public function delete()
    {
        return;
    }

    /**
     * Check if authentication information in $data matchs data of active \fpcm\model\abstracts\authProvider object
     * @param array $data
     * @param bool $external
     * @return bool
     */
    public function authenticate(array $data, $external = false)
    {
        $userid = \fpcm\components\components::getAuthProvider()->authenticate($data);
        if ($userid === false) {
            return false;
        }

        if ($userid !== false && $userid < 0) {
            return $userid;
        }

        $timer = time();

        $this->login = $timer;
        $this->lastaction = $timer;
        $this->logout = 0;
        $this->userid = $userid;
        $this->sessionid = $this->generateSessionId();
        $this->ip = \fpcm\classes\loader::getObject('\fpcm\model\http\request')->getIp();
        $this->external = (int) $external;
        $this->useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $this->sessionExists = true;

        return true;
    }

    /**
     * Setzt Login-Cookie
     * @return bool
     */
    public function setCookie()
    {
        if (!defined('FPCM_MODE_NOPAGETOKEN') && session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $expire = $this->getLastaction() + (int) FPCM_USER_SESSION;

        return (new \fpcm\model\http\cookie( \fpcm\classes\security::getSessionCookieName() ))
                ->setExpires($expire)
                ->set('_$$' . \fpcm\classes\loader::getObject('\fpcm\classes\crypt')->encrypt($this->sessionid));
    }

    /**
     * Löscht Cookie
     * @return bool
     */
    public function deleteCookie()
    {
        if (!defined('FPCM_MODE_NOPAGETOKEN') && session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        (new \fpcm\classes\pageTokens)->delete();

        $expire = $this->getLogin() - ((int) FPCM_USER_SESSION * 5);
        return setcookie(\fpcm\classes\security::getSessionCookieName(), 0, $expire, '/', '', false, true);
    }

    /**
     * Gibt gespeicherte Session-Informationen zurück
     * @return array
     */
    public function getSessions()
    {
        $sessions = [];

        $listItems = $this->dbcon->fetch($this->dbcon->select($this->table, '*', "sessionid NOT " . $this->dbcon->dbLike() . " ?", array($this->sessionid)), true);

        if (!$listItems) {
            return $sessions;
        }

        foreach ($listItems as $listItem) {
            $sessionItem = new session(false);
            $sessionItem->createFromDbObject($listItem);

            $sessions[] = $sessionItem;
        }

        return $sessions;
    }

    /**
     * Retrieve sessions list by condition
     * @param string $search
     * @param array $params
     * @return array|\fpcm\model\system\session
     */
    public function getSessionsByCondition(string $search = '', array $params = [])
    {
        $sessions = [];

        $sparams = new \fpcm\model\dbal\selectParams($this->table);

        $where = "sessionid NOT " . $this->dbcon->dbLike() . " ?";

        if (trim($search)) {
            $where = sprintf("%s %s" , $where, $search);
        }

        $sparams->setWhere($where);
        $sparams->setParams(array_merge([$this->sessionid], $params));
        $sparams->setFetchAll(true);

        $listItems = $this->dbcon->selectFetch($sparams);

        if (!$listItems) {
            return $sessions;
        }

        foreach ($listItems as $listItem) {
            $sessionItem = new session(false);
            $sessionItem->createFromDbObject($listItem);

            $sessions[] = $sessionItem;
        }

        return $sessions;
    }

    /**
     * Sessions löschen
     * @return bool
     */
    public function clearSessions()
    {
        return $this->dbcon->delete($this->table, "sessionid NOT " . $this->dbcon->dbLike() . " ? AND lastaction < ?", array($this->sessionid, time()));
    }

    /**
     * Inittiert Objekt mit Daten aus der Datenbank, sofern ID vergeben wurde
     */
    public function init()
    {
        if ($this->sessionid === null) {
            $this->sessionExists = false;
            return;
        }

        $this->currentUser = new \fpcm\model\users\author();

        $obj = (new \fpcm\model\dbal\selectParams( \fpcm\classes\database::viewSessionUserdata ))
                ->setWhere( "sess_sessionid = ? AND sess_logout = 0 AND sess_lastaction <= ? " . $this->dbcon->limitQuery(1, 0) )
                ->setParams([$this->sessionid, time() + (int) FPCM_USER_SESSION ]);

        $data = $this->dbcon->selectFetch($obj);

        if ($data === false) {
            $this->sessionExists = false;
            return;
        }

        $userData = new \stdClass();
        foreach ($data as $key => $value) {

            if (substr($key, 0, 3) === 'usr') {
                $userData->{substr($key, 4)} = $value;
                continue;
            }

            $this->{substr($key, 5)} = $value;
        }

        $this->currentUser->createFromDbObject($userData);
        $this->sessionExists = true;

        if (!defined('FPCM_MODE_NOPAGETOKEN') && session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Magic get
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {

        if ($name == 'sessionId') {
            return $this->sessionid;
        }

        if ($name == 'userId') {
            return $this->userid;
        }

        parent::__get($name);
    }

    /**
     * Magic set
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {

        if ($name == 'sessionId') {
            $this->sessionid = $value;
            return true;
        }

        if ($name == 'userId') {
            $this->userid = $value;
        }

        parent::__set($name, $value);
    }

    /**
     * Prüft ob übergebene Session-ID existiert und noch gültig ist
     * @param string $sessionId
     * @return bool
     * @since 3.4
     */
    public function pingExternal($sessionId)
    {

        $this->sessionid = $sessionId;
        $this->init();

        if (!$this->sessionExists) {
            return false;
        }

        return true;
    }

    /**
     * Generates session id string
     * @return string
     * @since 4.3
     */
    public function generateSessionId() : string
    {
        return \fpcm\classes\tools::getHash(bin2hex(random_bytes(64)));
    }

    /**
     * Returns config class instance
     * @return session
     * @since 5.1.a-a1
     */
    public static function getInstance()
    {
        return self::getObjectInstance();
    }

}