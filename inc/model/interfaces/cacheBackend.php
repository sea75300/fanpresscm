<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * Cache backend interface
 * 
 * @package fpcm\model\interfaces
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
interface cacheBackend {

    /**
     * Konstruktor
     * @param string $cacheName
     */
    public function __construct($cacheName);

    /**
     * Write content to cache file
     * @param mixed $data
     * @param integer $expires
     * @return bool
     */
    public function write($data, $expires);

    /**
     * Read cache file content
     * @param bool $raw
     * @return mixed|null
     */
    public function read($raw = false);

    /**
     * Fetch expiration time
     * @return mixed|null
     */
    public function expires();

    /**
     * Cleanup cache file
     * @return bool
     */
    public function cleanup();

    /**
     * Returns all *.cache files from fielsystem
     * @return array
     * @since 5.1-dev
     */
    public static function getCacheComplete(string $basePath) : array;

    /**
     * Cleanup cache by cache name in base path
     * @param string $basePath
     * @param type $cacheName
     * @return bool
     * @since 5.1-dev
     */
    public static function cleanupByCacheName(string $basePath, $cacheName = null) : bool;

}
