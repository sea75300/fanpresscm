<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Author image file objekt
     * 
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.6
     */
    final class authorImage extends image {

        /**
         * Konstruktor
         * @param string $filename Dateiname
         */
        public function __construct($filename) {
            parent::__construct($filename, \fpcm\classes\baseconfig::$profilePath.'images'.DIRECTORY_SEPARATOR, '');
            $this->init(false);
        }
        
        /**
         * Bild-Url ausgeben
         * @return string
         */
        public function getImageUrl() {
            return \fpcm\classes\baseconfig::$profilePath.'images/'.$this->filename;
        }


        /**
         * Dateimanager-Thumbnail ausgeben
         * @return string
         */
        public function getFileManagerThumbnailUrl() {
            return '';
        }

        /**
         * Thumbnail-Pfad ausgeben
         * @return string
         */
        public function getThumbnail() {
            return '';
        }

        /**
         * Dateimanager-Thumbnail-Pfad ausgeben
         * @return string
         */
        public function getFileManagerThumbnail() {
            return '';
        }
                
        /**
         * Speichert einen neuen Datei-Eintrag in der Datenbank
         * @return boolean
         */        
        public function save() {
            return false;
        }
        
        /**
         * Aktualisiert einen Datei-Eintrag in der Datenbank
         * @return boolean
         */         
        public function update() {
            return false;
        }
        
        /**
         * Löscht Datei-Eintrag in Datenbank und Datei in Dateisystem
         * @return boolean
         */
        public function delete() {
            return unlink($this->fullpath);
        }
        
        /**
         * Benennt eine Datei um
         * @param string $newname
         * @param int $userId
         * @return boolean
         */
        public function rename($newname, $userId = false) {
            return false;
        }

        /**
         * Prüft ob Datei existiert
         * @param bool $dbOnly
         * @return bool
         */
        public function exists($dbOnly = false) {
            return parent::existsFolder();
        }

        /**
         * Erzeugt ein Thumbnail für das aktuelle Bild
         * @return boolean
         */
        public function createThumbnail() {
            return true;
        }

        /**
         * Gibt Speicher-Values zurück
         * @return array
         */
        protected function getSaveValues() {
            return true;
        }
        
        /**
         * initialisiert Bild-Objekt
         * @param bool $initDB
         * @return boolean
         */
        protected function init($initDB) {
            if (!$this->exists()) {
                return false;
            }

            $ext = pathinfo($this->fullpath, PATHINFO_EXTENSION);
            $this->extension = ($ext) ? $ext : '';                
            $this->filesize  = filesize($this->fullpath); 
            
            $fileData = getimagesize($this->fullpath);

            if (is_array($fileData)) {
                $this->width    = $fileData[0];
                $this->height   = $fileData[1];
                $this->whstring = $fileData[3];
                $this->mimetype = $fileData['mime'];
            }
        }
        
        /**
         * Füllt Objekt mit Daten aus Datenbank-Result
         * @param object $object
         * @return boolean
         * @since FPCM 3.1.2
         */
        public function createFromDbObject($object) {
            return true;
        }
        
    }
?>