<?php
    /**
     * Revision file object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Revision file object
     * 
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class revision extends \fpcm\model\abstracts\file {
        
        /**
         * Konstruktor
         * @param string $filename Dateiname
         * @param string $filepath Dateipfad
         * @param string $content Dateiinhalt
         */
        public function __construct($filename = '', $filepath = '', $content = '') {
            
            $filename = 'rev'.$filename.'.php';
            
            parent::__construct($filename, $filepath, $content);
            
            if ($this->exists() && !$this->content) {
                $this->init();
            }
        }
        
        /**
         * Speichern Revision
         * @return boolean
         */
        public function save() {
            if (!file_put_contents($this->fullpath, $this->content)) {
                trigger_error('Unable to create revision file: '.$this->fullpath);
                return false;
            }
            
            return true;
        }
        
        /**
         * Initialisiert Revision
         */
        public function init() {            
            $this->content = json_decode(file_get_contents($this->fullpath), true);
        }
        
    }
?>