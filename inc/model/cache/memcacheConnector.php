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
class memcacheConnector {

    /**
     * Memcache object instance
     * @var \Memcached
     */
    private $obj;

    /**
     * Host string
     * @var string
     */
    private $host;

    /**
     * Port digit
     * @var int
     */
    private $port;

    /**
     * Constructor
     * @param string $cacheName
     */
    public function __construct()
    {
        $this->host = defined('FPCM_MEMCACHE_HOST') ? FPCM_MEMCACHE_HOST : '127.0.0.1';
        $this->port = defined('FPCM_MEMCACHE_PORT') ? FPCM_MEMCACHE_PORT : 11211;
    }

    /**
     * 
     * @return \Memcache|null
     */
    final public function getInstance() : \Memcached
    {
        
        if ($this->obj instanceof \Memcached) {
            return $this->obj;
        }

        try {
            
            $this->obj = new \Memcached();

            $servers = $this->obj->getServerList();
            if(!count($servers) || !isset($servers[0]) || !isset($servers[0]['host']) || $servers[0]['host'] !== $this->host) {
                $sec = $this->obj->addServer($this->host, $this->port);
            }
            
        } catch (\Exception $e) {
            return null;
        }

        if (!$sec) {
            trigger_error(sprintf('Unable to connect to memcache at %s:%s!', $this->host, $this->port));
        }

        return $this->obj;
    }

    /**
     * 
     * @param string $var
     * @return mixed
     */
    final public function getStats(?string $var): mixed
    {
        $stats = $this->obj->getStats();

        if (isset($stats[$this->host.':'.$this->port][$var])) {
            return $stats[$this->host.':'.$this->port][$var];
        }
        
        if ($var === null) {
            return $stats[$this->host.':'.$this->port];
        }

        return null;
    }

}