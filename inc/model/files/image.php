<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Image file objekt
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class image extends \fpcm\model\abstracts\file implements \fpcm\model\interfaces\validateFileType {

    /**
     * Erlaubte Dateitypen
     * @var array
     */
    public static $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];

    /**
     * Erlaubte Dateiendungen
     * @var array
     */
    public static $allowedExts = ['jpeg', 'jpg', 'png', 'gif'];

    /**
     * ID von Datei-Eintrag in DB
     * @var int
     */
    protected $id;

    /**
     * Bild-Breite
     * @var int
     */
    protected $width;

    /**
     * Bild-Höhe
     * @var int
     */
    protected $height;

    /**
     * String in der Form width="" height=""
     * @var string
     */
    protected $whstring;

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
     * Alternate text
     * @var string
     */
    protected $alttext;

    /**
     * MIME-Dateityp-Info
     * @var string
     */
    protected $mimetype;

    /**
     * Exif/ IPCT data
     * @var string
     */
    protected $iptcStr;

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
    protected $dbParams = ['userid', 'filename', 'filetime', 'filesize', 'alttext'];
    
    /**
     * Konstruktor
     * @param string $filename file name including sub path
     * @param bool $initDB Datenbank-Eintrag initialisieren
     * @param bool $forceInit Initialisierung erzwingen
     */
    public function __construct($filename = '', $initDB = true)
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
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, $filename);
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
    public function getImageUrl()
    {
        return \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_UPLOADS, $this->filename);
    }

    /**
     * Thumbnail-Url ausgeben
     * @return string
     */
    public function getThumbnailUrl()
    {
        return \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_UPLOADS, $this->getThumbnail());
    }

    /**
     * Dateimanager-Thumbnail ausgeben
     * @return string
     */
    public function getFileManagerThumbnailUrl()
    {
        return \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_FMTMP, $this->filename);
    }

    /**
     * Thumbnail-Pfad ausgeben
     * @return string
     */
    public function getThumbnail()
    {
        $fnArr = explode('/', $this->filename, 2);
        if (count($fnArr) == 2) {
            return $fnArr[0].'/thumbs/'.$fnArr[1];
        }
                
        return 'thumbs/' . $this->filename;
    }

    /**
     * kompletten Thumbnail-Pfad ausgeben
     * @return string
     */
    public function getThumbnailFull()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, $this->getThumbnail());
    }

    /**
     * Dateimanager-Thumbnail-Pfad ausgeben
     * @return string
     */
    public function getFileManagerThumbnail()
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_FMTMP, $this->filename);
    }

    /**
     * Checks if file amanager thumbnail exists
     * @return bool
     * @since 4.3
     */
    public function hasFileManageThumbnail() : bool
    {
        return file_exists($this->getFileManagerThumbnail());
    }

    /**
     * Breite ausgeben
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Höhe ausgeben
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * String width="" height="" auslesen
     * @return string
     */
    public function getWhstring()
    {
        return $this->whstring;
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
     * Get alternate text
     * @return string
     * @since 4.5
     */
    public function getAltText(): ?string
    {
        return $this->alttext;
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
     * Returns IPTC credit string
     * @return string
     */
    public function getIptcStr() {
        return $this->iptcStr;
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
     * Set alternate text
     * @param string $alttext
     * #@since 4.5
     */
    public function setAltText(string $alttext)
    {
        $this->alttext = $alttext;
    }

    /**
     * Speichert einen neuen Datei-Eintrag in der Datenbank
     * @return bool
     */
    public function save()
    {
        if ($this->exists(true) || !$this->isValidDataFolder($this->filepath, \fpcm\classes\dirs::DATA_UPLOADS)) {
            return false;
        }

        $saveValues = $this->getSaveValues();
        $saveValues['filesize'] = (int) $saveValues['filesize'];

        return $this->dbcon->insert($this->table, $this->events->trigger('image\save', $saveValues)->getData());
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
        $saveValues['filesize'] = (int) $saveValues['filesize'];

        $saveValues[] = $this->filename;
        $saveValues = $this->events->trigger('image\update', $saveValues)->getData();

        return $this->dbcon->update($this->table, $this->dbParams, array_values($saveValues), "filename = ?");
    }

    /**
     * Löscht Datei-Eintrag in Datenbank und Datei in Dateisystem
     * @return bool
     */
    public function delete()
    {
        if (!$this->isValidDataFolder('', \fpcm\classes\dirs::DATA_UPLOADS)) {
            return false;
        }
        
        parent::delete();
        if ($this->hasFileManageThumbnail()) {
            unlink($this->getFileManagerThumbnail());
        }

        $fileName = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, $this->getThumbnail());
        if (file_exists($fileName)) {
            unlink($fileName);
        }

        return $this->dbcon->delete($this->table, 'filename = ?', array($this->filename));
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
        if (!$this->isValidDataFolder($this->getFilepath(), \fpcm\classes\dirs::DATA_UPLOADS) || !parent::rename($newnameExt)) {
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

        if (!$this->createThumbnail()) {
            return false;
        }

        $this->filename = $oldname;
        unlink(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, $this->getThumbnail()));

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
     * Erzeugt ein Thumbnail für das aktuelle Bild
     * @return bool
     */
    public function createThumbnail()
    {
        include_once \fpcm\classes\loader::libGetFilePath('PHPImageWorkshop');

        try {
            $phpImgWsp = \PHPImageWorkshop\ImageWorkshop::initFromPath($this->getFullpath());
            $phpImgWsp->cropToAspectRatio(
                \PHPImageWorkshop\Core\ImageWorkshopLayer::UNIT_PIXEL,
                $this->config->file_thumb_size,
                $this->config->file_thumb_size,
                0, 0, 'MM'
            );

            $fullThumbPath = $this->getThumbnailFull();
            $phpImgWsp->resizeInPixel($this->config->file_thumb_size, $this->config->file_thumb_size);
            $phpImgWsp->save(dirname($fullThumbPath), basename($fullThumbPath), true, null, 85);
        } catch (\ErrorException $exc) {
            trigger_error('Error while creating file thumbnail '.$this->getThumbnail().PHP_EOL.$exc->getMessage());
            return false;
        }        

        $this->events->trigger('image\thumbnailCreate', $this)->getData();
        if (!file_exists($fullThumbPath)) {
            trigger_error('Unable to create thumbnail: ' . $this->getThumbnail());
            return false;
        }

        return true;
    }

    /**
     * Add upload sub folder string
     * @return bool
     * @since 5.0.0-a3
     */
    public function addUploadFolder() : bool
    {
        $this->fullpath = ops::getUploadPath($this->filename, $this->config->file_subfolders);
        
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
        if ($initDB) {
            $dbData = $this->dbcon->selectFetch((new \fpcm\model\dbal\selectParams($this->table))->setWhere('filename = ?')->setParams([$this->filename]));
            if (!$dbData) {  
                $this->isIndexed = false;
            }
            else {                
                foreach ($dbData as $key => $value) {
                    $this->$key = $value;
                }

                $this->isIndexed = true;
            }
        }
        else {
            $this->isIndexed = false;            
        }

        if (!parent::exists()) {
            return true;            
        }

        $this->extension = self::retrieveFileExtension($this->fullpath);

        if (!$this->filesize) {
            $this->filesize = filesize($this->fullpath);
        }

        $fileData = getimagesize($this->fullpath, $metaInfo);
        if (!is_array($fileData)) {
            return true;
        }

        $this->width = $fileData[0];
        $this->height = $fileData[1];
        $this->whstring = $fileData[3];
        $this->mimetype = $fileData['mime'];

        $this->parseIptc($metaInfo);
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

        $this->fullpath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_UPLOADS, $this->filename);
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
        unset($params['cache'], $params['config'], $params['dbcon'], $params['events'], $params['id'], $params['nodata'], $params['system'], $params['table'], $params['dbExcludes'], $params['language'], $params['editAction'], $params['objExists'], $params['cacheName']);

        if ($this->nodata)
            unset($params['data']);

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

        return implode('/', $filename);;
    }

    /**
     * reads IPTC data from file
     * @param array $info
     * @return bool
     * @since 4.2.1 
     */
    public function parseIptc($info)
    {
        if (trim($this->iptcStr)) {
            return true;
        }
        
        if (!function_exists('iptcparse') || !is_array($info) || !count($info)) {
            return false;
        }

        $this->iptcStr = [];
        array_map(function ($item) {

            $iptc = iptcparse($item);
            if (!is_array($iptc) || !count($iptc)) {
                return [];
            }

            foreach (array_keys($iptc) as $s) {             
                $c = count ($iptc[$s]);
                for ($i=0; $i <$c; $i++) {
                    $this->iptcStr[$s] = $iptc[$s][$i];
                }
            }  

            $this->iptcStr = array_intersect_key($this->iptcStr, ['2#080' => 1, '2#110' => 1, '2#116' => 1]);

        }, $info);

        $this->iptcStr = htmlspecialchars(strip_tags(utf8_encode(implode(PHP_EOL, $this->iptcStr))));
        return true;
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
            'fileresx' => $this->getWidth(),
            'fileresy' => $this->getHeight(),
            'filehash' => $this->getFileHash(),
            'filemime' => $this->getMimetype(),
            'credits' => $this->getIptcStr()  
        ];
    }

    /**
     * Get cropper filename string
     * @return string
     * @since 5.0.0-a1
     */
    public static function getCropperFilename(string &$filename)
    {            
        $repl = [
            '{{filename}}' => self::retrieveFileName($filename),
            '{{date}}' => date('Y-m-d'),
            '{{datelong}}' => date('Y-m.d_H-m-s'),
            '{{hash}}' => \fpcm\classes\tools::getHash($filename),
            '{{userid}}' => \fpcm\classes\loader::getObject('\fpcm\model\system\session')->getUserId(),
            '{{random}}' => mt_rand()
        ];
        
        $pattern = \fpcm\classes\loader::getObject('\fpcm\model\system\config')->file_cropper_name;        
        if (!trim($pattern)) {
            $pattern = '{{filename}}_cropped_{{date}}';
        }

        $filename = str_replace(
            array_keys($repl),
            array_values($repl),
            $pattern
        ) . '.' . self::retrieveFileExtension($filename);
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

        if ($ext === 'jpg' || $type === 'image/jpg') {
            $assigned = 'image/jpeg';
        }

        return in_array($type, self::$allowedTypes) && in_array($ext, self::$allowedExts) && $assigned === $type;
    }

}

?>