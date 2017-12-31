<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Article template file list
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.3
     */    
    final class templatefilelist extends \fpcm\model\abstracts\filelist {

        /**
         * Konstruktor
         */
        public function __construct() {
            $this->basepath = \fpcm\classes\baseconfig::$articleTemplatesDir;
            $this->exts     = templatefile::$allowedExts;
            parent::__construct();
        }
        
        /**
         * Gibt aktuelle Größe des upload-Ordners in byte zurück
         * @return int
         */
        public function getUploadFolderSize() {     
            return array_sum(array_map('filesize', $this->getFolderList()));
        }

        /**
         * Gibt Liste von Dateien zurück
         * @return array
         */
        public function getFolderList() {            

            $files = parent::getFolderList();
            
            $idxkey = array_search(\fpcm\classes\baseconfig::$articleTemplatesDir.'index.html', $files);
            unset($files[$idxkey]);

            return $files;
        }

        /**
         * Gibt Liste von Dateiobjekte zurück
         * @return array
         * @since FPCM 3.3
         */
        public function getFolderObjectList() {            

            $files = $this->getFolderList();
            
            $ret = [];
            foreach ($files as $file) {
                $ret[] = new templatefile(basename($file));
            }

            return $ret;
        }
        
    }
?>