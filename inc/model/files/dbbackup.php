<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Image file objekt
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class dbbackup extends \fpcm\model\abstracts\file {

    /**
     * Erlaubte Dateitypen
     * @var array
     */
    public static $allowedTypes = ['application/sql', 'text/plain', '', 'application/gzip', 'application/zip'];

    /**
     * Erlaubte Dateiendungen
     * @var array
     */
    public static $allowedExts = ['sql', 'sql.gz', 'sql.zip'];

    /**
     * MIME-Dateityp-Info
     * @var string
     */
    protected $mimetype;

    /**
     * Konstruktor
     * @param string $filename Dateiname
     */
    public function __construct($filename = '')
    {
        parent::__construct($filename);

        if (!$this->exists()) {
            return false;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $this->mimetype = $finfo->file($this->fullpath);
    }

    /**
     * Returns base path for file
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DBDUMP, $filename);
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
     * MIME-Type ausgeben
     * @return int
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * Speichert einen neuen Datei-Eintrag in der Datenbank
     * @return bool
     */
    public function save()
    {
        return false;
    }

    /**
     * Aktualisiert einen Datei-Eintrag in der Datenbank
     * @return bool
     */
    public function update()
    {
        return false;
    }

    /**
     * Benennt eine Datei um
     * @param string $newname
     * @param int $userId
     * @return bool
     */
    public function rename($newname, $userId = false)
    {
        return false;
    }

}

?>