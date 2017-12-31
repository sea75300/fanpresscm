<?php
    /**
     * Database backup file object
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
    final class dbbackup extends \fpcm\model\abstracts\file {

        /**
         * Erlaubte Dateitypen
         * @var array
         */
        public static $allowedTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp');
        
        /**
         * Erlaubte Dateiendungen
         * @var array
         */
        public static $allowedExts = array('sql', 'sql.gz', 'sql.zip');
        
        /**
         * MIME-Dateityp-Info
         * @var string
         */
        protected $mimetype;

        /**
         * Konstruktor
         * @param string $filename Dateiname
         * @param string $filepath Dateipfad
         * @param string $content Dateiinhalt
         */
        public function __construct($filename = '', $filepath = '', $content = '') {

            if (!$filepath) {
                $filepath = \fpcm\classes\baseconfig::$dbdumpDir;
            }

            parent::__construct($filename, $filepath, $content);
            
            if (!$this->exists()) {
                return false;
            }

            $finfo          = new \finfo(FILEINFO_MIME_TYPE);
            $this->mimetype = $finfo->file($this->fullpath);
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
            return false;
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
        
    }
?>