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
    abstract protected function getPackageKey();

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
     * 
     * @return bool
     */
    abstract public function copy();

    /**
     * 
     * @return bool
     */
    abstract public function updateLog();

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

    /**
     * 
     * @return boolean
     */
    public function checkPackage()
    {
        $hashLocal = $this->getLocalSignature();
        $hashRemote = $this->getRemoteSignature();

        if (!trim($hashLocal) || !trim($hashRemote)) {
            trigger_error("Error while checking package signatures, no signature given.");
            return self::HASHCHECK_ERROR;
        }

        $keyPath = $this->getPackageKey();
        if (!$keyPath || !file_exists($keyPath)) {
            trigger_error("Error while checking package signatures, no package key found.");
            return self::HASHCHECK_ERROR;
        }

        if (openssl_verify($hashLocal, base64_decode($hashRemote), file_get_contents($keyPath), OPENSSL_ALGO_SHA512) !== 1) {
            trigger_error("Verification of package signature failed!");
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
     * Performs clean of update files and cache
     * @return boolean
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
     * Ersetzt "fanpress"-Ordnername durch basedir-Daen in einem Pfad
     * @param string $path
     * @return string
     */
    protected function replaceFanPressBaseFolder($path)
    {
        return str_replace('fanpress/', $this->getLocalDestinationPath(), $path);
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
     * Fetch array of files from files.txt file
     * @param string $path
     * @return array
     */
    protected function getFileList($path, $start = 0)
    {
        if (!trim($path) || !file_exists($path)) {
            trigger_error($path.' was not found on expected path. This is an unexpected behaviour and should NOT be happen. You should contact the developer for further help.');
            return [];
        }

        $files = file($path, FILE_IGNORE_NEW_LINES);
        if (!count($files)) {
            return [];
        }

        return array_slice($files, $start, -2);
    }
}
