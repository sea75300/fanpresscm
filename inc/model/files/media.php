<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Audio/Video object wrapper
 *
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class media
extends \fpcm\model\abstracts\file
implements \fpcm\model\interfaces\validateFileType,
           \fpcm\model\interfaces\isCopyable {

    use \fpcm\model\traits\assignThisProperties;

    /**
     * Erlaubte Dateitypen
     * @var array
     */
    public static $allowedTypes = ['video/mp4', 'video/ogg', 'video/webm', 'audio/mpeg', 'audio/wav'];

    /**
     * Erlaubte Dateiendungen
     * @var array
     */
    public static $allowedExts = ['mp4', 'ogg', 'webm', 'mp3', 'wav'];

    /**
     * ID von Datei-Eintrag in DB
     * @var int
     */
    protected $id;

    /**
     * Benutzer-ID des Uploaders
     * @var int
     */
    protected $userid;

    /**
     * Zeitpunkt des Uploads
     * @var int
     */
    protected $filetime;

    /**
     * MIME-Dateityp-Info
     * @var string
     */
    protected $mimetype;

    /**
     * Flag if file is in file index
     * @var bool
     * @since 4.5
     */
    protected $isIndexed = false;

    /**
     * Felder die in Datenbank gespeichert werden können
     * @var array
     */
    protected $dbParams = [
        'userid', 'filename', 'filetime', 'filesize', 'mimetype', 'filehash'
    ];

    /**
     * Constructor
     * @param string $filename file name including sub folder
     * @param bool $initDB force object init from database
     */
    public function __construct(string $filename = '', bool $initDB = true)
    {
        $this->table = \fpcm\classes\database::tableFiles;
        $filename = $this->splitFilename($filename);
        parent::__construct($filename);

        $this->filename = $filename;

        $this->init($initDB);
    }

    /**
     * Returns base path for file
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MEDIA, $filename);
    }

    /**
     * Datensatz-ID
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Bild-Url ausgeben
     * @return string
     */
    public function getFileUrl()
    {
        return \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_MEDIA, $this->filename);
    }

    /**
     * Uploader-ID ausgeben
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Upload-Zeit ausgeben
     * @return int
     */
    public function getFiletime()
    {
        return $this->filetime;
    }

    /**
     * MIME-Type ausgeben
     * @return int
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * Datensatz-ID setzen
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Benutzer-ID setzen
     * @param int $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * Upload-Zeit setzen
     * @param int $filetime
     */
    public function setFiletime($filetime)
    {
        $this->filetime = $filetime;
    }

    /**
     * Speichert einen neuen Datei-Eintrag in der Datenbank
     * @return bool
     */
    public function save()
    {
        if ($this->exists(true) || !$this->isValidDataFolder($this->filepath, \fpcm\classes\dirs::DATA_MEDIA)) {
            return false;
        }

        $saveValues = $this->getSaveValues();
        $saveValues['filehash'] = $this->getFileHash();
        $saveValues['filesize'] = (int) $saveValues['filesize'];
        $saveValues['filetime'] = (int) $saveValues['filetime'];
        $saveValues['userid'] = (int) $saveValues['userid'];

        /*$ev = $this->events->trigger('image\save', $saveValues);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event image\save failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return [];
        }*/

        return $this->dbcon->insert($this->table, $ev->getData());
    }

    /**
     * Aktualisiert einen Datei-Eintrag in der Datenbank
     * @return bool
     */
    public function update()
    {
        if (!$this->exists(true) || !$this->isValidDataFolder()) {
            return false;
        }

        $saveValues = $this->getSaveValues();
        $saveValues['filehash'] = $this->getFileHash();
        $saveValues['filesize'] = (int) $saveValues['filesize'];
        $saveValues['filetime'] = (int) $saveValues['filetime'];
        $saveValues['userid'] = (int) $saveValues['userid'];

        $saveValues[] = $this->filename;

        /*$ev = $this->events->trigger('image\update', $saveValues);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event image\save failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }*/

        $saveValues = $ev->getData();
        return $this->dbcon->update($this->table, $this->dbParams, array_values($saveValues), "filename = ?");
    }

    /**
     * Löscht Datei-Eintrag in Datenbank und Datei in Dateisystem
     * @return bool
     */
    public function delete()
    {
        if (!$this->isValidDataFolder('', \fpcm\classes\dirs::DATA_MEDIA)) {
            return false;
        }

        parent::delete();

        \fpcm\model\reminders\reminders::getInstance()->removeByObject(image::class, $this->id);

        return $this->dbcon->delete($this->table, 'filename = :filename', [':filename' => $this->filename]);
    }

    /**
     * Benennt eine Datei um
     * @param string $newname
     * @param int $userId
     * @return bool
     */
    public function rename($newname, $userId = false)
    {
        $newname = $this->splitFilename($newname);

        $oldname = $this->filename;
        if (strpos($oldname, '/') !== false) {
            $newname = dirname($oldname).DIRECTORY_SEPARATOR.$newname;
        }

        $newnameExt = $newname.'.'.$this->getExtension();
        if (!$this->isValidDataFolder($this->getFilepath(), \fpcm\classes\dirs::DATA_MEDIA) || !parent::rename($newnameExt)) {
            return false;
        }

        $this->filetime = time();
        $this->userid = $userId;
        $params = array_merge(array_values($this->getSaveValues()), [$oldname]);
        $res = $this->dbcon->update($this->table, $this->dbParams, $params, "filename = ?");

        if (!$res) {
            trigger_error('Unable to update database file info for ' . $oldname);
            return false;
        }

        $this->filename = $oldname;
        return true;
    }

    /**
     * Check if image exists
     * @param type $dbOnly
     * @return bool
     */
    public function exists($dbOnly = false)
    {
        if (!$this->filename) {
            return false;
        }

        $fileExists = $this->existsFolder();
        if ($dbOnly) {
            return (bool) $this->isIndexed;
        }

        return $fileExists && $this->isIndexed ? true : false;
    }

    /**
     * Prüft, ob Bild nur in Dateisystem existiert
     * @return bool
     * @since 3.1.2
     */
    public function existsFolder()
    {
        if (!$this->isValidDataFolder($this->filepath)) {
            return false;
        }

        return parent::exists();
    }

    /**
     * Add upload sub folder string
     * @return bool
     * @since 5.0.0-a3
     */
    public function addUploadFolder() : bool
    {
        $this->fullpath = ops::getUploadPath($this->filename, true);

        if (!file_exists(dirname($this->fullpath))) {
            mkdir(dirname($this->fullpath));
        }

        return true;
    }

    /**
     * Gibt Speicher-Values zurück
     * @return array
     */
    protected function getSaveValues()
    {
        $values = [];
        foreach ($this->dbParams as $key) {
            $values[$key] = $this->$key ?? '';
        }

        return $values;
    }

    /**
     * initialisiert Bild-Objekt
     * @param bool $initDB
     * @return bool
     */
    protected function init($initDB)
    {
        $this->isIndexed = false;

        if ($initDB) {

            $obj = (new \fpcm\model\dbal\selectParams($this->table))
                    ->setWhere('filename = :filename')
                    ->setParams([
                        ':filename' => $this->filename
                    ]);

            $dbData = $this->dbcon->selectFetch($obj);
            if ($dbData) {
                $this->assignThis($dbData);
                $this->isIndexed = true;
            }
        }

        if (!parent::exists()) {
            return true;
        }

        $this->extension = self::retrieveFileExtension($this->fullpath);

        if (!$this->mimetype) {
            $this->mimetype = self::retrieveRealType($this->fullpath);
        }

        if (!$this->filesize) {
            $this->filesize = filesize($this->fullpath);
        }

        return true;
    }

    /**
     * Füllt Objekt mit Daten aus Datenbank-Result
     * @param object $object
     * @return bool
     * @since 3.1.2
     */
    public function createFromDbObject($object)
    {
        if (!is_object($object)) {
            return false;
        }

        $keys = array_keys($this->getPreparedSaveParams());
        $keys[] = 'id';

        foreach ($keys as $key) {
            if (!isset($object->$key)) {
                continue;
            }

            $this->$key = $object->$key;
        }

        $this->fullpath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MEDIA, $this->filename);
        $this->filepath = dirname($this->fullpath);

        $this->init(false);

        return true;
    }

    /**
     * Bereitet Eigenschaften des Objects zum Speichern ind er Datenbank vor und entfernt nicht speicherbare Eigenschaften
     * @return array
     * @since 3.1.2
     */
    protected function getPreparedSaveParams()
    {
        $params = get_object_vars($this);
        unset(
            $params['cache'], $params['config'], $params['dbcon'],
            $params['events'], $params['id'], $params['nodata'],
            $params['system'], $params['table'], $params['dbExcludes'],
            $params['language'], $params['editAction'], $params['objExists'],
            $params['cacheName']
        );

        if ($this->nodata) {
            unset($params['data']);
        }

        return $params;
    }

    /**
     * Splits filename with possible folder
     * @param string $filename
     * @return string
     * @since 4.1
     */
    protected function splitFilename(string $filename) : string
    {
        $filename = explode('/', $filename, 2);

        $fn = $filename[1] ?? $filename[0];
        $this->escapeFileName($fn);
        if (isset($filename[1])) {
            $filename[1] = $fn;
        }
        else {
            $filename[0] = $fn;
        }

        return implode('/', $filename);
    }

    /**
     * Return properties array
     * @param string $userName
     * @return array
     * @since 5.0.0-a1
     */
    public function getPropertiesArray(string $userName) : array
    {
        return [
            'filename' => $this->getFilename(),
            'filetime' => (string) new \fpcm\view\helper\dateText($this->getFiletime()),
            'fileuser' => $userName,
            'filesize' => \fpcm\classes\tools::calcSize($this->getFilesize()),
            'filehash' => $this->getFileHash(),
            'filemime' => $this->getMimetype()
        ];
    }

    /**
     *
     * @return int
     */
    public function copy(): int
    {
        $cn = self::class;

        if (!$this->existsFolder()) {
            return 0;
        }

        /* @var $copy image */
        $copy = new $cn( $this->language->translate('GLOBAL_COPY_OF_FILE', [basename($this->filename)], true), false );
        $copy->addUploadFolder();

        $subFolderbase = basename(dirname($copy->getFullpath()));
        if ($this->config->file_subfolders && !str_starts_with($copy->getFilename(), $subFolderbase)) {
            $copy->setFilename(basename(dirname($copy->getFullpath())) . DIRECTORY_SEPARATOR . $copy->getFilename());
        }

        $copy->setUserid(\fpcm\model\system\session::getInstance()->getUserId());
        $copy->setFiletime(time());

        if ($copy->existsFolder()) {
            return 0;
        }

        if (!copy($this->fullpath, $copy->getFullpath())) {
            return 0;
        }

        return $copy->save() ?: 0;
    }

    /**
     * Check if file extension and file type is valid
     * @param string $ext
     * @param string $type
     * @return bool
     * @since 4.5
     * @see \fpcm\model\interfaces\validateFileType
     */
    public static function isValidType(string $ext, string $type, array $map = []) : bool
    {
        $assigned = array_combine(self::$allowedExts, self::$allowedTypes)[$ext] ?? null;
        if ($assigned === null) {
            return false;
        }

        return in_array($type, self::$allowedTypes) && in_array($ext, self::$allowedExts) && $assigned === $type;
    }

}
