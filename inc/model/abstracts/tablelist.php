<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * Table model object
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.2.0
 * @package fpcm\model\abstracts
 * @abstract
 */
abstract class tablelist {

    /**
     * DB-Verbindung
     * @var \fpcm\classes\database
     */
    protected $dbcon;

    /**
     * Tabellen-Name
     * @var string
     */
    protected $table;

    /**
     * System-Cache
     * @var \fpcm\classes\cache
     */
    protected $cache;

    /**
     * Event-Liste
     * @var \fpcm\events\events 
     */
    protected $events;

    /**
     * System-Config-Objekt
     * @var \fpcm\model\system\config
     */
    protected $config;

    /**
     * System-Sprachen-Objekt
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     * Notifications
     * @var \fpcm\model\theme\notifications
     * @since 3.6
     */
    protected $notifications;

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
     * Data array
     * @var array
     * @since 4.1
     */
    protected $data = [];

    /**
     * Konstruktor
     * @param int $id
     * @return void
     */
    public function __construct()
    {
        $this->dbcon = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        $this->events = \fpcm\classes\loader::getObject('\fpcm\events\events');
        $this->cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');

        if (\fpcm\classes\baseconfig::installerEnabled()) {
            return false;
        }

        $this->config = \fpcm\classes\loader::getObject('\fpcm\model\system\config');
        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language', $this->config->system_lang);
        $this->notifications = \fpcm\classes\loader::getObject('\fpcm\model\theme\notifications');

        if (is_object($this->config)) {
            $this->config->setUserSettings();
        }

        return true;
    }

    /**
     * Konstruktor
     * @return void
     */
    public function __destruct()
    {
        $this->dbcon = false;
        $this->cache = null;
        $this->events = null;

        return;
    }

}
