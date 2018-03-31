<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\packages;

/**
 * Package object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class package {

    /**
     * Fehler beim Abrufen der Update-Server-Infos
     */
    const REMOTEFILE_ERROR = 901;

    /**
     * Fehler beim Öffnen der lokalen Datei
     */
    const LOCALFILE_ERROR = 902;

    /**
     * Fehler beim Schreiben der Daten in die lokalen Datei
     */
    const LOCALWRITE_ERROR = 903;

    /**
     * Prüfung, dass Datei lokal vorhanden ist schlägt fehl
     */
    const LOCALEXISTS_ERROR = 904;

    /**
     * Hash-Wert stimmt nicht überein
     */
    const HASHCHECK_ERROR = 905;

    /**
     * ZIP-Archiv kann nicht geöffnet werden
     */
    const ZIPOPEN_ERROR = 906;

    /**
     * Fehler beim Entpacken des ZIP-Archivs
     */
    const ZIPEXTRACT_ERROR = 907;

    /**
     * Fehler beim kopieren der Paket-Dateien
     */
    const FILESCOPY_ERROR = 908;

    /**
     * Fehler bei Schreibrechte-Prüfung vorhandener Dateien
     * @since FPCM 3.5
     */
    const FILESCHECK_ERROR = 909;

    /**
     * Fehler bei Schreibrechte-Prüfung vorhandener Dateien
     * @since FPCM 3.5
     */
    const REMOTEPATH_UNTRUSTED = 910;

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
     * Konstruktor
     * @param string $type Package-Type
     * @param string $key Package-Key
     * @param string $version Package-Version
     * @param string $signature Package-Signature
     */
    final public function __construct($packageName)
    {
        $this->packageName  = $packageName;
        $this->archive      = new \ZipArchive();
        $this->initObjects();
    }

    /**
     * 
     * @return bool
     */
    abstract protected function initObjects();

    /**
     * 
     * @return string
     */
    abstract protected function getRemotePath();

    /**
     * 
     * @return string
     */
    abstract protected function getRemoteSignature();

    /**
     * 
     * @return string
     */
    abstract protected function getLocalPath();

    /**
     * 
     * @return string
     */
    abstract protected function getLocalSignature();

    /**
     * 
     * @return string
     */
    abstract protected function getExtractionPath();

    /**
     * 
     * @return string
     */
    abstract protected function getLocalDestinationPath();

    /**
     * 
     * @return bool
     */
    abstract public function checkFiles();

    /**
     * Check if remot path points to trusted server
     * @return boolean
     */
    final public function isTrustedPath()
    {
        include_once \fpcm\classes\loader::libGetFilePath('spyc/Spyc.php');
        $trusted = \Spyc::YAMLLoad(\fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_CONFIG, 'trustedServers.yml'));

        if (!count($trusted)) {
            return false;
        }

        $remotePath = $this->getRemotePath();
        fpcmLogSystem($remotePath);
        
        foreach ($trusted as $path) {
             if (strpos($remotePath, $path) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Lädt Package in Abhängigkeit von Einstellungen herunter
     * @return boolean
     */
    public function download()
    {
        $remotePath = $this->getRemotePath();

        $remoteHandle = fopen($remotePath, 'rb');

        if (!$remoteHandle) {
            trigger_error('Unable to connect to remote server: ' . $remotePath);
            \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
            return self::REMOTEFILE_ERROR;
        }

        $localPath  = $this->getLocalPath();
        $remoteHandleLocal = fopen($localPath, 'wb');
        if (!$remoteHandleLocal) {
            trigger_error('Unable to open local file: ' . $localPath);
            return self::LOCALFILE_ERROR;
        }

        while (!feof($remoteHandle)) {
            if (fwrite($remoteHandleLocal, fgets($remoteHandle)) === false) {
                trigger_error("Error while writing content of {$remotePath} to {$localPath}.");
                return self::LOCALWRITE_ERROR;
            }           
        }

        fclose($remoteHandle);
        fclose($remoteHandleLocal);

        if (!file_exists($localPath)) {
            trigger_error("Downloaded file not found in {$localPath}.");
            return self::LOCALEXISTS_ERROR;
        }

        return true;
    }
    
    public function checkPackage()
    {
        $hashLocal = $this->getLocalSignature();
        $hashRemote = $this->getRemoteSignature();
        
        if (!trim($hashLocal) || !trim($hashRemote) || $hashLocal !== $hashRemote) {
            trigger_error("Error while checking package signatures, {$hashLocal} does not match {$hashRemote}");
            return self::HASHCHECK_ERROR;
        }

        return true;
    }

    /**
     * Package entpacken
     * @return boolean
     */
    public function extract()
    {
        $localPath  = $this->getLocalPath();

        if ($this->archive->open($localPath) !== true) {
            trigger_error('Unable to open ZIP archive: ' . $localPath);
            return self::ZIPOPEN_ERROR;
        }

        if ($this->archive->extractTo($this->getExtractionPath()) !== true) {
            trigger_error('Unable to extract ZIP archive: ' . $localPath);
            return self::ZIPEXTRACT_ERROR;
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
        return is_array($res) ? self::FILESCOPY_ERROR : $res;
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
        $localPath = $this->getLocalPath();
        if (!file_exists($localPath) || !unlink($localPath)) {
            return false;
        }

        $extractPath = $this->getExtractionPath();
        if (!file_exists($extractPath) || !\fpcm\model\files\ops::deleteRecursive($extractPath)) {
            return false;
        }

        return true;
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
        $path = \fpcm\classes\baseconfig::$updateServer . self::SERVER_PACKAGEPATH . $this->filename;
        $localPath = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, $this->filename);
        $this->extractPath = dirname($localPath) . '/' . md5(basename($localPath, '.zip')) . '/';
        $this->tempListFile = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_TEMP, md5($localPath));
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
        $tempfile = new \fpcm\model\files\tempfile('protocol' . $localPath);
        $tempfile->setContent(base64_encode(json_encode($this->protocol)));
        return $tempfile->save();
    }

}
