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
 */
class fsBackend implements \fpcm\model\interfaces\cacheBackend {

    const EXTENSION_CACHE = '.cache';

    /**
     * fulle cache file path
     * @var string
     */
    private $path;

    /**
     *
     * @var Cache file module
     */
    private $module;

    /**
     *
     * @var Cache file expiration time
     */
    private $expires = null;

    /**
     * Konstruktor
     * @param string $cacheName
     */
    public function __construct(string $cacheName)
    {
        $cacheName = explode('/', $cacheName, 2);

        $this->module = isset($cacheName[1]) && trim($cacheName[1]) ? $cacheName[0] : '';

        $this->path = \fpcm\classes\dirs::getDataDirPath(
                $this->getType(), $this->initCacheModule($this->module) .
                $this->initCacheName($this->module ? $cacheName[1] : $cacheName[0])
        ) . $this->getExt();
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

        $parent = dirname($this->path);
        if ($this->module && !is_dir($parent) && !mkdir($parent)) {
            trigger_error('Unable to create cache subdirectory in ' . ops::removeBaseDir($parent, true));
            return false;
        }

        $this->expires = time() + $expires;

        if (is_object($data) || is_array($data)) {
            $data = serialize($data);
        }

        $data = [
            'expires' => $this->expires,
            'data' => $data
        ];

        if (!file_put_contents($this->path, json_encode($data))) {
            trigger_error('Unable to write cache file ' . ops::removeBaseDir($this->path, true));
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
        if (file_exists($this->path)) {
            $return = json_decode(file_get_contents($this->path));
            return $raw ? $return : ($return->data ?? null);
        }

        return null;
    }

    /**
     * Fetch expiration time
     * @return mixed|null
     */
    public function expires()
    {
        if (!file_exists($this->path)) {
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
        clearstatcache();
        if (!file_exists($this->path)) {
            return true;
        }

        return unlink($this->path);
    }

    /**
     * Initialize cache name
     * @param string $cacheName
     * @return string
     */
    protected function initCacheName($cacheName)
    {
        if ($cacheName === null)
            return null;

        if (defined('FPCM_CACHE_DEBUG') && FPCM_CACHE_DEBUG) {
            return strtolower($cacheName);
        }

        return md5(strtolower($cacheName));
    }

    /**
     * Initialize cache module name
     * @param string $module
     * @return string
     * @since 3.4
     */
    protected function initCacheModule($module)
    {
        if (!trim($module))
            return '';

        if (defined('FPCM_CACHEMODULE_DEBUG') && FPCM_CACHEMODULE_DEBUG) {
            return strtolower($module) . DIRECTORY_SEPARATOR;
        }

        return md5(strtolower($module)) . DIRECTORY_SEPARATOR;
    }

    /**
     * Return extension for cache file
     * @return string
     */
    protected function getExt()
    {
        return self::EXTENSION_CACHE;
    }

    /**
     * Return path type
     * @return string
     */
    protected function getType()
    {
        return \fpcm\classes\dirs::DATA_CACHE;
    }

    /**
     * Returns all *.cache files from fielsystem
     * @return array
     * @since 5.1-dev
     */
    public static function getCacheComplete(string $basePath) : array
    {
        return array_unique(array_merge_recursive(glob($basePath . '/*' . self::EXTENSION_CACHE), glob($basePath . '/*/*' . self::EXTENSION_CACHE)));
    }

    /**
     * Cleanup cache by cache name in base path
     * @param string $basePath
     * @param type $cacheName
     * @return bool
     * @since 5.1-dev
     */
    public static function cleanupByCacheName(string $basePath, $cacheName = null) : bool
    {
        if ($cacheName === null) {
            $cacheFiles = self::getCacheComplete($basePath);
        }
        elseif (substr($cacheName, -1) !== \fpcm\classes\cache::CLEAR_ALL) {
            $file = new \fpcm\model\cache\fsBackend($cacheName);
            return $file->cleanup();
        }
        else {
            $cacheName = strtolower(substr($cacheName, 0, -2));
            if (!defined('FPCM_CACHEMODULE_DEBUG') || !FPCM_CACHEMODULE_DEBUG) {
                $cacheName = md5($cacheName);
            }

            $cacheFiles = glob($basePath . DIRECTORY_SEPARATOR . $cacheName . DIRECTORY_SEPARATOR . '*' . self::EXTENSION_CACHE);            
        }
        
        if (defined('FPCM_CACHELIST_DEBUG') && FPCM_CACHELIST_DEBUG) {
            fpcmLogSystem($cacheFiles);
        }

        if (!is_array($cacheFiles) || !count($cacheFiles)) {
            return false;
        }

        $cacheFiles = array_filter($cacheFiles, function ($cacheFile) {
            return file_exists($cacheFile) && is_writable($cacheFile);            
        });

        if (!count($cacheFiles)) {
            return true;
        }
        
        array_map('unlink', $cacheFiles);
        return true;
    }

}
