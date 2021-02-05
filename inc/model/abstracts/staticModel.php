<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

use fpcm\classes\baseconfig;
use fpcm\classes\loader;

/**
 * Statisches Model ohen DB-Verbindung
 * 
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class staticModel {

    /**
     * Data array
     * @var array
     */
    protected $data;

    /**
     * Cache object
     * @var \fpcm\classes\cache
     */
    protected $cache;

    /**
     * Event list
     * @var \fpcm\events\events 
     */
    protected $events;

    /**
     * Config object
     * @var \fpcm\model\system\config
     */
    protected $config;

    /**
     * Sprachobjekt
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     * Session objekt
     * @var \fpcm\model\system\session
     */
    protected $session;

    /**
     * Notifications
     * @var \fpcm\model\theme\notifications
     * @since 3.6
     */
    protected $notifications;

    /**
     * Permissions
     * @var \fpcm\model\permissions\permissions
     * @since 4
     */
    protected $permissions;

    /**
     * Cache name
     * @var string
     */
    protected $cacheName = false;

    /**
     * Cache Modul
     * @var string
     * @since 3.4
     */
    protected $cacheModule = '';

    /**
     * Konstruktor
     * @return void
     */
    public function __construct()
    {
        $this->events = loader::getObject('\fpcm\events\events');
        $this->cache = loader::getObject('\fpcm\classes\cache');

        if (!baseconfig::dbConfigExists()) {
            return;
        }

        $this->session = loader::getObject('\fpcm\model\system\session');
        $this->config = loader::getObject('\fpcm\model\system\config');
        $this->language = loader::getObject('\fpcm\classes\language');
        $this->notifications = loader::getObject('\fpcm\model\theme\notifications');

        if (is_object($this->config)) {
            $this->config->setUserSettings();
        }

        $this->permissions = loader::getObject('\fpcm\model\permissions\permissions');
        
        if (method_exists($this, 'init')) {
            $this->init();
        }

    }

    /**
     * Magic get
     * @param string $name
     * @return mixed
     * @ignore
     */
    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }

    /**
     * Magic set
     * @param mixed $name
     * @param mixed $value
     * @ignore
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
    
    /**
     * Cache-Name zurückgeben
     * @param string $addName
     * @return string
     */
    public function getCacheName($addName = '')
    {
        return $this->cacheModule . '' . $this->cacheName . $addName;
    }

}
