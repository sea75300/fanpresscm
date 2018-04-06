<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * Cache system
 * 
 * @package fpcm\classes\cache
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 */
final class cache {

    const CLEAR_ALL = '*';

    /**
     * Crypt Object
     * @var crypt
     */
    private $crypt;

    /**
     * Basis-Pfad für Cache
     * @var string
     */
    private $basePath;

    /**
     * Konstruktor
     * @return void
     */
    public function __construct()
    {
        $this->crypt = loader::getObject('\fpcm\classes\crypt');
        $this->basePath = dirs::getDataDirPath(dirs::DATA_CACHE);
    }

    /**
     * Ist Cache-Inhalt veraltet
     * @return bool
     */
    public function isExpired($cacheName)
    {
        if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE)
            return true;

        $cacheFile = loader::getObject('\fpcm\model\files\cacheFile', $cacheName);
        return $cacheFile->expires() <= time() ? true : false;
    }

    /**
     * Cache-Inhalt schreiben
     * @param mixed $data
     * @param int $expires
     */
    public function write($cacheName, $data, $expires = 0)
    {
        if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) {
            return false;
        }

        $file = new \fpcm\model\files\cacheFile($cacheName);
        return $file->write($data, $expires ? $expires : FPCM_CACHE_DEFAULT_TIMEOUT);
    }

    /**
     * Cache-Inhalt lesen
     * @return string
     */
    public function read($cacheName)
    {
        if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) {
            return false;
        }

        $file = new \fpcm\model\files\cacheFile($cacheName);
        return $file->read();
    }

    /**
     * Cache-Inhalt lesen
     * @return string
     */
    public function getExpirationTime($cacheName)
    {
        if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE)
            return false;

        $file = new \fpcm\model\files\cacheFile($cacheName);
        return $file->expires();
    }

    /**
     * Cachew bereinigen
     * @param string $cacheName
     * @return boolean
     */
    public function cleanup($cacheName = null)
    {
        if ($cacheName !== null && substr($cacheName, -2) !== '/*') {
            $file = new \fpcm\model\files\cacheFile($cacheName);
            return $file->cleanup();
        }

        $cacheFiles = (substr($cacheName, -2) === '/*' ? glob($this->basePath . substr($cacheName, 0, -2) . \fpcm\model\files\cacheFile::EXTENSION_CACHE) : $this->getCacheComplete());
        if (!is_array($cacheFiles) || !count($cacheFiles)) {
            return false;
        }

        foreach ($cacheFiles as $cacheFile) {

            if (!file_exists($cacheFile)) {
                continue;
            }

            unlink($cacheFile);
        }

        return true;
    }

    /**
     * Gibt aktuelle Größe des Caches in byte zurück
     * @return int
     */
    public function getSize()
    {
        return array_sum(array_map('filesize', $this->getCacheComplete()));
    }

    /**
     * Liefert alle *.cache-Dateien in cache-ordner zurück
     * @return array
     * @since FPCM 3.4
     */
    public function getCacheComplete()
    {
        return array_merge(glob($this->basePath . '/*' . \fpcm\model\files\cacheFile::EXTENSION_CACHE), glob($this->basePath . '/*/*' . \fpcm\model\files\cacheFile::EXTENSION_CACHE));
    }

}

?>