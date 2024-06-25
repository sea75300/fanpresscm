<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cache;

/**
 * Cache file objekt
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\model\cache
 * @since 5.1-dev
 */
class memcacheBackend implements \fpcm\model\interfaces\cacheBackend {

    /**
     *
     * @var memcacheConnector
     */
    private $memcache;

    /**
     *
     * @var Cache file expiration time
     */
    private $expires;

    /**
     * Full cache file path
     * @var string
     */
    private $path;

    /**
     * Cache file module
     * @var string
     */
    private $module;

    /**
     * Konstruktor
     * @param string $cacheName
     */
    public function __construct(?string $cacheName)
    {
        if ($cacheName === null) {
            $cacheName = '';
        }

        $cacheName = explode('/', strtolower($cacheName), 2);

        $this->module = isset($cacheName[1]) && trim($cacheName[1]) ? $cacheName[0] : '';
        $this->path = $cacheName[1] ?? $cacheName[0];
        $this->path = $this->module . '/'. $this->path;

        $this->memcache = \fpcm\classes\loader::getObject('\fpcm\model\cache\memcacheConnector');
    }

    /**
     * Write content to cache file
     * @param mixed $data
     * @param integer $expires
     * @return bool
     */
    public function write($data, int $expires)
    {
        if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) {
            return false;
        }

        $this->expires = time() + $expires;

        $r = $this->memcache->getInstance()->set($this->path, [
            'expires' => $this->expires,
            'data' => $data
        ], $expires);

        if (!$r) {
            trigger_error(sprintf('Unable to write cache data for key %s with code %s', $this->path, $r));
            return false;
        }

        return true;
    }

    /**
     * Read cache file content
     * @param bool $raw
     * @return mixed|null
     */
    public function read($raw = false)
    {
        $return = $this->memcache->getInstance()->get($this->path);
        return $raw ? $return : ($return['data'] ?? null);
    }

    /**
     * Fetch expiration time
     * @return mixed|null
     */
    public function expires()
    {
        $data = $this->read(true);

        if (!$data) {
            return 0;
        }

        $this->expires = $data['expires'] ?? 0;
        return $this->expires;
    }

    /**
     * Cleanup cache file
     * @return bool
     */
    public function cleanup()
    {
        if ($this->memcache === null) {
            return true;
        }

        return $this->memcache->getInstance()->flush();
    }

    /**
     * Prepare data
     * @param mixed $value
     * @return mixed
     */
    public function prepareReturnedValue(mixed $value): mixed
    {
        return $value;
    }

    /**
     * Get cache size
     * @return int
     */
    public function getSize(string $basePath): int
    {
        if ($this->memcache === null) {
            return 0;
        }

        return (int) $this->memcache->getStats('bytes');
    }

    /**
     *
     * @param string $basePath
     * @param type $cacheName
     * @return bool
     */
    public static function cleanupByCacheName(string $basePath, $cacheName = null): bool
    {
        /* @var $mem \Memcached */
        $mem = \fpcm\classes\loader::getObject('\fpcm\model\cache\memcacheConnector')->getInstance();
        if ($cacheName === null) {
            $r = $mem->flush();
            if (!$r) {
                trigger_error(sprintf('Unable to clear cache %s with code %s: %s', $cacheName, $mem->getResultCode(), $mem->getResultMessage()));
                return false;
            }
            
            return true;
        }

        if (substr($cacheName, -1) !== \fpcm\classes\cache::CLEAR_ALL) {
            $r = $mem->delete( $cacheName );
            if (!$r) {
                trigger_error(sprintf('Unable to clear cache %s with code %s: %s', $cacheName, $mem->getResultCode(), $mem->getResultMessage()));
                return false;
            }            
        }

        $mod = strtolower(substr($cacheName, 0, -2));

        $all = $mem->getAllKeys();
        if (!is_array($all) || !count($all)) {
            return true;
        }

        $filtered = array_filter( $all, function ($val) use ($mod) {
            return str_starts_with($val, $mod);
        });

        if (!is_array($filtered) || !count($filtered)) {
            return true;
        }

        $mem->deleteMulti($filtered);
        return true;
    }

}