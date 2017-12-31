<?php
    /**
     * FanPress CM module event model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * Module event basis
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class moduleEvent implements \fpcm\model\interfaces\event {

        /**
         * data Array
         * @var array
         */
        protected $data;
        
        /**
         * Sprachobjekt
         * @var \fpcm\classes\language
         */
        protected $lang;
        
        /**
         * Config-Objekt
         * @var \fpcm\model\system\config
         */
        protected $config;

        /**
         * Notifications
         * @var \fpcm\model\theme\notifications
         * @since FPCM 3.6
         */
        protected $notifications;
        
        /**
         * Konstruktor
         * @return boolean
         */
        public function __construct() {
            
            if (\fpcm\classes\baseconfig::installerEnabled()) return false;
            
            $this->config        = \fpcm\classes\baseconfig::$fpcmConfig;
            $this->lang          = \fpcm\classes\baseconfig::$fpcmLanguage;
            $this->notifications = \fpcm\classes\baseconfig::$fpcmNotifications;
            
            $this->config->setUserSettings();
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
