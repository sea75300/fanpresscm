<?php

/**
 * Package object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\packages;

/**
 * System package objekt
 * 
 * @package fpcm\model\packages
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class package {

    /**
     * Fehler beim Abrufen der Update-Server-Infos
     */
    const FPCMPACKAGE_REMOTEFILE_ERROR = -1;

    /**
     * Fehler beim Öffnen der lokalen Datei
     */
    const FPCMPACKAGE_LOCALFILE_ERROR = -2;

    /**
     * Fehler beim Schreiben der Daten in die lokalen Datei
     */
    const FPCMPACKAGE_LOCALWRITE_ERROR = -3;

    /**
     * Prüfung, dass Datei lokal vorhanden ist schlägt fehl
     */
    const FPCMPACKAGE_LOCALEXISTS_ERROR = -4;

    /**
     * Hash-Wert stimmt nicht überein
     */
    const FPCMPACKAGE_HASHCHECK_ERROR = -5;

    /**
     * ZIP-Archiv kann nicht geöffnet werden
     */
    const FPCMPACKAGE_ZIPOPEN_ERROR = -6;

    /**
     * Fehler beim Entpacken des ZIP-Archivs
     */
    const FPCMPACKAGE_ZIPEXTRACT_ERROR = -7;

    /**
     * Fehler beim kopieren der Paket-Dateien
     */
    const FPCMPACKAGE_FILESCOPY_ERROR = -8;

    /**
     * Fehler bei Schreibrechte-Prüfung vorhandener Dateien
     * @since FPCM 3.5
     */
    const FPCMPACKAGE_FILESCHECK_ERROR = -9;

    /**
     * Packages-Unterordner auf Paket-Server
     */
    const FPCMPACKAGE_SERVER_PACKAGEPATH = 'packages/';

    /**
     * Package name
     * @var string
     */
    protected $packageName = '';

    /**
     * ZIP-Archiv-Object
     * @var \ZipArchive
     */
    protected $archive;

    /**
     * ZIP-Archiv-Object
     * @var \fpcm\model\files\fileOption
     */
    protected $fileOption;

    /**
     * Konstruktor
     * @param string $type Package-Type
     * @param string $key Package-Key
     * @param string $version Package-Version
     * @param string $signature Package-Signature
     */
    public function __construct($packageName)
    {
        $this->packageName  = $packageName;
        $this->archive      = new \ZipArchive();
    }

    abstract function initObjects();

    abstract function getRemotePath();

    abstract function getRemoteSignature();

    abstract function getLocalPath();

    abstract function getLocalSignature();

    abstract function getLocalDestinationPath();

    abstract function getStorageData();

    /**
     * Lädt Package in Abhängigkeit von Einstellungen herunter
     * @return boolean
     */
    public function download()
    {
        $handleRemote = fopen($this->remoteFile, 'rb');

        if (!$handleRemote) {
            trigger_error('Unable to connect to remote server: ' . $this->remoteFile);
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return self::FPCMPACKAGE_REMOTEFILE_ERROR;
        }

        if ($this->type == 'module' && !is_dir(dirname($this->localFile))) {
            if (!mkdir(dirname($this->localFile))) {
                trigger_error('Unable to create module vendor folder: ' . dirname($this->localFile));
                \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
                return self::FPCMPACKAGE_LOCALFILE_ERROR;
            }
        }

        $handleLocal = fopen($this->localFile, 'wb');

        if (!$handleLocal) {
            trigger_error('Unable to open local file: ' . $this->localFile);
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return self::FPCMPACKAGE_LOCALFILE_ERROR;
        }

        while (!feof($handleRemote)) {
            if (fwrite($handleLocal, fgets($handleRemote)) === false) {
                trigger_error("Error while writing content of {$this->remoteFile} to {$this->localFile}.");
                \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
                return self::FPCMPACKAGE_LOCALEXISTS_ERROR;
            }
        }

        fclose($handleRemote);
        fclose($handleLocal);

        if (!file_exists($this->localFile)) {
            trigger_error("Downloaded file not found in {$this->localFile}.");
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return self::FPCMPACKAGE_LOCALEXISTS_ERROR;
        }

        $res = $this->checkHashes();
        if ($res !== true) {
            return $res;
        }

        return true;
    }

    /**
     * Package entpacken
     * @return boolean
     */
    public function extract()
    {

        if ($this->archive->open($this->localFile) !== true) {
            trigger_error('Unable to open ZIP archive for extraction: ' . $this->localFile);
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return self::FPCMPACKAGE_ZIPOPEN_ERROR;
        }

        $this->listArchiveFiles();

        if ($this->archive->extractTo($this->extractPath) !== true) {
            trigger_error('Unable to extract ZIP archive: ' . $this->localFile);
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return self::FPCMPACKAGE_ZIPEXTRACT_ERROR;
        }

        return true;
    }

    /**
     * Kopiert Inhalt von Paket von Quelle nach Ziel
     * @return boolean
     */
    public function copy()
    {

        if (!file_exists($this->tempListFile)) {
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return false;
        }

        $this->loadPackageFileListFromTemp();

        if (!count($this->files)) {
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return false;
        }

        $vendorFolder = \fpcm\classes\dirs::getFullDirPath($this->copyDestination . dirname($this->key));
        if ($this->type == 'module' && !is_dir($vendorFolder) && !mkdir($vendorFolder)) {
            trigger_error('Unable to create module vendor folder: ' . \fpcm\model\files\ops::removeBaseDir($vendorFolder, true));
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return false;
        }

        $res = true;
        foreach ($this->files as $zipFile) {
            $source = $this->extractPath . $zipFile;

            $dest = ($this->type == 'module' ? \fpcm\classes\dirs::getFullDirPath($this->copyDestination . str_replace(basename($this->key) . '/', $this->key . '/', $zipFile)) : dirname(\fpcm\classes\dirs::getFullDirPath('')) . $this->copyDestination . $zipFile);

            $dest = $this->replaceFanpressDirString($dest);

            if (is_dir($source)) {
                if (!file_exists($dest) && !mkdir($dest, 0777)) {
                    if (!is_array($res))
                        $res = [];
                    $res[] = $dest;
                }
                continue;
            }

            if (file_exists($dest)) {

                if (sha1_file($source) == sha1_file($dest)) {
                    $this->updateProtocol($zipFile, -1);
                    continue;
                }

                $backFile = $dest . '.back';
                if (file_exists($backFile)) {
                    unlink($backFile);
                }

                rename($dest, $backFile);
            }

            $success = copy($source, $dest);
            if (!$success) {
                if (!is_array($res))
                    $res = [];
                $res[] = $dest;
            }

            $this->updateProtocol($zipFile, $success);
        }

        $this->saveProtocolTemp();
        return is_array($res) ? self::FPCMPACKAGE_FILESCOPY_ERROR : $res;
    }

    /**
     * Prüft Dateien ob beschreibbar
     * @return boolean
     * @since FPCM 3.5
     */
    public function checkFiles()
    {

        if (!file_exists($this->tempListFile)) {
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return false;
        }

        $this->loadPackageFileListFromTemp();
        if (!count($this->files)) {
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return false;
        }

        $res = [];
        foreach ($this->files as $zipFile) {

            $source = $this->extractPath . $zipFile;

            $dest = ($this->type == 'module' ? \fpcm\classes\dirs::getFullDirPath($this->copyDestination . str_replace(basename($this->key) . '/', $this->key . '/', $zipFile)) : dirname(\fpcm\classes\dirs::getFullDirPath('')) . $this->copyDestination . $zipFile);

            $dest = $this->replaceFanpressDirString($dest);
            if (!$dest || file_exists($dest) && !is_writable($dest)) {
                $res[] = $dest;
                continue;
            }
        }

        if (!count($res)) {
            return true;
        }

        $this->copyErrorPaths = $res;
        return self::FPCMPACKAGE_FILESCHECK_ERROR;
    }

    /**
     * Lädt Paket-Dateiliste aus temporärer Datei
     * @return boolean
     */
    public function loadPackageFileListFromTemp()
    {

        if (count($this->files)) {
            return true;
        }

        $this->files = json_decode(base64_decode(file_get_contents($this->tempListFile)), true);
        return true;
    }

    /**
     * Löscht temoräre Dateien
     */
    public function cleanup()
    {
        if (!unlink($this->tempListFile)) {
            trigger_error('Unable to remove temp list file: ' . \fpcm\model\files\ops::removeBaseDir($this->tempListFile, true));
        }

        if (!unlink($this->localFile)) {
            trigger_error('Unable to delete local package copy: ' . \fpcm\model\files\ops::removeBaseDir($this->localFile, true));
        }

        if (!\fpcm\model\files\ops::deleteRecursive($this->extractPath)) {
            trigger_error('Package extraction path still exists in ' . \fpcm\model\files\ops::removeBaseDir($this->extractPath, true));
        }
    }

    /**
     * Baut Datei-Liste aus Archiv auf
     */
    protected function listArchiveFiles()
    {

        for ($i = 0; $i < $this->archive->numFiles; $i++) {
            $this->files[] = $this->archive->getNameIndex($i);
        }

        if (!file_put_contents($this->tempListFile, base64_encode(json_encode($this->files)))) {
            trigger_error('Failed to create temporary package file list');
            return false;
        }

        return true;
    }

    /**
     * Initialisiert Daten
     */
    public function init()
    {
        $this->remoteFile = \fpcm\classes\baseconfig::$updateServer . self::FPCMPACKAGE_SERVER_PACKAGEPATH . $this->filename;
        $this->localFile = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, $this->filename);
        $this->extractPath = dirname($this->localFile) . '/' . md5(basename($this->localFile, '.zip')) . '/';
        $this->tempListFile = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, md5($this->localFile));
    }

    /**
     * Prüft ob Datei-Hashed und ggf. Datei-Signaturen übereinstimmen
     * @return boolean
     */
    protected function checkHashes()
    {
        $this->buildHashes();

        if ($this->remoteHash != $this->localHash) {
            trigger_error('Remote and local file hash do not match for ' . \fpcm\model\files\ops::removeBaseDir($this->localFile, true));
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return self::FPCMPACKAGE_HASHCHECK_ERROR;
        }


        if ($this->remoteSignature != $this->localSignature) {
            trigger_error('Remote and local file hash do not match for ' . \fpcm\model\files\ops::removeBaseDir($this->localFile, true));
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return self::FPCMPACKAGE_HASHCHECK_ERROR;
        }

        return true;
    }

    /**
     * Erzeugt Hashed und loale Datei-Signatur
     * @return void
     */
    protected function buildHashes()
    {
        $this->remoteHash = sha1_file($this->remoteFile);
        $this->localHash = sha1_file($this->localFile);

        if (!$this->remoteSignature) {
            return;
        }

        $this->localSignature = '$sig$' . md5_file($this->localFile) . '_' . sha1_file($this->localFile) . '$sig$';
    }

    /**
     * Ersetzt "fanpress"-Ordnername durch basedir-Daen in einem Pfad
     * @param string $path
     * @return string
     */
    protected function replaceFanpressDirString($path)
    {
        return str_replace('fanpress/', basename(\fpcm\classes\dirs::getFullDirPath('')) . '/', $path);
    }

    /**
     * Dateiname/ Key der Form modulkey_versionX.Y.Z aufsplitten
     * @param string $filename
     * @return array
     */
    public static function explodeModuleFileName($filename)
    {
        return explode('_version', $filename);
    }

    /**
     * copy-Protokoll
     * @param string $file
     * @param bool $success
     * @return boolean
     * @see package::copy
     * @since FPCM 3.6
     */
    protected function updateProtocol($file, $success)
    {
        $this->protocol[] = $file . ' (' . ($success === -1 ? 'SKIPPED' : ($success ? 'OK' : 'FAILED')) . ')';
        return true;
    }

    /**
     * copy-Protokoll in temporäre Datei speichern
     * @return boolean
     * @since FPCM 3.6
     */
    protected function saveProtocolTemp()
    {
        $tempfile = new \fpcm\model\files\tempfile('protocol' . $this->localFile);
        $tempfile->setContent(base64_encode(json_encode($this->protocol)));
        return $tempfile->save();
    }

}
