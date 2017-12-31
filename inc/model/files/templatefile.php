<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Article draft template file object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\files
     * @since FPCM 3.3
     */
    final class templatefile extends \fpcm\model\abstracts\file {

        /**
         * Erlaubte Dateitypen
         * @var array
         */
        public static $allowedTypes = array('application/xhtml+xml', 'text/html');
        
        /**
         * Erlaubte Dateiendungen
         * @var array
         */
        public static $allowedExts = array('html', 'htm');

        /**
         * Konstruktor
         * @param string $filename Dateiname
         * @param string $filepath Dateipfad
         * @param string $content Dateiinhalt
         */
        public function __construct($filename = '', $filepath = '', $content = '') {
            parent::__construct($filename, \fpcm\classes\baseconfig::$articleTemplatesDir.$filepath, $content);
        }

        /**
         * Liefert eine URL zum Aufrufend er Datei zurück
         * @return string
         */
        public function getFileUrl() {
            return \fpcm\classes\baseconfig::$rootPath.  ltrim(ops::removeBaseDir($this->fullpath), '/');
        }

        /**
         * Liefert eine URL für Editor zurück
         * @return string
         * @since FPCM 3.5
         */
        public function getEditUrl() {
            $crypt = new \fpcm\classes\crypt();
            return \fpcm\classes\baseconfig::$rootPath.\fpcm\classes\tools::getControllerLink('system/templateedit', [
                'file' => urlencode($crypt->encrypt($this->filename))
            ]);
        }
        
        /**
         * Speichert Template in Dateisystem
         * @return boolean
         */
        public function save() {

            if (!$this->exists() || !$this->content || !$this->isWritable()) return false;

            if (!file_put_contents($this->fullpath, $this->content)) {
                trigger_error('Unable to update template '.$this->fullpath);
                return false;
            }
            
            return true;
        }
        
    }
?>