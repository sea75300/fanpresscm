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
    public function __construct(string $cacheName)
    {
        $cacheName = explode('/', $cacheName, 2);
        
        $this->module = isset($cacheName[1]) && trim($cacheName[1]) ? $cacheName[0] : '';
        $this->path = $cacheName[1] ?? $cacheName[0];

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

        if (is_object($data) || is_array($data)) {
            $data = serialize($data);
        }
        
        $this->expires = time() + $expires;

        $r = $this->memcache->getInstance()->set($this->path, [
            'expires' => $this->expires,
            'data' => $data
        ]);
        
        fpcmLogSystem([__METHOD__, $this->path, $this->memcache->getInstance()->getResultCode(), $this->memcache->getInstance()->getResultMessage()]);
        
        if (!$r) {
            trigger_error('Unable to write cache data for key ' . $this->path);
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
        
        fpcmLogSystem([__METHOD__, $this->path, $this->memcache->getInstance()->getResultCode(), $this->memcache->getInstance()->getResultMessage()]);
        
        return $raw ? $return : ($return->data ?? null);
    }

    /**
     * Fetch expiration time
     * @return mixed|null
     */
    public function expires()
    {
        $item = $this->memcache->getInstance()->get($this->path);
        
        if (!$item) {
            return 0;
        }

        $data = $this->read(true);
        $this->expires = $data->expires ?? 0;
        return $this->expires;
    }

    /**
     * Cleanup cache file
     * @return bool
     */
    public function cleanup()
    {
        $this->memcache->getInstance()->flush();
        return true;
    }

    public static function cleanupByCacheName(string $basePath, $cacheName = null): bool
    {
        return true;
    }

    public static function getCacheComplete(string $basePath): array
    {
        $this->memcache->getInstance()->getAllKeys();
        
        return [];
    }

}