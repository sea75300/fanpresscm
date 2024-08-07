<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Author image file objekt
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.6
 */
final class authorImage extends image {

    /**
     * Konstruktor
     * @param string $filename Dateiname
     */
    public function __construct($filename)
    {
        parent::__construct($filename);
        $this->init(false);
    }

    /**
     * Returns base path for file
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_PROFILES, 'images/' . $filename);
    }

    /**
     * Bild-Url ausgeben
     * @return string
     */
    public function getImageUrl()
    {
        return \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_PROFILES, 'images/' . $this->filename);
    }

    /**
     * Dateimanager-Thumbnail ausgeben
     * @return string
     */
    public function getFileManagerThumbnailUrl()
    {
        return '';
    }

    /**
     * Thumbnail-Pfad ausgeben
     * @return string
     */
    public function getThumbnail()
    {
        return '';
    }

    /**
     * Dateimanager-Thumbnail-Pfad ausgeben
     * @return string
     */
    public function getFileManagerThumbnail()
    {
        return '';
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
     * Löscht Datei-Eintrag in Datenbank und Datei in Dateisystem
     * @return bool
     */
    public function delete()
    {
        if (!$this->isValidDataFolder($this->filepath)) {
            return false;
        }
        return unlink($this->fullpath);
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

    /**
     * Check if image exists
     * @param type $dbOnly
     * @return bool
     */
    public function exists($dbOnly = false)
    {
        return parent::existsFolder();
    }

    /**
     * Erzeugt ein Thumbnail für das aktuelle Bild
     * @return bool
     */
    public function createThumbnail()
    {
        return true;
    }

    /**
     * Gibt Speicher-Values zurück
     * @return array
     */
    protected function getSaveValues()
    {
        return true;
    }

    /**
     * initialisiert Bild-Objekt
     * @param bool $initDB
     * @return bool
     */
    protected function init($initDB)
    {
        if (!$this->exists()) {
            return false;
        }

        $this->extension = self::retrieveFileExtension($this->fullpath);
        $this->filesize = filesize($this->fullpath);

        $fileData = getimagesize($this->fullpath);

        if (is_array($fileData)) {
            $this->width = $fileData[0];
            $this->height = $fileData[1];
            $this->whstring = $fileData[3];
            $this->mimetype = $fileData['mime'];
        }
    }

    /**
     * Füllt Objekt mit Daten aus Datenbank-Result
     * @param object $object
     * @return bool
     * @since 3.1.2
     */
    public function createFromDbObject($object)
    {
        return true;
    }

}

?>