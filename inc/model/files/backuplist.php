<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Backup files list object
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.1.0
 */
final class backuplist extends \fpcm\model\abstracts\filelist {

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->basepath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DBDUMP, '/');
        $this->exts = dbbackup::$allowedExts;
        parent::__construct();
    }

    /**
     * Gibt aktuelle Größe des upload-Ordners in byte zurück
     * @return int
     */
    public function getUploadFolderSize()
    {
        return array_sum(array_map('filesize', $this->getFolderList()));
    }

}

?>