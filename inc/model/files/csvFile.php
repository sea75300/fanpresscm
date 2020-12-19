<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * CSv file object
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5-b8
 */
final class csvFile extends \fpcm\model\abstracts\file {

    /**
     * Erlaubte Dateitypen
     * @var array
     */
    public static $allowedTypes = ['text/csv'];

    /**
     * Erlaubte Dateiendungen
     * @var array
     */
    public static $allowedExts = ['csv'];

    /**
     * Resource from fopen
     * @var resource
     */
    private $handle;

    /**
     * Constructor
     * @param string $filename
     */
    public function __construct($filename = '')
    {
        parent::__construct($filename);
        $this->init();
    }

    /**
     * Destructor
     * @return void
     */
    public function __destruct()
    {
        if (!$this->hasResource()) {
            return;
        }

        fclose($this->handle);
    }

    /**
     * Returns file data base path
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, \fpcm\classes\tools::getHash($filename).'.csv');
    }

    /**
     * 
     * @return bool
     * @ignore
     */
    public function save()
    {
        return false;
    }
    
    /**
     * 
     * @param string $newname
     * @param bool|int $userid
     * @return bool
     * @ignore
     */
    public function rename($newname, $userid = false)
    {
        return false;
    }

    /**
     * 
     * @return bool
     * @ignore
     */
    public function setContent($content)
    {
        return false;
    }

    /**
     * 
     * @return bool
     * @ignore
     */
    public function setFilename($filename)
    {
        return false;
    }

    /**
     * 
     * @return bool
     * @ignore
     */
    public function writeContent()
    {
        return false;
    }

    /**
     * Check if handle was set
     * @return bool
     */
    public function hasResource() : bool
    {
        return is_resource($this->handle);
    }
    
    /**
     * Seek file pos
     * @param int $current
     * @return int
     */
    public function seek(int $current) : int
    {
        if (!$this->hasResource()) {
            return -1;
        }

        return fseek($this->handle, $current);
    }

    /**
     * Tell file pos
     * @return int
     */
    public function tell() : int
    {
        if (!$this->hasResource()) {
            return -1;
        }

        return ftell($this->handle);
    }

    /**
     * Tell file pos
     * @return int
     */
    public function isEoF() : bool
    {
        if (!$this->hasResource()) {
            return true;
        }

        return feof($this->handle);
    }

    /**
     * Fetch file content
     * @param string $delim
     * @param string $enclosure
     * @return array
     */
    public function getCsv(string $delim, string $enclosure) : array
    {
        if (!$this->hasResource()) {
            return [];
        }

        return fgetcsv($this->handle, 0, $delim, $enclosure);
    }

    /**
     * Initialize file handle
     * @return void
     */
    public function init()
    {
        if (!$this->exists() || !$this->isReadable()) {
            return;
        }

        $this->handle = fopen($this->fullpath, 'r');
    }

    /**
     * Retieves file mime type
     * @return string
     */
    public function getMimeType() : string
    {
        return (new \finfo())->file($this->fullpath, FILEINFO_MIME_TYPE);
    }

    /**
     * Assigns row by index to field
     * @param array $fields
     * @param array $line
     * @return void
     */
    public function assignCsvFields(array $fields, array &$line)
    {
        $tmp = [];
        foreach ($fields as $index => $field) {
            
            $field = preg_replace('/^(csv_field_){1}(.+)$/i', '$2', $field);
            if (!trim($field)) {
                return false;
            }

            $res[$field] = $line[$index] ?? '';
        }

        $line = $tmp;
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
        $assigned = array_combine(self::$allowedExts, self::$allowedTypes)[$ext] ?? null;
        if ($assigned === null) {
            return false;
        }

        return in_array($type, self::$allowedTypes) && in_array($ext, self::$allowedExts) && $assigned === $type;
    }

}
