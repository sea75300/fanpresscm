<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * Cache system
 * 
 * @package fpcm\classes\cache
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
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

        if (!isset($GLOBALS['fpcm']['stack'])) {
            $GLOBALS['fpcm']['stack'] = [];
        }
    }

    /**
     * Ist Cache-Inhalt veraltet
     * @param string $cacheName Cache-Name
     * @return bool
     */
    public function isExpired($cacheName)
    {
        if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) {
            return true;
        }

        return $this->getBackendInstance($cacheName)->expires() <= time() ? true : false;
    }

    /**
     * Cache-Inhalt schreiben
     * @param string $cacheName Cache-Name
     * @param mixed $data
     * @param int $expires
     * @return bool
     */
    public function write($cacheName, $data, $expires = 0)
    {
        if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) {
            return false;
        }

        return $this->getBackendInstance($cacheName)->write($data, $expires ? $expires : FPCM_CACHE_DEFAULT_TIMEOUT);
    }

    /**
     * Cache-Inhalt lesen
     * @param string $cacheName Cache-Name
     * @return string
     * @return mixed
     */
    public function read($cacheName)
    {
        if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) {
            return false;
        }

        $obj = $this->getBackendInstance($cacheName);
        return $obj->prepareReturnedValue( $obj->read() );
    }

    /**
     * Cache-Inhalt lesen
     * @param string $cacheName Cache-Name
     * @return string
     */
    public function getExpirationTime($cacheName)
    {
        if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) {
            return false;
        }

        return $this->getBackendInstance($cacheName)->expires();
    }

    /**
     * Cachew bereinigen
     * @param string $cacheName
     * @return bool
     */
    public function cleanup($cacheName = null)
    {        
        return call_user_func(FPCM_CACHE_BACKEND . '::cleanupByCacheName', $this->basePath, $cacheName);
    }

    /**
     * Gibt aktuelle Größe des Caches in byte zurück
     * @return int
     */
    public function getSize()
    {        
        return $this->getBackendInstance(null)->getSize($this->basePath);
    }

    /**
     * Create cache backend object instance
     * @param string $cacheName
     * @return \fpcm\model\interfaces\cacheBackend
     */
    private function getBackendInstance(?string $cacheName)
    {
        $cbe = FPCM_CACHE_BACKEND;
        return new $cbe($cacheName);
    }

    /**
     * Get cache backend name
     * @return string
     * @since 5.1-dev
     */
    public static function getCacheBackendName() : string
    {
        return strtoupper(preg_replace('/(.*\\\\)(.*)/', 'SYSTEM_OPTIONS_SYSCHECK_CACHE_$2', FPCM_CACHE_BACKEND));
    }

}
