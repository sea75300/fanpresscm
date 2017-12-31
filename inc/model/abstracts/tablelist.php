<?php
    /**
     * FanPress CM table model object
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.2.0
     */
    namespace fpcm\model\abstracts;

    /**
     * Table model object
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.2.0
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
         * @var \fpcm\model\events\eventList 
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
         * @param int $id
         * @return void
         */
        public function __construct() {
 
            $this->dbcon    = \fpcm\classes\baseconfig::$fpcmDatabase;
            $this->events   = \fpcm\classes\baseconfig::$fpcmEvents;
            $this->cache    = new \fpcm\classes\cache($this->cacheName ? $this->cacheName : md5(microtime(false)), $this->cacheModule);

            if (\fpcm\classes\baseconfig::installerEnabled()) return false;
            
            $this->config        = \fpcm\classes\baseconfig::$fpcmConfig;
            $this->language      = \fpcm\classes\baseconfig::$fpcmLanguage;
            $this->notifications = !empty(\fpcm\classes\baseconfig::$fpcmNotifications) ? \fpcm\classes\baseconfig::$fpcmNotifications : null;

            if (is_object($this->config)) {
                $this->config->setUserSettings();
            }

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

        /**
         * Konstruktor
         * @return void
         */
        public function __destruct() {
            $this->dbcon  = false;
            $this->data   = null;
            $this->cache  = null;
            $this->events = null;
            
            return;
        }

    }
