<?php
    /**
     * Session object
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\system;

    /**
     * Session Objekt
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class session extends \fpcm\model\abstracts\model {

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
         * Existiert Session
         * @var bool
         */
        protected $sessionExists  = false;
        
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
        public function __construct($init = true) {
            parent::__construct();
            
            $this->table    = \fpcm\classes\database::tableSessions;
            
            if (!is_object($this->config)) {
                $this->config = new config(false);
            }

            if ($init && !is_null(\fpcm\classes\security::getSessionCookieValue())) {                
                $this->sessionid = \fpcm\classes\security::getSessionCookieValue();            
                $this->init();
                
                if ($this->sessionExists) {                    
                    if (!defined('FPCM_USERID')) {
                        /**
                         * ID des aktuellen Benutzers, nur verfügbar wenn Session existiert
                         */
                        define('FPCM_USERID', $this->userid);
                    }
                    
                    $this->currentUser = new \fpcm\model\users\author($this->userid);
                    if ($this->lastaction <= time() - 60) {
                        $this->lastaction  = time();
                        $this->update();
                        $this->setCookie();
                    }
                }               
            }
        }
        
        /**
         * Session-ID-String zurückgeben
         * @return string
         */
        public function getSessionId() {
            return $this->sessionid;
        }

        /**
         * ID des aktuellen Benutzers
         * @return int
         */
        public function getUserId() {
            return (int) $this->userid;
        }

        /**
         * Login-Zeit zurückgeben
         * @return int
         */
        public function getLogin() {
            return (int) $this->login;
        }

        /**
         * Logout-Zeit zurückgeben
         * @return int
         */
        public function getLogout() {
            return (int) $this->logout;
        }

        /**
         * Zeit der letzten Aktion +/- 60sec
         * @return int
         */
        public function getLastaction() {
            return (int) $this->lastaction;
        }

        /**
         * IP-Adresse des aktuellen Benutzers
         * @return string
         */
        public function getIp() {
            return $this->ip;
        }

        /**
         * Flag auslesen, ob externe Session
         * @return int
         * @since FPCM 3.5
         */
        public function getExternal() {
            return (int) $this->external;
        }

        /**
         * Session-ID-String setzen
         * @param string $sessionid
         */
        public function setSessionId($sessionid) {
            $this->sessionid = $sessionid;
        }

        /**
         * Benutzer-ID setzen
         * @param int $userid
         */
        public function setUserId($userid) {
            $this->userid = (int) $userid;
        }

        /**
         * Login-Zeit setzen
         * @param int $login
         */
        public function setLogin($login) {
            $this->login = (int) $login;
        }

        /**
         * Logout-Zeit setzen
         * @param int $logout
         */
        public function setLogout($logout) {
            $this->logout = (int) $logout;
        }

        /**
         * Zeitpunkt letzter Aktion setzen
         * @param int $lastaction
         */
        public function setLastaction($lastaction) {
            $this->lastaction = (int) $lastaction;
        }

        /**
         * IP-Adresse setzen
         * @param string $ip
         */
        public function setIp($ip) {
            $this->ip = $ip;
        }

        /**
         * Flag externer Login via API setzen
         * @param bool $external
         */
        public function setExternal($external) {
            $this->external = (int) $external;
        }

        /**
         * Session-Status setzen
         * @param bool $sessionExists
         */
        public function setSessionExists($sessionExists) {
            $this->sessionExists = $sessionExists;
        }
        
        /**
         * Gibt aktuellen Benutzer dieser Session zurück
         * @return \fpcm\model\users\author
         */
        public function getCurrentUser() {
            return $this->currentUser;
        }

        /**
         * Prüft, ob Session existiert
         * @return bool
         */
        public function exists() {
            return (bool) $this->sessionExists;
        }        
        
        /**
         * Speichert
         * @return void
         */
        public function save() {
            $params = $this->getPreparedSaveParams();
            $params = $this->events->runEvent('sessionCreate', $params);

            $value_params = $this->getPreparedValueParams();

            $return = false;
            if ($this->dbcon->insert($this->table, implode(',', array_keys($params)), implode(', ', $value_params), array_values($params))) {
                $return = true;
            }
            
            return $return;            
        }
        
        /**
         * Aktualisiert
         * @return void
         */
        public function update() {
            $params     = $this->getPreparedSaveParams();
            $fields     = array_keys($params);
            
            $params[]   = $this->getSessionId();
            $params     = $this->events->runEvent('sessionUpdate', $params);

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
        public function delete() {
            return;
        }
        
        /**
         * Prüft ob Kombination Benutzer und Passwort existiert
         * @param string $username
         * @param string $password
         * @param bool $external
         * @return bool Ja, wenn Benutzer + Passwort vorhanden ist
         */
        public function checkUser($username, $password, $external = false) {

            $userList = new \fpcm\model\users\userList();            

            $userid = $userList->getUserIdByUsername($username);
            if (!$userid) {
                trigger_error('Login failed for username '.$username.'! User not found. Request was made by '.\fpcm\classes\http::getIp());
                return false;
            }

            $user = new \fpcm\model\users\author($userid);
            if ($user->getDisabled()) {
                trigger_error('Login failed for username '.$username.'! User is disabled. Request was made by '.\fpcm\classes\http::getIp());
                return \fpcm\model\users\author::AUTHOR_ERROR_DISABLED;
            }

            if (\fpcm\classes\security::createPasswordHash($password, $user->getPasswd()) == $user->getPasswd()) {
                
                $timer = time();
                
                $this->login         = $timer;
                $this->lastaction    = $timer;
                $this->logout        = 0;
                $this->userid        = $userid;
                $this->sessionid     = \fpcm\classes\security::createSessionId();
                $this->ip            = \fpcm\classes\http::getIp();
                $this->external      = (int) $external;
                $this->sessionExists = true;
                
                return true;
            }
            
            trigger_error('Login failed for username '.$username.'! Wrong username or password. Request was made by '.\fpcm\classes\http::getIp());
            
            return false;
            
        }
        
        /**
         * Setzt Login-Cookie
         * @return bool
         */
        public function setCookie() {       
            $expire = $this->getLastaction() + $this->config->system_session_length;
            
            $crypt = new \fpcm\classes\crypt();
            return setcookie(\fpcm\classes\security::getSessionCookieName(), '_$$'.$crypt->encrypt($this->sessionid), $expire, '/', '', false, true);
        }
                
        /**
         * Löscht Cookie
         * @return bool
         */
        public function deleteCookie() {       
            $expire = $this->getLogin() - ($this->config->system_session_length * 5);
            return setcookie(\fpcm\classes\security::getSessionCookieName(), 0, $expire, '/', '', false, true);
        }
        
        /**
         * Gibt gespeicherte Session-Informationen zurück
         * @return array
         */
        public function getSessions() {
            $sessions = [];
            
            $listItems = $this->dbcon->fetch($this->dbcon->select($this->table, '*', "sessionid NOT ".$this->dbcon->dbLike()." ?", array($this->sessionid)), true);
            
            if (!$listItems) return $sessions;
            
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
        public function clearSessions() {
            return $this->dbcon->delete($this->table, "sessionid NOT ".$this->dbcon->dbLike()." ? AND lastaction < ?", array($this->sessionid, time()));
        }

        /**
         * Inittiert Objekt mit Daten aus der Datenbank, sofern ID vergeben wurde
         */
        protected function init() {
            
            $lastaction = time() + $this->config->system_session_length;
            $data = $this->dbcon->fetch($this->dbcon->select($this->table, '*', "sessionid = ? AND logout = 0 AND lastaction <= ? ".$this->dbcon->limitQuery(1, 0), array($this->sessionid, $lastaction)));

            if ($data === false) {
                $this->sessionExists = false;
                return;
            }

            foreach ($data as $key => $value) {
                $this->$key = $value;
            }

            $this->sessionExists = true;
        }
        
        /**
         * Magic get
         * @param string $name
         * @return mixed
         */
        public function __get($name) {
            
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
        public function __set($name, $value) {
            
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
         * @return boolean
         * @since FPCM 3.4
         */
        public function pingExternal($sessionId) {
            
            $this->sessionid = $sessionId;
            $this->init();

            if (!$this->sessionExists) {
                return false;
            }

            return true;

        }

    }
?>