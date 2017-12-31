<?php
    /**
     * Backup files list object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.1.0
     */
    namespace fpcm\model\files;

    /**
     * Image list object
     * 
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.1.0
     */    
    final class backuplist extends \fpcm\model\abstracts\filelist {

        /**
         * Konstruktor
         */
        public function __construct() {
            $this->basepath = \fpcm\classes\baseconfig::$dbdumpDir;
            $this->exts     = dbbackup::$allowedExts;
            
            parent::__construct();
        }
        
        /**
         * Gibt aktuelle Größe des upload-Ordners in byte zurück
         * @return int
         */
        public function getUploadFolderSize() {     
            return array_sum(array_map('filesize', $this->getFolderList()));
        }
        
    }
?>