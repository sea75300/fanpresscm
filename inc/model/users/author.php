<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\users;

/**
 * Benutzer Objekt
 * 
 * @package fpcm\model\user
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class author extends \fpcm\model\abstracts\dataset {

    use \fpcm\model\traits\autoTable;
    
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
     * E-Mail-Adresse
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
     * @var usrmeta
     */
    protected $usrmeta = '';

    /**
     * Author-Beschreibung
     * @var string
     * @since 3.6
     */
    protected $usrinfo = '';

    /**
     * Two factor auth token
     * @var string
     * @since 4
     */
    protected $authtoken = '';

    /**
     * Time of last change
     * @var int
     */
    protected $changetime = 0;

    /**
     * User of last change
     * @var int
     */
    protected $changeuser = 0;

    /**
     * Übersetzter Gruppenname
     * @var string
     * @since 3.4
     */
    protected $groupname;

    /**
     * Authoren-Bild
     * @var string
     * @since 3.6
     */
    protected $image = '';

    /**
     * Edit action string
     * @var string
     */
    protected $editAction = 'users/edit&id=';

    /**
     * Wortsperren-Liste
     * @var \fpcm\model\wordban\items
     * @since 3.2.0
     */
    protected $wordbanList;

    /**
     * Eigenschaften, welche beim Speichern in DB nicht von getPreparedSaveParams() zurückgegeben werden sollen
     * @var array
     */
    protected $dbExcludes = ['groupname', 'image'];

    /**
     * Konstruktor
     * @param int $id
     */
    public function __construct($id = null)
    {
        $this->wordbanList = new \fpcm\model\wordban\items();

        if ($id !== null) {
            $this->cacheName = 'author' . $id;
        }

        parent::__construct($id);        
    }

    /**
     * Liefert anzeigten Name zurück
     * @return string
     */
    public function getDisplayname()
    {
        return $this->displayname;
    }

    /**
     * Liefert E-Mail-Adresse zurück
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Liefert Datum der Anmeldung zurück
     * @return int
     */
    public function getRegistertime()
    {
        return $this->registertime;
    }

    /**
     * Liefert Benutzername zurück
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Liefert Passwort-Hash zurück
     * @return string
     */
    public function getPasswd()
    {
        return $this->passwd;
    }

    /**
     * Liefert Rollen-ID zurück
     * @return string
     */
    public function getRoll()
    {
        return (int) $this->roll;
    }

    /**
     * Rollen-ID setzen
     * @param int $roll
     */
    public function setRoll($roll)
    {
        $this->roll = (int) $roll;
    }

    /**
     * Status ob Benutzer deaktiviert ist auslesen
     * @return bool
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * Returns change time
     * @return int
     */
    public function getChangeTime() : int
    {
        return $this->changetime;
    }

    /**
     * Return change user
     * @return int
     */
    public function getChangeUser() : int
    {
        return $this->changeuser;
    }
    
    /**
     * Übersetzter Gruppenname
     * @return string
     * @since 3.4
     */
    function getGroupname()
    {
        return $this->groupname;
    }

    /**
     * Kurze Authoren-Beschreibung setzen
     * @since 3.6
     */
    public function getUsrinfo()
    {
        return $this->usrinfo;
    }

    /**
     * Author-Bild zurückliefern
     * @since 3.6
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Deaktiviert-Status setzen
     * @param bool $disabled
     */
    public function setDisabled($disabled)
    {
        $this->disabled = (int) $disabled;
    }

    /**
     * Returns user settings
     * @param string $valueName
     * @return usrmeta
     */
    public function getUserMeta($valueName = null)
    {
        if ($valueName === null) {
            return $this->usrmeta;
        }

        return $this->usrmeta->{$valueName};
    }

    /**
     * Fetch auth token
     * @return string
     */
    public function getAuthtoken()
    {
        return $this->authtoken;
    }
    
    /**
     * ist Benutzer ein Administrator
     * @return bool
     */
    public function isAdmin()
    {
        return $this->roll == 1 ? true : false;
    }

    /**
     * Angezeigten Name setzen
     * @param string $displayname
     */
    public function setDisplayName($displayname)
    {
        $this->displayname = $displayname;
    }

    /**
     * E-Mail-Adresse setzen
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Anmelde-Datum setzen
     * @param string $registertime
     */
    public function setRegistertime($registertime)
    {
        $this->registertime = $registertime;
    }

    /**
     * Benutzername setzen
     * @param string $username
     */
    public function setUserName($username)
    {
        $this->username = $username;
    }

    /**
     * Passwort-Hash setzen
     * @param string $passwd
     */
    public function setPassword($passwd)
    {
        $this->passwd = $passwd;
    }

    /**
     * ben.-def. Einstellungen setzen
     * @param array $usrmeta
     */
    public function setUserMeta($usrmeta)
    {
        $this->usrmeta = is_array($usrmeta) ? new usrmeta($usrmeta) : $usrmeta;
    }

    /**
     * Kurze Authoren-Beschreibung setzen
     * @param string $usrinfo
     * @since 3.6
     */
    public function setUsrinfo($usrinfo)
    {
        $this->usrinfo = $usrinfo;
    }

    /**
     * Returns time of last change
     * @param int $changetime
     * @return $this
     */
    public function setChangeTime(int $changetime)
    {
        $this->changetime = $changetime;
        return $this;
    }

    /**
     * Returns user of last change
     * @param int $changeuser
     * @return $this
     */
    public function setChangeUser(int $changeuser)
    {
        $this->changeuser = $changeuser;
        return $this;
    }

    /**
     * Set auth token data
     * @param string $authtoken
     */
    public function setAuthtoken($authtoken)
    {
        $this->authtoken = $authtoken;
    }
        
    /**
     * Speichert einen neuen Benutzer in der Datenbank
     * @return bool
     */
    public function save()
    {
        $this->removeBannedTexts();

        if (!$this->username) {
            trigger_error('Username cannot be blank.');
            return false;
        }

        if ($this->authorExists()) {
            return self::AUTHOR_ERROR_EXISTS;
        }

        if (!$this->checkPasswordSecure() && !$this->passwordSecCheckDisabled()) {
            return self::AUTHOR_ERROR_PASSWORDINSECURE;
        }

        $this->email = filter_var($this->email, FILTER_VALIDATE_EMAIL);
        if (!$this->email) {
            return self::AUTHOR_ERROR_NOEMAIL;
        }

        $this->passwd = \fpcm\classes\security::createUserPasswordHash($this->passwd);
        $this->disabled = 0;

        return parent::save() === false ? false : true;
    }

    /**
     * Aktualisiert einen Benutzer in der Datenbank
     * @return bool
     */
    public function update()
    {
        $this->removeBannedTexts();
        if (!$this->passwordSecCheckDisabled() && $this->passwd !== null) {
            if (!$this->checkPasswordSecure()) {
                return self::AUTHOR_ERROR_PASSWORDINSECURE;
            }

            $this->passwd = \fpcm\classes\security::createUserPasswordHash($this->passwd);
        }

        $this->email = filter_var($this->email, FILTER_VALIDATE_EMAIL);
        if (!$this->email) {
            return self::AUTHOR_ERROR_NOEMAIL;
        }

        $params = $this->getPreparedSaveParams();
        if (empty($this->passwd)) {
            unset($params['passwd']);
        }

        $fields = array_keys($params);
        $params[] = $this->getId();

        $this->events->trigger($this->getEventName('update'), $params)->getData();

        $return = false;
        if ($this->dbcon->update($this->table, $fields, array_values($params), 'id = ?')) {
            $return = true;
        }

        $this->cache->cleanup();
        $this->init();

        return $return;
    }

    /**
     * Deaktiviert einen Benutzer
     * @return bool
     */
    public function disable()
    {
        $this->disabled = 1;
        $this->disablePasswordSecCheck();
        return $this->update();
    }

    /**
     * Aktiviert einen Benutzer
     * @return bool
     */
    public function enable()
    {
        $this->disabled = 0;
        $this->disablePasswordSecCheck();
        return $this->update();
    }

    /**
     * Passwort-Check ein Anlegen/Aktualisieren deaktivieren
     */
    public function disablePasswordSecCheck()
    {
        $this->nopasscheck = true;
    }

    /**
     * Passwort-Check ein Anlegen/Aktualisieren deaktivieren
     */
    public function passwordSecCheckDisabled()
    {
        return $this->nopasscheck;
    }

    /**
     * Passwort für Benutzer zurücksetzen
     * @param bool $resetOnly (@since FPCM3.4)
     * @return bool
     */
    public function resetPassword($resetOnly = false)
    {
        $this->disablePasswordSecCheck();

        $password = substr(str_shuffle(ucfirst(sha1($this->username) . uniqid())), 0, rand(10, 16));
        $this->passwd = \fpcm\classes\security::createUserPasswordHash($password);

        if ($resetOnly) {
            return [
                'updateOk' => $this->update(),
                'password' => $password
            ];
        }

        $email = new \fpcm\classes\email(
            $this->email,
            $this->language->translate('PASSWORD_RESET_SUBJECT'),
            $this->language->translate('PASSWORD_RESET_TEXT', ['{{newpass}}' => $password])
        );

        $email->setHtml(true);

        if ($email->submit()) {
            return $this->update();
        }

        trigger_error('Unable to reset user password, failed to submit email to '.$this->email);
        return false;
    }

    /**
     * Reset Profile settings
     * @return bool
     * @since 4.1
     */
    public function resetProfileSettings()
    {
        $this->getUserMeta()->resetSettings();
        $this->disablePasswordSecCheck();
        $this->setPassword(null);
        return $this->update();
    }

    /**
     * Reset Dashboard container settings
     * @return bool
     * @since 4.1
     */
    public function resetDashboard()
    {
        $meta = $this->getUserMeta();
        $meta->dashboardpos = [];
        $this->setUserMeta($meta);
        $this->disablePasswordSecCheck();
        $this->setPassword(null);
        return $this->update();
    }

    /**
     * Füllt Objekt mit Daten aus Datenbank-Result
     * @param object $object
     * @return bool
     */
    public function createFromDbObject($object)
    {
        $res = parent::createFromDbObject($object);
        $this->groupname = $this->language->translate($this->groupname);
        $this->image = preg_replace('/[^a-z0-9_\-\w]/', '', strtolower($this->username));
        if (!$this->usrmeta instanceof usrmeta) {
            $this->usrmeta = new usrmeta($this->usrmeta);
        }

        return $res;
    }

    /**
     * Prüft, ob Benutzer existiert
     * @return bool
     */
    private function authorExists()
    {
        $result = $this->dbcon->count($this->table, "id", "username " . $this->dbcon->dbLike() . " ? OR displayname " . $this->dbcon->dbLike() . " ?", array($this->username, $this->displayname));
        return ($result > 0) ? true : false;
    }

    /**
     * Prüft, ob Passwort den minimalen Anforderungen entspricht
     * @return bool
     */
    private function checkPasswordSecure()
    {
        return (preg_match(\fpcm\classes\security::regexPasswordCkeck, $this->passwd)) ? true : false;
    }

    /**
     * Führt Ersetzung von gesperrten Texten in Benutzer-Daten durch
     * @return bool
     * @since 3.2.0
     */
    protected function removeBannedTexts()
    {
        $this->username = $this->wordbanList->replaceItems($this->username);
        $this->displayname = $this->wordbanList->replaceItems($this->displayname);
        $this->email = $this->wordbanList->replaceItems($this->email);

        return true;
    }

    /**
     * Inittiert Objekt mit Daten aus der Datenbank, sofern ID vergeben wurde
     */
    public function init()
    {       
        $prefixUserTab = $this->dbcon->getTablePrefixed($this->table);
        $prefixRollTab = $this->dbcon->getTablePrefixed(\fpcm\classes\database::tableRoll);
        
        $data = $this->dbcon->selectFetch(
            (new \fpcm\model\dbal\selectParams())
                ->setTable($this->table)
                ->setItem($prefixUserTab . '.*, ' . $prefixRollTab . '.leveltitle AS groupname')
                ->setJoin('LEFT JOIN '.$prefixRollTab . ' ON '.$prefixUserTab . '.roll = '.$prefixRollTab . '.id')
                ->setWhere($prefixUserTab.'.id = ?')
                ->setParams([$this->id])
        );

        if (!$data) {
            trigger_error('Failed to load data for object of type "' . static::class . '" with given id ' . $this->id . '!');
            return false;
        }

        $this->objExists = true;
        $this->createFromDbObject($data);
    }

    /**
     * Write content to file option for current user
     * @param string $opt
     * @param mixed $data
     * @return bool
     * @deprecated 5.1.0-a1
     */
    final public function writeOption($opt, $data)
    {
        trigger_error(sprintf('%s is deprecated as of FPCM 5.1.0-a1. Use %s object instead.', __METHOD__, '\fpcm\model\files\userFileOption'), E_USER_DEPRECATED);
        return $this->getFileOptionObject($opt)->write($data);
    }

    /**
     * Read content from file option for current user
     * @param string $opt
     * @return mixed
     * @deprecated 5.1.0-a1
     */
    final public function readOption($opt)
    {
        trigger_error(sprintf('%s is deprecated as of FPCM 5.1.0-a1. Use %s object instead.', __METHOD__, '\fpcm\model\files\userFileOption'), E_USER_DEPRECATED);
        return $this->getFileOptionObject($opt)->read();
    }

    /**
     * Removes file option for current user
     * @param string $opt
     * @return mixed
     * @deprecated 5.1.0-a1
     */
    final public function removeOption($opt)
    {
        trigger_error(sprintf('%s is deprecated as of FPCM 5.1.0-a1. Use %s object instead.', __METHOD__, '\fpcm\model\files\userFileOption'), E_USER_DEPRECATED);
        return $this->getFileOptionObject($opt)->remove();
    }

    /**
     * Generates file option object for current user
     * @param string $opt
     * @return string
     * @deprecated 5.1.0-a1
     */
    private function getFileOptionObject($opt)
    {
        return new \fpcm\model\files\fileOption('user'.$this->getId().'/fopt_'.$opt);
    }

    /**
     * Author-Bild laden
     * @param \fpcm\model\users\author $author
     * @param bool $asUrl
     * @return string
     * @since 3.6
     */
    public static function getAuthorImageDataOrPath($author, $asUrl = true)
    {
        if (!$author instanceof author) {
            return '';
        }

        $cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');

        $cacheName = 'system/author' . $author->getImage() . '_image';
        $data = $cache->read($cacheName);

        if (!is_array($data)) {
            $data = [];
        }

        $usernameHash = \fpcm\classes\tools::getHash($author->getUsername());

        if (!$cache->isExpired($cacheName) && isset($data[$usernameHash])) {
            return $asUrl ? $data[$usernameHash]['url'] : $data[$usernameHash]['data'];
        }

        foreach (\fpcm\model\files\image::$allowedExts as $ext) {

            $img = new \fpcm\model\files\authorImage($author->getImage() . '.' . $ext);
            if (!$img->exists() || $img->getFilesize() > FPCM_AUTHOR_IMAGE_MAX_SIZE) {
                unset($img);
                continue;
            }

            $img->loadContent();
            $data[$usernameHash] = [
                'url' => $img->getImageUrl(),
                'data' => $img->getContent() ? 'data:' . $img->getMimetype() . ';base64,' . base64_encode($img->getContent()) : ''
            ];

            break;
        }

        $cache->write($cacheName, $data, FPCM_LANGCACHE_TIMEOUT);
        if (!isset($data[$usernameHash])) {
            return '';
        }

        return $asUrl ? $data[$usernameHash]['url'] : $data[$usernameHash]['data'];
    }
    
    /**
     * Override config object with global settings
     * @return void
     */
    public function overrideConfig()
    {
        $this->config = new \fpcm\model\system\config();
    }

    /**
     * Returns event base string
     * @see \fpcm\model\abstracts\dataset::getEventModule
     * @return string
     * @since 4.1
     */
    protected function getEventModule(): string
    {
        return 'user';
    }

    /**
     * Is triggered after successful database insert
     * @see \fpcm\model\abstracts\dataset::afterSaveInternal
     * @return bool
     * @since 4.1
     */
    protected function afterSaveInternal(): bool
    {
        $this->cache->cleanup();
        return true;
    }

}
