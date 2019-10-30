<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\system;

/**
 * Session Objekt
 * 
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class session extends \fpcm\model\abstracts\dataset {

    use \fpcm\model\traits\eventModuleEmpty;

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
     * @since FPCM 3.5
     */
    protected $external;

    /**
     * Session user agent string
     * @var string
     * @since FPCM 4
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
     * @since FPCM 3.5
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
        if ($this->dbcon->insert(
                $this->table, $this->events->trigger(
                    'session\create', $this->getPreparedSaveParams()
                )
            )
        ) {
            return true;
        }

        return false;
    }

    /**
     * Aktualisiert
     * @return void
     */
    public function update()
    {
        $params = $this->getPreparedSaveParams();
        $fields = array_keys($params);

        $params[] = $this->getSessionId();
        $params = $this->events->trigger('session\update', $params);

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
            trigger_error('Login failed for username ' . $data['username'] . '! Wrong username or password. Request was made by ' . \fpcm\classes\http::getIp());
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
        $this->sessionid = \fpcm\classes\security::createSessionId();
        $this->ip = \fpcm\classes\http::getIp();
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
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();            
        }

        $expire = $this->getLastaction() + $this->config->system_session_length;

        $crypt = \fpcm\classes\loader::getObject('\fpcm\classes\crypt');
        return setcookie(\fpcm\classes\security::getSessionCookieName(), '_$$' . $crypt->encrypt($this->sessionid), $expire, '/', '', false, true);
    }

    /**
     * Löscht Cookie
     * @return bool
     */
    public function deleteCookie()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        
        (new \fpcm\classes\pageTokens)->delete();

        $expire = $this->getLogin() - ($this->config->system_session_length * 5);
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

        if (!$listItems)
            return $sessions;

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
        $lastaction = time() + $this->config->system_session_length;

        if (version_compare($this->config->system_version, '4.0.0', '<')) {
            $lastaction = time() + $this->config->system_session_length;
            $data = $this->dbcon->fetch($this->dbcon->select($this->table, '*', "sessionid = ? AND logout = 0 AND lastaction <= ? " . $this->dbcon->limitQuery(1, 0), array($this->sessionid, $lastaction)));

            if ($data === false) {
                $this->sessionExists = false;
                return;
            }

            foreach ($data as $key => $value) {
                $this->$key = $value;
            }

            $this->currentUser = new \fpcm\model\users\author($this->userid);
            $this->sessionExists = true;

            return true;
        }


        $this->currentUser = new \fpcm\model\users\author();

        $cols = [];
        foreach (array_keys($this->getPreparedSaveParams()) as $col) {
            $cols[] = 'sess.' . $col . ' as sess_' . $col;
        }

        foreach (array_keys($this->currentUser->getPreparedSaveParams()) as $col) {
            $cols[] = 'usr.' . $col . ' as usr_' . $col;
        }

        $obj = (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableAuthors . ' usr JOIN ' . $this->dbcon->getTablePrefixed($this->table) . ' sess ON (sess.userid = usr.id)'))
                ->setItem('sess.id as sess_id, usr.id as usr_id, ' . implode(', ', $cols))
                ->setWhere("sess.sessionid = ? AND sess.logout = 0 AND sess.lastaction <= ? " . $this->dbcon->limitQuery(1, 0))
                ->setParams([$this->sessionid, $lastaction]);

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

        if (session_status() !== PHP_SESSION_ACTIVE) {
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
     * @since FPCM 3.4
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

}

?>