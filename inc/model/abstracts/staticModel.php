<?php
    /**
     * FanPress CM static data model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * Statisches Model ohen DB-Verbindung
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
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
         * @var \fpcm\model\events\eventList 
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
         * @since FPCM 3.6
         */
        protected $notifications;
        
        /**
         * Cache name
         * @var string
         */
        protected $cacheName    = false;
        
        /**
         * Cache Modul
         * @var string
         * @since FPCM 3.4
         */
        protected $cacheModule    = '';

        /**
         * Konstruktor
         * @return void
         */
        public function __construct() {

            $this->events   = \fpcm\classes\baseconfig::$fpcmEvents;
            $this->cache    = new \fpcm\classes\cache($this->cacheName ? $this->cacheName : md5(microtime(false)), $this->cacheModule);            
            
            if (!\fpcm\classes\baseconfig::dbConfigExists()) return;

            $this->session       = \fpcm\classes\baseconfig::$fpcmSession;
            $this->config        = \fpcm\classes\baseconfig::$fpcmConfig;
            $this->language      = \fpcm\classes\baseconfig::$fpcmLanguage;
            $this->notifications = !empty(\fpcm\classes\baseconfig::$fpcmNotifications) ? \fpcm\classes\baseconfig::$fpcmNotifications : null;
            
            if (is_object($this->config)) $this->config->setUserSettings();
        }
        
        /**
         * Magic get
         * @param string $name
         * @return mixed
         */
        public function __get($name) {
            return isset($this->data[$name]) ? $this->data[$name] : false;
        }
        
        /**
         * Magic set
         * @param mixed $name
         * @param mixed $value
         */
        public function __set($name, $value) {
            $this->data[$name] = $value;
        }
        
        /**
         * Magische Methode für nicht vorhandene Methoden
         * @param string $name
         * @param mixed $arguments
         * @return boolean
         */
        public function __call($name, $arguments) {
            print "Function '{$name}' not found in ".get_class($this).'<br>';
            return false;
        }

        /**
         * Magische Methode für nicht vorhandene, statische Methoden
         * @param string $name
         * @param mixed $arguments
         * @return boolean
         */        
        public static function __callStatic($name, $arguments) {
            print "Static function '{$name}' not found in ".get_class($this).'<br>';
            return false;
        }

    }
