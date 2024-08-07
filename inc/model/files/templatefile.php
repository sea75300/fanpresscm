<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Article draft template file object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\files
 * @since 3.3
 */
final class templatefile extends \fpcm\model\abstracts\file implements \fpcm\model\interfaces\validateFileType {

    /**
     * Erlaubte Dateitypen
     * @var array
     */
    public static $allowedTypes = ['application/xhtml+xml', 'text/html', 'text/plain'];

    /**
     * Erlaubte Dateiendungen
     * @var array
     */
    public static $allowedExts = ['html', 'htm', 'txt'];

    /**
     * Returns base path for file
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_DRAFTS, $filename);
    }

    /**
     * Liefert eine URL zum Aufrufend er Datei zurück
     * @return string
     */
    public function getFileUrl()
    {
        return \fpcm\classes\dirs::getRootUrl(ltrim(ops::removeBaseDir($this->fullpath), DIRECTORY_SEPARATOR));
    }

    /**
     * Liefert eine URL für Editor zurück
     * @return string
     * @since 3.5
     */
    public function getEditLink()
    {
        return \fpcm\classes\tools::getFullControllerLink('templates/templateedit', [
                    'file' => urlencode(\fpcm\classes\loader::getObject('\fpcm\classes\crypt')->encrypt($this->filename))
        ]);
    }

    /**
     * Speichert Template in Dateisystem
     * @return bool
     */
    public function save()
    {
        if (!$this->exists() || !$this->content || !$this->isWritable()) {
            return false;            
        }

        if (!file_put_contents($this->fullpath, $this->content)) {
            trigger_error('Unable to update template ' . $this->fullpath);
            return false;
        }

        return true;
    }

    /**
     * Check if file exists
     * @return bool
     */
    public function exists()
    {
        if (!$this->isValidDataFolder('', \fpcm\classes\dirs::DATA_DRAFTS)) {
            return false;
        }

        if (!parent::exists()) {
            return false;
        }

        return true;
    }

    /**
     * Check if file extension and file type is valid
     * @param string $ext
     * @param string $type
     * @return bool
     * @since 4.5
     * @see \fpcm\model\interfaces\validateFileType
     */
    public static function isValidType(string $ext, string $type, array $map = []) : bool
    {
        return in_array($ext, self::$allowedExts) && in_array($type, self::$allowedTypes);
    }

}

?>