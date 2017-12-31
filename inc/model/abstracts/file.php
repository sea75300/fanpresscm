<?php
    /**
     * FanPress CM file model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * File model base
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class file {

        /**
         * Flag für $content-Parameter, um gespeicherten Inhalt zu laden
         * @since FPCM 3.5
         */
        const FPCM_FILE_LOADCONTENT = '###fpcmLdStg###';

        /**
         * Tabellen-Name
         * @var string
         */
        protected $table;
        
        /**
         * DB-Verbindung
         * @var \fpcm\classes\database
         */
        protected $dbcon;        
        
        /**
         * Cache-Objekt
         * @var \fpcm\classes\cache
         */
        protected $cache;
        
        /**
         * Event-Liste
         * @var \fpcm\model\events\eventList 
         */
        protected $events;
        
        /**
         * System-Config-Objekt
         * @var \fpcm\model\system\config
         */
        protected $config;
        
        /**
         * System-Sprachen-Objekt
         * @var \fpcm\classes\language
         */
        protected $language;

        /**
         * Notifications
         * @var \fpcm\model\theme\notifications
         * @since FPCM 3.6
         */
        protected $notifications;
        
        /**
         * Dateiname
         * @var string
         */
        protected $filename;
        
        /**
         * Dateispfad
         * @var string
         */        
        protected $filepath;
        
        /**
         * Dateipfad inkl. Dateiname
         * @var string
         */        
        protected $fullpath;
                
        /**
         * Dateierweiterung
         * @var string
         */
        protected $extension;
        
        /**
         * Dateigröße
         * @var int
         */
        protected $filesize;

        /**
         * Dateiinhalt
         * @var string
         */
        protected $content;
        
        /**
         * data-Array für nicht weiter definierte Eigenschaften
         * @var array
         */
        protected $data;
        
        /**
         * Cache name
         * @var string
         */
        protected $cacheName    = false;
        
        /**
         * Cache Modul
         * @var string
         * @since FPCM 3.4
         */
        protected $cacheModule    = '';

        /**
         * Konstruktor
         * @param string $filename
         * @param string $filepath
         * @param string $content
         */
        public function __construct($filename = '', $filepath = '', $content = '') {

            $this->escapeFileName($filename);
            
            $this->dbcon    = \fpcm\classes\baseconfig::$fpcmDatabase;
            
            if (\fpcm\classes\baseconfig::installerEnabled()) return false;
            
            $this->cache         = new \fpcm\classes\cache($this->cacheName ? $this->cacheName : md5(microtime(false)), $this->cacheModule);
            $this->events        = \fpcm\classes\baseconfig::$fpcmEvents;
            $this->config        = \fpcm\classes\baseconfig::$fpcmConfig;
            $this->language      = \fpcm\classes\baseconfig::$fpcmLanguage;
            $this->notifications = !empty(\fpcm\classes\baseconfig::$fpcmNotifications) ? \fpcm\classes\baseconfig::$fpcmNotifications : null;
            
            $this->config->setUserSettings();
            
            $this->filename = $filename;
            $this->filepath = $filepath;
            $this->fullpath = $filepath.$filename;
            $this->content  = ($content === self::FPCM_FILE_LOADCONTENT ? file_get_contents($this->fullpath) : $content);
            
            if ($this->exists()){
                $ext = pathinfo($this->fullpath, PATHINFO_EXTENSION);
                $this->extension = ($ext) ? $ext : '';
                $this->filesize = filesize($this->fullpath);
            }          
        }
        
        /**
         * Magic get
         * @param string $name
         * @return mixed
         */
        public function __get($name) {
            return isset($this->data[$name]) ? $this->data[$name] : false;
        }
        
        /**
         * Magic set
         * @param mixed $name
         * @param mixed $value
         */
        public function __set($name, $value) {
            $this->data[$name] = $value;
        }
        
        /**
         * Magic string
         * @return string
         */
        public function __toString() {
            return $this->filename;
        }
        
        /**
         * Magische Methode für nicht vorhandene Methoden
         * @param string $name
         * @param mixed $arguments
         * @return boolean
         */
        public function __call($name, $arguments) {
            print "Function '{$name}' not found in ".get_class($this).'<br>';
            return false;
        }

        /**
         * Magische Methode für nicht vorhandene, statische Methoden
         * @param string $name
         * @param mixed $arguments
         * @return boolean
         */        
        public static function __callStatic($name, $arguments) {
            print "Static function '{$name}' not found in ".get_class($this).'<br>';
            return false;
        }
        
        /**
         * Gibt Inhalt von "data" zurück
         * @return array
         */
        public function getData() {
            return $this->data;
        }
        
        /**
         * Löscht Datei in Dateisystem
         * @return bool
         */        
        public function delete() {
            if ($this->exists() && !unlink($this->fullpath)) {
                trigger_error('Unable to delete file: '.$this->fullpath);
                return false;
            }
            
            return true;
        }
        
        /**
         * Datei umbenennen
         * @param string $newname
         * @param int $userid
         * @return bool
         */
        public function rename($newname, $userid = false) {
            
            if (!rename($this->fullpath, $this->filepath.$newname)) {
                trigger_error('Unable to rename file: '.$this->fullpath);
                return false;
            }
            
            $this->filename = $newname;
            $this->fullpath = $this->filepath.$newname;
            
            return true;            
        }
        
        /**
         * Prüft ob Datei existiert
         * @return bool
         */
        public function exists() {
            return file_exists($this->fullpath);
        }

        /**
         * Dateiname
         * @return string
         */
        public function getFilename() {
            return $this->filename;
        }
        
        /**
         * Dateipfad
         * @return string
         */
        public function getFilepath() {
            return $this->filepath;
        }

        /**
         * Dateipfad + Dateiname
         * @return string
         */
        public function getFullpath() {
            return $this->fullpath;
        }

        /**
         * Erweiterung
         * @return string
         */
        public function getExtension() {
            return $this->extension;
        }

        /**
         * Dateigröße
         * @return int
         */
        public function getFilesize() {
            return $this->filesize;
        }

        /**
         * Dateiinhalt
         * @return string
         */
        public function getContent() {
            return $this->content;
        }

        /**
         * Dateiname setzen
         * @param string $filename
         */
        public function setFilename($filename) {
            $this->filename = $filename;
        }

        /**
         * Dateipfad setzen
         * @param string $filepath
         */
        public function setFilepath($filepath) {
            $this->filepath = $filepath;
        }

        /**
         * Dateiinhalt setzen
         * @param string $content
         */
        public function setContent($content) {
            $this->content = $content;
        }
        
        /**
         * Bereinigt Dateiname von problematischen Zeichen
         * @param string $filename
         */
        public function escapeFileName(&$filename) {
            $filename = preg_replace('/[^A-Za-z0-9_.\-]/', '', htmlentities($filename, ENT_COMPAT | ENT_HTML401));
        }

        /**
         * Verschiebt via PHP Upload hochgeladene Datei von tmp-Pfad nach Zielpfad
         * @param string $uploadedPath
         * @return bool
         * @since FPCM 3.3
         */
        public function moveUploadedFile($uploadedPath) {
            return move_uploaded_file($uploadedPath, $this->fullpath);
        }

        /**
         * Lädt Inhalt von gespeicherter Datei
         * @return boolean
         * @since FPCM 3.5
         */
        public function loadContent() {
            $this->content = file_get_contents($this->fullpath);
            
            if (!trim($this->content)) {
                return false;
            }

            return true;
        }

        /**
         * ist Datei beschreibbar
         * @return boolean
         * @since FPCM 3.5
         */
        public function isWritable() {
            return is_writable($this->fullpath) ? true : false;
        }

        /**
         * ist Datei lesbar
         * @return boolean
         * @since FPCM 3.5
         */
        public function isReadable() {
            return is_readable($this->fullpath) ? true : false;
        }
        
    }
