<?php
    /**
     * FanPress CM Author/ User Model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    namespace fpcm\model\users;

    /**
     * Benutzer Objekt
     * 
     * @package fpcm\model\user
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class author extends \fpcm\model\abstracts\model {
        
        /**
         * Error-Code: Benutzer existiert
         */
        const AUTHOR_ERROR_EXISTS = -1;
        
        /**
         * Error-Code: Passwort ist unsicher
         */
        const AUTHOR_ERROR_PASSWORDINSECURE = -2;
        
        /**
         * Error-Code: Keine E-Mail-Adresse angegeben
         */
        const AUTHOR_ERROR_NOEMAIL = -3;
        
        /**
         * Error-Code: Benutzer ist deaktiviert
         */
        const AUTHOR_ERROR_DISABLED = -4;
        
        /**
         * Anzeigeter Name
         * @var string
         */
        protected $displayname;
        
        /**
         *E-Mail-Adresse
         * @var string
         */
        protected $email;
        
        /**
         * Zeit, an dem der Benutzer angelegt wurde
         * @var int
         */
        protected $registertime;
        
        /**
         * Benutzername
         * @var string
         */
        protected $username;
        
        /**
         * sha256-Hash des Passwortes
         * @var string
         */
        protected $passwd;
        
        /**
         * sha256-Hash des Salts
         * @var string
         */
        protected $salt;
        
        /**
         * Benutezrrolle
         * @var int
         */
        protected $roll;
        
        /**
         * Deaktiviert
         * @var int
         */        
        protected $disabled;

        /**
         * Meta-Daten für persönliche Einstellungen
         * @var string
         */
        protected $usrmeta = '';

        /**
         * Author-Beschreibung
         * @var string
         * @since FPCM 3.6
         */
        protected $usrinfo = '';

        /**
         * Übersetzter Gruppenname
         * @var string
         * @since FPCM 3.4
         */
        protected $groupname;

        /**
         * Authoren-Bild
         * @var string
         * @since FPCM 3.6
         */
        protected $image = '';
        
        /**
         * Edit action string
         * @var string
         */
        protected $editAction = 'users/edit&userid=';
        
        /**
         * Wortsperren-Liste
         * @var \fpcm\model\wordban\items
         * @since FPCM 3.2.0
         */
        protected $wordbanList;
        
        /**
         * Eigenschaften, welche beim Speichern in DB nicht von getPreparedSaveParams() zurückgegeben werden sollen
         * @var array
         */
        protected $dbExcludes = array('groupname', 'image');
        
        /**
         * Konstruktor
         * @param int $id
         */
        public function __construct($id = null) {
            $this->table = \fpcm\classes\database::tableAuthors;
            $this->wordbanList = new \fpcm\model\wordban\items();
            
            if (!is_null($id)) $this->cacheName = 'author'.$id;
            
            parent::__construct($id);
        }
        
        /**
         * Liefert anzeigten Name zurück
         * @return string
         */
        public function getDisplayname() {
            return $this->displayname;
        }
        
        /**
         * Liefert E-Mail-Adresse zurück
         * @return string
         */
        public function getEmail() {
            return $this->email;
        }
        
        /**
         * Liefert Datum der Anmeldung zurück
         * @return int
         */
        public function getRegistertime() {
            return $this->registertime;
        }
        
        /**
         * Liefert Benutzername zurück
         * @return string
         */
        public function getUsername() {
            return $this->username;
        } 
        
        /**
         * Liefert Passwort-Hash zurück
         * @return string
         */public function getPasswd() {
            return $this->passwd;
        }
        
        /**
         * Liefert Rollen-ID zurück
         * @return string
         */
        public function getRoll() {
            return (int) $this->roll;
        }
        
        /**
         * Rollen-ID setzen
         * @param int $roll
         */
        public function setRoll($roll) {
            $this->roll = (int) $roll;
        }
        
        /**
         * Passwort-Salt auslesen
         * @return string
         */
        public function getSalt() {
            return $this->salt;
        }

        /**
         * Passwort-Salt setzen
         * @param string $salt
         */
        public function setSalt($salt) {
            $this->salt = $salt;
        }        
        
        /**
         * Status ob Benutzer deaktiviert ist auslesen
         * @return bool
         */
        public function getDisabled() {
            return $this->disabled;
        }

        /**
         * Übersetzter Gruppenname
         * @return string
         * @since FPCM 3.4
         */        
        function getGroupname() {
            return $this->groupname;
        }

        /**
         * Kurze Authoren-Beschreibung setzen
         * @since FPCM 3.6
         */
        public function getUsrinfo() {
            return $this->usrinfo;
        }

        /**
         * Author-Bild zurückliefern
         * @since FPCM 3.6
         */
        public function getImage() {
            return $this->image;
        }

        /**
         * Deaktiviert-Status setzen
         * @param bool $disabled
         */
        public function setDisabled($disabled) {
            $this->disabled = $disabled;
        }

        /**
         * Liefert ben.-def. Einstellungen zurück
         * @param string $valueName
         * @return string|array
         */
        public function getUserMeta($valueName = null) {
            $userMeta = json_decode($this->usrmeta, true);
            
            if (is_null($valueName))          {
                return $userMeta;                
            }

            if (isset($userMeta[$valueName])) {
                return $userMeta[$valueName];
            }
            
            return $this->config->{$valueName};
        }
        
        /**
         * ist Benutzer ein Administrator
         * @return bool
         */
        public function isAdmin() {
            return $this->roll == 1 ? true : false;
        }

        /**
         * Angezeigten Name setzen
         * @param string $displayname
         */
        public function setDisplayName($displayname) {
            $this->displayname = $displayname;
        }

        /**
         * E-Mail-Adresse setzen
         * @param string $email
         */
        public function setEmail($email) {
            $this->email = $email;
        }

        /**
         * Anmelde-Datum setzen
         * @param string $registertime
         */
        public function setRegistertime($registertime) {
            $this->registertime = $registertime;
        }

        /**
         * Benutzername setzen
         * @param string $username
         */
        public function setUserName($username) {
            $this->username = $username;
        }

        /**
         * Passwort-Hash setzen
         * @param string $passwd
         */
        public function setPassword($passwd) {
            $this->passwd = $passwd;
        }

        /**
         * ben.-def. Einstellungen setzen
         * @param array $usrmeta
         */
        public function setUserMeta(array $usrmeta) {
            $this->usrmeta = json_encode($usrmeta);
        }

        /**
         * Kurze Authoren-Beschreibung setzen
         * @param string $usrinfo
         * @since FPCM 3.6
         */
        public function setUsrinfo($usrinfo) {
            $this->usrinfo = $usrinfo;
        }
        
        /**
         * Speichert einen neuen Benutzer in der Datenbank
         * @return boolean
         */
        public function save() {

            $this->removeBannedTexts();

            if (!$this->username) {
                trigger_error('Username cannot be blank.');
                return false;
            }
            
            if ($this->authorExists()) return self::AUTHOR_ERROR_EXISTS;
            if (!$this->checkPasswordSecure() && !$this->passwordSecCheckDisabled()) return self::AUTHOR_ERROR_PASSWORDINSECURE;
            
            $this->email = filter_var($this->email, FILTER_VALIDATE_EMAIL);
            if (!$this->email) return self::AUTHOR_ERROR_NOEMAIL;
            
            $this->salt     = \fpcm\classes\security::createSalt();
            $this->passwd   = \fpcm\classes\security::createPasswordHash($this->passwd, $this->salt);
            $this->disabled = 0;
            
            $params = $this->getPreparedSaveParams();
            $this->events->runEvent('authorSave', $params);

            $return = false;
            $insertRes = $this->dbcon->insert($this->table, implode(',', array_keys($params)), implode(', ', $this->getPreparedValueParams()), array_values($params));
            if ($insertRes) {
                $return = true;
            }

            $this->id = $this->dbcon->getLastInsertId();
            $this->cache->cleanup();

            return $return;            
        }

        /**
         * Aktualisiert einen Benutzer in der Datenbank
         * @return boolean
         */
        public function update() {

            $this->removeBannedTexts();

            if (!$this->passwordSecCheckDisabled()) {
                if (!$this->checkPasswordSecure()) return self::AUTHOR_ERROR_PASSWORDINSECURE;                
                $this->passwd = \fpcm\classes\security::createPasswordHash($this->passwd, $this->salt);
            }
            
            $this->email = filter_var($this->email, FILTER_VALIDATE_EMAIL);
            if (!$this->email) return self::AUTHOR_ERROR_NOEMAIL;
            
            $params     = $this->getPreparedSaveParams();
            if (empty($this->passwd)) {
                unset($params['passwd']);
            }
            
            $fields     = array_keys($params);
            
            $params[]   = $this->getId();
            $this->events->runEvent('authorUpdate', $params);

            $return = false;
            if ($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
                $return = true;
            }            
            
            $this->cache->cleanup();
            $this->init();
            
            return $return;
        }
        
        /**
         * Löscht einen Benutzer in der Datenbank
         * @return bool
         */
        public function delete() {
            return parent::delete();
        }
        
        /**
         * Deaktiviert einen Benutzer
         * @return bool
         */
        public function disable() {
            $this->disabled = 1;
            $this->disablePasswordSecCheck();
            return $this->update();
        }
        
        /**
         * Aktiviert einen Benutzer
         * @return bool
         */        
        public function enable() {
            $this->disabled = 0;
            $this->disablePasswordSecCheck();
            return $this->update();
        }
        
        /**
         * Passwort-Check ein Anlegen/Aktualisieren deaktivieren
         */
        public function disablePasswordSecCheck() {
            $this->nopasscheck = true;
        }
        
        /**
         * Passwort-Check ein Anlegen/Aktualisieren deaktivieren
         */
        public function passwordSecCheckDisabled() {
            return $this->nopasscheck;
        }
        
        /**
         * Passwort für Benutzer zurücksetzen
         * @param bool $resetOnly (@since FPCM3.4)
         * @return boolean
         */
        public function resetPassword($resetOnly = false) {

            $this->disablePasswordSecCheck();
            
            $password       = substr(str_shuffle(ucfirst(sha1($this->username).uniqid())), 0, rand(10,16));

            $this->salt     = \fpcm\classes\security::createSalt($this->displayname.'-'.$this->username.'-'.$this->id);
            $this->passwd   = \fpcm\classes\security::createPasswordHash($password, $this->salt);
            
            if ($resetOnly) {
                return array(
                    'updateOk' => $this->update(),
                    'password' => $password
                );
            }

            $text = $this->language->translate('PASSWORD_RESET_TEXT', array('{{newpass}}' => $password));
            $email = new \fpcm\classes\email($this->email, $this->language->translate('PASSWORD_RESET_SUBJECT'), $text);
            $email->setHtml(true);

            if ($email->submit()) {
                return $this->update();
            }

            return false;
            
        }        
        /**
         * Füllt Objekt mit Daten aus Datenbank-Result
         * @param object $object
         * @return boolean
         */
        public function createFromDbObject($object) {

            $res = parent::createFromDbObject($object);
            $this->groupname = $this->language->translate($this->groupname);
            $this->image     = preg_replace('/[^a-z0-9_\-\w]/', '', strtolower($this->username));
            
            return $res;

        }

        /**
         * Prüft, ob Benutzer existiert
         * @return bool
         */
        private function authorExists() {
            $result = $this->dbcon->count($this->table,"id", "username ".$this->dbcon->dbLike()." ? OR displayname ".$this->dbcon->dbLike()." ?", array($this->username, $this->displayname));
            return ($result > 0) ? true : false;
        }
        
        /**
         * Prüft, ob Passwort den minimalen Anforderungen entspricht
         * @return boolean
         */
        private function checkPasswordSecure() {
            return (preg_match(\fpcm\classes\security::regexPasswordCkeck, $this->passwd)) ? true : false;
        }
        
        /**
         * Führt Ersetzung von gesperrten Texten in Benutzer-Daten durch
         * @return boolean
         * @since FPCM 3.2.0
         */
        private function removeBannedTexts() {

            $this->username     = $this->wordbanList->replaceItems($this->username);
            $this->displayname   = $this->wordbanList->replaceItems($this->displayname);
            $this->email = $this->wordbanList->replaceItems($this->email);
            
            return true;
        }

        /**
         * Inittiert Objekt mit Daten aus der Datenbank, sofern ID vergeben wurde
         */
        protected function init() {
            
            $item   = $this->dbcon->getTablePrefixed($this->table).'.*, ';
            $item  .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll).'.leveltitle AS groupname';

            $where  = $this->dbcon->getTablePrefixed($this->table).'.roll = ';
            $where .= $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll).'.id AND ';
            $where .= $this->dbcon->getTablePrefixed($this->table).'.id = ?';
            $data   = $this->dbcon->fetch($this->dbcon->select(array($this->table, \fpcm\classes\database::tableRoll), $item, $where, array($this->id)));

            if (!$data) {
                trigger_error('Failed to load data for object of type "'.get_class($this).'" with given id '.$this->id.'!');
                return false;
            }
            
            $this->objExists = true;
            $this->createFromDbObject($data);

        }

        /**
         * Author-Bild laden
         * @param \fpcm\model\users\author $author
         * @param bool $asUrl
         * @return string
         * @since FPCM 3.6
         */
        public static function getAuthorImageDataOrPath($author, $asUrl = true) {

            if (!$author instanceof author) {
                return '';
            }
            
            $cache = new \fpcm\classes\cache('authorImages', 'system');
            $data  = $cache->read();
            
            if (!is_array($data)) {
                $data = [];
            }
            
            $usernameHash = hash(\fpcm\classes\security::defaultHashAlgo, $author->getUsername());

            if (!$cache->isExpired() && isset($data[$usernameHash])) {
                return $asUrl ? $data[$usernameHash]['url'] : $data[$usernameHash]['data'];
            }

            foreach (\fpcm\model\files\image::$allowedExts as $ext) {

                $img = new \fpcm\model\files\authorImage($author->getImage().'.'.$ext);
                if (!$img->exists() || $img->getFilesize() > FPCM_AUTHOR_IMAGE_MAX_SIZE) {
                    unset($img);
                    continue;
                }

                $img->loadContent();
                $data[$usernameHash] = [
                    'url'  => $img->getImageUrl(),
                    'data' => $img->getContent() ? 'data:'.$img->getMimetype().';base64,'.base64_encode($img->getContent()) : ''
                ];
                
                break;
            }
            
            $cache->write($data, FPCM_LANGCACHE_TIMEOUT);
            if (!isset($data[$usernameHash])) {
                return '';
            }

            return $asUrl ? $data[$usernameHash]['url'] : $data[$usernameHash]['data'];
        }
        
    }
