<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

use fpcm\classes\dirs;
use fpcm\classes\loader;
use fpcm\classes\tools;
use fpcm\model\files\ops;

/**
 * File model base
 * 
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class file {

    /**
     * Tabellen-Name
     * @var string
     */
    protected $table;

    /**
     * DB-Verbindung
     * @var \fpcm\classes\database
     */
    protected $dbcon;

    /**
     * Cache-Objekt
     * @var \fpcm\classes\cache
     */
    protected $cache;

    /**
     * Event-Liste
     * @var \fpcm\events\events 
     */
    protected $events;

    /**
     * System-Config-Objekt
     * @var \fpcm\model\system\config
     */
    protected $config;

    /**
     * System-Sprachen-Objekt
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     * Notifications
     * @var \fpcm\model\theme\notifications
     * @since FPCM 3.6
     */
    protected $notifications;

    /**
     * Dateiname
     * @var string
     */
    protected $filename;

    /**
     * Dateispfad
     * @var string
     */
    protected $filepath;

    /**
     * Dateipfad inkl. Dateiname
     * @var string
     */
    protected $fullpath;

    /**
     * Dateierweiterung
     * @var string
     */
    protected $extension;

    /**
     * Dateigröße
     * @var int
     */
    protected $filesize;

    /**
     * Dateiinhalt
     * @var string
     */
    protected $content;

    /**
     * data-Array für nicht weiter definierte Eigenschaften
     * @var array
     */
    protected $data;

    /**
     * Cache name
     * @var string
     */
    protected $cacheName = false;

    /**
     * Cache Modul
     * @var string
     * @since FPCM 3.4
     */
    protected $cacheModule = '';
    
    /**
     * Konstruktor
     * @param strong $filename
     * @return bool
     */
    public function __construct($filename = '')
    {
        if ($filename) {
            $this->fullpath = $this->basePath($filename);
            $this->filepath = dirname($this->fullpath);
            $this->filename = basename($this->fullpath);
        }

        $this->dbcon = loader::getObject('\fpcm\classes\database');

        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return false;
        }

        $this->cache = loader::getObject('\fpcm\classes\cache');
        $this->events = loader::getObject('\fpcm\events\events');
        $this->config = loader::getObject('\fpcm\model\system\config');
        $this->language = loader::getObject('\fpcm\classes\language');
        $this->notifications = loader::getObject('\fpcm\model\theme\notifications');

        if ($this->exists()) {
            $ext = pathinfo($this->fullpath, PATHINFO_EXTENSION);
            $this->extension = ($ext) ? $ext : '';
            $this->filesize = filesize($this->fullpath);
        }
    }

    /**
     * Returns base path for file
     * @param string $filename File name
     * @return string
     * @abstract
     */
    abstract protected function basePath($filename);

    /**
     * Magic get
     * @param string $name
     * @return mixed
     * @ignore
     */
    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : false;
    }

    /**
     * Magic set
     * @param mixed $name
     * @param mixed $value
     * @ignore
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * Magic string
     * @return string
     * @ignore
     */
    public function __toString()
    {
        return $this->filename;
    }

    /**
     * Magische Methode für nicht vorhandene Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     * @ignore
     */
    public function __call($name, $arguments)
    {
        print "Function '{$name}' not found in " . get_class($this) . '<br>';
        return false;
    }

    /**
     * Magische Methode für nicht vorhandene, statische Methoden
     * @param string $name
     * @param mixed $arguments
     * @return bool
     * @ignore
     */
    public static function __callStatic($name, $arguments)
    {
        print "Static function '{$name}' not found in " . get_called_class() . '<br>';
        return false;
    }

    /**
     * Gibt Inhalt von "data" zurück
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Löscht Datei in Dateisystem
     * @return bool
     */
    public function delete()
    {
        if (!$this->isReadable() || !$this->isWritable()) {
            trigger_error('Unable to delete file, invalid read and/or write permissions: ' . $this->fullpath);
            return false;
        }

        if ($this->exists() && !unlink($this->fullpath)) {
            trigger_error('Unable to delete file: ' . $this->fullpath);
            return false;
        }

        return true;
    }

    /**
     * Datei umbenennen
     * @param string $newname
     * @param int $userid
     * @return bool
     */
    public function rename($newname, $userid = false)
    {
        if (!$this->isReadable()) {
            return false;
        }

        $newFullPath = $this->basePath($newname);
        if (!$this->isValidDataFolder($newFullPath)) {
            return false;
        }

        if (!rename($this->fullpath, $newFullPath)) {
            trigger_error('Unable to rename file: ' . $this->fullpath);
            return false;
        }

        $this->filename = $newname;
        $this->fullpath = $newFullPath;

        return true;
    }

    /**
     * Prüft ob Datei existiert
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->fullpath);
    }

    /**
     * Return file upload time in file system
     * @return int
     */
    public function getModificationTime()
    {
        $ts = filemtime($this->fullpath);
        if (!$ts) {
            $ts = filectime($this->fullpath);
        }

        return $ts;
    }

    /**
     * Dateiname
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Dateipfad
     * @return string
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Dateipfad + Dateiname
     * @return string
     */
    public function getFullpath()
    {
        return $this->fullpath;
    }

    /**
     * Erweiterung
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Dateigröße
     * @return int
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Dateiinhalt
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * File SHA256 hash
     * @return string
     * @since FPCM 4.1
     */
    public function getFileHash() : string
    {
        return ops::hashFile($this->fullpath);
    }

    /**
     * Dateiname setzen
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        $this->fullpath = $this->basePath($filename);
    }

    /**
     * Dateiinhalt setzen
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Bereinigt Dateiname von problematischen Zeichen
     * @param string $filename
     */
    public function escapeFileName(&$filename)
    {
        $filename = tools::escapeFileName($filename);
    }

    /**
     * Verschiebt via PHP Upload hochgeladene Datei von tmp-Pfad nach Zielpfad
     * @param string $uploadedPath
     * @return bool
     * @since FPCM 3.3
     */
    public function moveUploadedFile($uploadedPath)
    {
        if (!$this->isValidDataFolder($this->filepath)) {
            return false;
        }

        return move_uploaded_file($uploadedPath, $this->fullpath);
    }

    /**
     * Lädt Inhalt von gespeicherter Datei
     * @return bool
     * @since FPCM 3.5
     */
    public function loadContent()
    {
        if (!$this->isValidDataFolder() || !$this->isReadable()) {
            return false;
        }
        
        $this->content = file_get_contents($this->fullpath);

        if (!trim($this->content)) {
            return false;
        }

        return true;
    }

    /**
     * Lädt Inhalt von gespeicherter Datei
     * @return bool
     * @since FPCM 4.2
     */
    public function writeContent()
    {
        if (!$this->isValidDataFolder() || !$this->isWritable()) {
            return false;
        }

        return file_put_contents($this->fullpath, $this->content);
    }

    /**
     * ist Datei beschreibbar
     * @return bool
     * @since FPCM 3.5
     */
    public function isWritable()
    {
        return is_writable($this->fullpath) ? true : false;
    }

    /**
     * ist Datei lesbar
     * @return bool
     * @since FPCM 3.5
     */
    public function isReadable()
    {
        return is_readable($this->fullpath) ? true : false;
    }

    /**
     * 
     * Check if fullpath is valid path in /data folder structure
     * @param string $path
     * @param string $type
     * @return bool
     * @since FPCM 4.1
     */
    public function isValidDataFolder(string $path = '', string $type = '/') : bool
    {
        if (!trim($path)) {
            $path = $this->fullpath;
        }

        $dataPath = dirs::getDataDirPath($type);
        $realpath = realpath($path);
        
        if (!trim($realpath)) {
            $realpath = $this->realpathNoExists($path);
        }

        if (strpos($realpath, $dataPath) === 0) {
            return true;
        }
        
        trigger_error('Invalid data path found: '.$path);
        return false;
    }
    
    protected function realpathNoExists(string $path) : string
    {
        $items = explode('/', $path);
        if (!count($items)) {
            return '';
        }
        
        $realpath = array_reduce($items, function ($carry, $item) use ($path) {
            
            if ($carry === 0) {
                $carry = DIRECTORY_SEPARATOR;
            }
            
            if($item === "" || $item === ".") {
                return $item;
            }
            
            if ($item == '..') {
                return dirname($carry);
            }

            return preg_replace("/\/+/", "/", "$carry/$item");
        });

        return $realpath;
        
        
    }

}
