<?php
    /**
     * Image file object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Image file objekt
     * 
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class image extends \fpcm\model\abstracts\file {

        /**
         * Erlaubte Dateitypen
         * @var array
         */
        public static $allowedTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp');
        
        /**
         * Erlaubte Dateiendungen
         * @var array
         */
        public static $allowedExts = array('jpeg', 'jpg', 'png', 'gif', 'bmp');

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
         * MIME-Dateityp-Info
         * @var string
         */
        protected $mimetype;
        
        /**
         * Felder die in Datenbank gespeichert werden können
         * @var array
         */
        protected $dbParams = array('userid', 'filename', 'filetime');

        /**
         * Konstruktor
         * @param string $filename Dateiname
         * @param string $filepath Dateipfad
         * @param string $content Dateiinhalt
         * @param bool $initDB Datenbank-Eintrag initialisieren
         * @param bool $forceInit Initialisierung erzwingen
         */
        public function __construct($filename = '', $filepath = '', $content = '', $initDB = true, $forceInit = false) {
            $this->table    = \fpcm\classes\database::tableFiles;
            
            if (!$filepath) $filepath = \fpcm\classes\baseconfig::$uploadDir;
            
            parent::__construct($filename, $filepath, $content);
            
            if ($this->exists() || $forceInit) {
                $this->init($initDB);
            }
            
        }
        
        /**
         * Datensatz-ID
         * @return int
         */
        public function getId() {
            return $this->id;
        }

        /**
         * Bild-Url ausgeben
         * @return string
         */
        public function getImageUrl() {
            return \fpcm\classes\baseconfig::$uploadRootPath.$this->filename;
        }

        /**
         * Thumbnail-Url ausgeben
         * @return string
         */
        public function getThumbnailUrl() {
            return \fpcm\classes\baseconfig::$uploadRootPath.$this->getThumbnail();
        }

        /**
         * Dateimanager-Thumbnail ausgeben
         * @return string
         */
        public function getFileManagerThumbnailUrl() {
            return \fpcm\classes\baseconfig::$filemanagerRootPath.$this->filename;
        }

        /**
         * Thumbnail-Pfad ausgeben
         * @return string
         */
        public function getThumbnail() {
            return 'thumbs/'.$this->filename;
        }

        /**
         * Dateimanager-Thumbnail-Pfad ausgeben
         * @return string
         */
        public function getFileManagerThumbnail() {
            return  \fpcm\classes\baseconfig::$filemanagerTempDir.$this->filename;
        }

        /**
         * Breite ausgeben
         * @return int
         */
        public function getWidth() {
            return $this->width;
        }

        /**
         * Höhe ausgeben
         * @return int
         */
        public function getHeight() {
            return $this->height;
        }

        /**
         * String width="" height="" auslesen
         * @return string
         */
        public function getWhstring() {
            return $this->whstring;
        }

        /**
         * Uploader-ID ausgeben
         * @return int
         */
        public function getUserid() {
            return $this->userid;
        }

        /**
         * Upload-Zeit ausgeben
         * @return int
         */
        public function getFiletime() {
            return $this->filetime;
        }

        /**
         * MIME-Type ausgeben
         * @return int
         */
        public function getMimetype() {
            return $this->mimetype;
        }

        /**
         * Datensatz-ID setzen
         * @param int $id
         */
        public function setId($id) {
            $this->id = $id;
        }        
        
        /**
         * Benutzer-ID setzen
         * @param int $userid
         */        
        public function setUserid($userid) {
            $this->userid = $userid;
        }

        /**
         * Upload-Zeit setzen
         * @param int $filetime
         */
        public function setFiletime($filetime) {
            $this->filetime = $filetime;
        }
                
        /**
         * Speichert einen neuen Datei-Eintrag in der Datenbank
         * @return boolean
         */        
        public function save() {
            if ($this->exists(true)) return false;
            
            $saveValues = $this->getSaveValues();
            $saveValues = $this->events->runEvent('imageSave', $saveValues);
            
            return $this->dbcon->insert($this->table, implode(', ', $this->dbParams), '?, ?, ?', array_values($saveValues));            
        }
        
        /**
         * Aktualisiert einen Datei-Eintrag in der Datenbank
         * @return boolean
         */         
        public function update() {
            if (!$this->exists(true)) return false;
            
            $saveValues = $this->getSaveValues();
            $params = $this->events->runEvent('imageUpdate', $saveValues);

            return $this->dbcon->update($this->table, $this->dbParams, array_values($saveValues), "filename = ?", array($this->filename));
        }
        
        /**
         * Löscht Datei-Eintrag in Datenbank und Datei in Dateisystem
         * @return boolean
         */
        public function delete() {
            parent::delete();            
            if (file_exists($this->getFileManagerThumbnail())) unlink ($this->getFileManagerThumbnail());
            if (file_exists(\fpcm\classes\baseconfig::$uploadDir.$this->getThumbnail())) unlink (\fpcm\classes\baseconfig::$uploadDir.$this->getThumbnail());
            return $this->dbcon->delete($this->table, 'filename = ?', array($this->filename));
            
            return false;
        }
        
        /**
         * Benennt eine Datei um
         * @param string $newname
         * @param int $userId
         * @return boolean
         */
        public function rename($newname, $userId = false) {
            
            $oldname = $this->filename;
            
            if (!parent::rename($newname)) {
                return false;
            }

            $this->filetime = time();
            $this->userid   = $userId;
            $res = $this->dbcon->update($this->table, $this->dbParams, array_values($this->getSaveValues()), "filename = ?", array($oldname));
            
            if (!$res) {
                trigger_error('Unable to update database file info for '.$oldname);
                return false;
            }

            if (!$this->createThumbnail()) {
                return false;
            }
            
            $this->filename = $oldname;
            unlink(\fpcm\classes\baseconfig::$uploadDir.$this->getThumbnail());
            
            return true;
        }

        /**
         * Prüft ob Datei existiert
         * @param bool $dbOnly
         * @return bool
         */
        public function exists($dbOnly = false) {
            
            if (!$this->filename) {
                return false;
            }
            
            $count = $this->dbcon->count($this->table, '*', "filename = ?", array($this->filename));
            if ($dbOnly) {
                return $count > 0 ? true : false;
            }
            
            return (parent::exists() && $count > 0) ? true : false;
        }
        
        /**
         * Prüft, ob Bild nur in Dateisystem existiert
         * @return bool
         * @since FPCM 3.1.2
         */
        public function existsFolder() {
            return parent::exists();
        }

        /**
         * Erzeugt ein Thumbnail für das aktuelle Bild
         * @return boolean
         */
        public function createThumbnail() {

            include_once \fpcm\classes\loader::libGetFilePath('PHPImageWorkshop', 'ImageWorkshop.php');

            $phpImgWsp = \PHPImageWorkshop\ImageWorkshop::initFromPath($this->getFullpath());
            if (memory_get_usage(true) < \fpcm\classes\baseconfig::memoryLimit(true) * 0.5) {
                $phpImgWsp->cropMaximumInPixel(0, 0, 'MM');
            }

            $phpImgWsp->resizeInPixel($this->config->file_img_thumb_width, $this->config->file_img_thumb_height);
            $phpImgWsp->save($this->getFilepath().dirname($this->getThumbnail()),
                             basename($this->getThumbnail()),
                             true,
                             null,
                             85);

            $this->events->runEvent('thumbnailCreate', $this);
            if (!file_exists($this->getFilepath().$this->getThumbnail())) {
                trigger_error('Unable to create filemanager thumbnail: '.$this->getThumbnail());
                return false;
            }

            return true;
        }

        /**
         * Gibt Speicher-Values zurück
         * @return array
         */
        protected function getSaveValues() {
            $values = [];
            foreach ($this->dbParams as $key) {
                $values[$key] = ($this->$key) ? $this->$key : '';
            }
            
            return $values;
        }
        
        /**
         * initialisiert Bild-Objekt
         * @param bool $initDB
         * @return boolean
         */
        protected function init($initDB) {
            if ($initDB) {
                $dbData = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'filename = ?', array($this->filename)));
                
                if (!$dbData) return false;
                
                foreach ($dbData as $key => $value) {
                    $this->$key = $value;
                }                
            }
            
            $ext = pathinfo($this->fullpath, PATHINFO_EXTENSION);
            $this->extension = ($ext) ? $ext : '';                
            $this->filesize  = filesize($this->fullpath); 
            
            if (parent::exists()) {
                $fileData = getimagesize($this->fullpath);

                if (is_array($fileData)) {
                    $this->width    = $fileData[0];
                    $this->height   = $fileData[1];
                    $this->whstring = $fileData[3];
                    $this->mimetype = $fileData['mime'];
                }
            }           
        }
        
        /**
         * Füllt Objekt mit Daten aus Datenbank-Result
         * @param object $object
         * @return boolean
         * @since FPCM 3.1.2
         */
        public function createFromDbObject($object) {
            
            if (!is_object($object)) return false;
            
            $keys   = array_keys($this->getPreparedSaveParams());
            $keys[] = 'id';
            
            foreach ($keys as $key) {
                if (!isset($object->$key)) continue;
                $this->$key = $object->$key;   
            }
            
            $this->fullpath = $this->filepath.$this->filename;
            $this->init(false);
            
            return true;
        }
        
        /**
         * Bereitet Eigenschaften des Objects zum Speichern ind er Datenbank vor und entfernt nicht speicherbare Eigenschaften
         * @return array
         * @since FPCM 3.1.2
         */
        protected function getPreparedSaveParams() {
            $params = get_object_vars($this);
            unset(
                $params['cache'],
                $params['config'],
                $params['dbcon'],
                $params['events'],
                $params['id'],
                $params['nodata'],
                $params['system'],
                $params['table'],
                $params['dbExcludes'],
                $params['language'],
                $params['editAction'],
                $params['objExists'],
                $params['cacheName']
            );
            
            if ($this->nodata) unset($params['data']);
            
            return $params;
        }
        
    }
?>