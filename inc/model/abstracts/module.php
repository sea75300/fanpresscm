<?php
    /**
     * FanPress CM module base class
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;
    
    /**
     * Module base object
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class module implements \fpcm\model\interfaces\module {

        /**
         * Cache-Modul für Module
         * @since FPCM 3.4
         */
        const FPCM_MODULES_CACHEFOLDER = 'modules';
        
        /**
         * Datenbank-Objekt
         * @var \fpcm\classes\database
         */
        protected $dbcon;
        
        /**
         * Tabelle
         * @var string
         */
        protected $table;
        
        /**
         * Objekt existier
         * @var bool
         */
        protected $objExists = false;
        
        /**
         * Data array
         * @var array
         */
        protected $data;
        
        /**
         * Config-Objekt
         * @var \fpcm\model\system\config
         */
        protected $config;
        
        /**
         * Sprachobjekt
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
         * Module-Key
         * @var string
         */
        protected $modkey        = '';
        
        /**
         * Module-Name/ Beschreibung
         * @var string
         */
        protected $name          = '';
        
        /**
         * lokale Modul-Version
         * @var string
         */
        protected $version       = '';
        
        /**
         * Modul-Version auf Update-Server
         * @var string
         */
        protected $versionRemote = '';
        
        /**
         * ausführliche Modul-Beschreibung
         * @var string
         */
        protected $description   = '';
        
        /**
         * Author des Moduls
         * @var string
         */
        protected $author        = '';
        
        /**
         * Info-Link zu diesem Modul
         * @var string
         */
        protected $link          = '';
        
        /**
         * minimale Version von FPCM, die dieses Modul benötigt
         * @var string
         */
        protected $systemMinVersion = '';

        /**
         * ist Modul aktiv
         * @var bool
         */
        protected $status        = false;
        
        /**
         * ist Modul installiert
         * @var bool
         */
        protected $isInstalled   = false;
        
        /**
         * Abhängigkeiten von anderen Modulen alle erfüllt
         * @var bool
         */
        protected $dependenciesOk   = true;
        
        /**
         * Abhängigkeiten von anderen Modulen
         * @var array
         */
        protected $dependencies     = [];        

        /**
         * Konstruktor
         * @param string $key Modul-Key
         * @param string $name Module-Name
         * @param string $version Modul-Version
         * @param string $versionRemote Server-Version
         * @param string $description Modul-Beschreibung
         * @param string $author Module-Author
         * @param string $link Modul-Info-Link
         * @param string $systemMinVersion minimale System-Version für Modul
         * @param bool $init
         */
        public function __construct($key, $name, $version, $versionRemote = '-', $description = '-', $author = '-', $link = '', $systemMinVersion = '', $init = true) {

            $this->dbcon    = \fpcm\classes\baseconfig::$fpcmDatabase;
            
            if (\fpcm\classes\baseconfig::installerEnabled()) return false;
            
            $this->config        = \fpcm\classes\baseconfig::$fpcmConfig;
            $this->language      = \fpcm\classes\baseconfig::$fpcmLanguage;
            $this->notifications = !empty(\fpcm\classes\baseconfig::$fpcmNotifications) ? \fpcm\classes\baseconfig::$fpcmNotifications : null;
            
            $this->modkey           = $key;
            $this->name             = $name;
            $this->version          = $version;
            $this->versionRemote    = $versionRemote;
            $this->description      = $description;
            $this->author           = $author;
            $this->link             = $link;
            $this->systemMinVersion = $systemMinVersion;            
            $this->table            = \fpcm\classes\database::tableModules;
            
            if ($init) {
                $this->init();                
            }
            
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
         * Magic string
         * @return string
         */
        public function __toString() {
            return $this->getKey();
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
         * Destruktor
         * @return void
         */
        public function __destruct() {
            $this->dbcon    = false;
            $this->data     = null;
            $this->config   = null;
            $this->language = null;
            
            return;
        }
        
        /**
         * Module-Key zurückgeben
         * @return string
         */
        public function getKey() {
            return $this->modkey;
        }

        /**
         * Module-Name zurückgeben
         * @return string
         */
        public function getName() {
            return $this->name ? $this->name : $this->modkey;
        }

        /**
         * lokale Module-Version zurückgeben
         * @return string
         */
        public function getVersion() {
            return $this->version;
        }

        /**
         * remote Module-Version zurückgeben
         * @return string
         */
        public function getVersionRemote() {
            return $this->versionRemote;
        }        
        
        /**
         * Module-Beschreibung zurückgeben
         * @return string
         */        
        public function getDescription() {
            return $this->description;
        }

        /**
         * Module-Author zurückgeben
         * @return string
         */
        public function getAuthor() {
            return $this->author;
        }

        /**
         * Module-Info-Link zurückgeben
         * @return string
         */
        public function getLink() {
            return $this->link;
        }

        /**
         * Gibt aktuellen Status zurück
         * @return bool
         */
        public function getStatus() {
            return (bool) $this->status;
        }
        
        /**
         * Setzt Modul-Status (aktiv/inaktiv)
         * @param bool $status
         */
        public function setStatus($status) {
            $this->status = (bool) $status;
        }        
        
        /**
         * Abhängigkeiten setzen
         * @param array $dependencies
         */
        public function setDependencies(array $dependencies) {
            $this->dependencies = $dependencies;
        }

        /**
         * remote Version setzen
         * @param setzen $versionRemote
         */
        public function setVersionRemote($versionRemote) {
            $this->versionRemote = $versionRemote;
        }
        
        /**
         * Sind Abhängigkeiten erfüllt
         * @return bool
         */
        public function dependenciesOk() {
            if (defined(FPCM_MODULE_IGNORE_DEPENDENCIES) && FPCM_MODULE_IGNORE_DEPENDENCIES) return true;
            
            return (bool) $this->dependenciesOk;
        }        
        
        /**
         * Status ob Modul installiert ist oder nicht
         * @return bool
         */
        public function isInstalled() {
            return (bool) $this->isInstalled;
        }
        
        /**
         * Setzt Installiert-Status
         * @param bool $isInstalled
         */
        public function setIsInstalled($isInstalled) {
            $this->isInstalled = (bool) $isInstalled;
        }
        
        /**
         * Setzt Status, ob Abhänigkeiten erfüllt sind
         * @param bool $dependenciesOk
         */
        public function setDependenciesOk($dependenciesOk) {
            $this->dependenciesOk = (bool) $dependenciesOk;
        }        
        
        /**
         * Prüft, ob für aktuelle Systemsprache ein Sprachpaket vorliegt
         * @return bool
         */
        public function currentLanguageIncluded() {
            return is_dir(\fpcm\classes\baseconfig::$moduleDir.$this->modkey.'/lang/'.$this->config->system_lang);
        }
        
        /**
         * Gibt Inhalt von "data" zurück
         * @return array
         */
        public function getData() {
            return $this->data;
        }
        
        /**
         * Module deaktivieren
         * @return bool
         */
        final public function disable() {
            return $this->dbcon->reverseBool($this->table, 'status', "modkey ".$this->dbcon->dbLike()." ? and status = 1", array($this->modkey));
        }
        
        /**
         * Module aktivieren
         * @return bool
         */
        final public function enable() {
            $this->dbcon->reverseBool($this->table, 'status', "modkey ".$this->dbcon->dbLike()." ? and status = 0", array($this->modkey));
        }

        /**
         * Config-Dateien in yml-Format auslesen
         * @param string $filename
         * @return array
         */
        public function getConfig($filename) {
            
            $path = \fpcm\classes\baseconfig::$moduleDir.$this->modkey.'/config/'.$filename.'.yml';
            
            if (!file_exists($path)) return [];
            
            include_once \fpcm\classes\loader::libGetFilePath('spyc', 'Spyc.php');
            return \Spyc::YAMLLoad($path);            
        }
        
        /**
         * Gibt Abhängigkeiten zurück, falls vorhanden
         * @return array
         */
        public function getDependencies() {
            
            if (defined('FPCM_MODULE_IGNORE_DEPENDENCIES') && FPCM_MODULE_IGNORE_DEPENDENCIES) return [];
            
            if (count($this->dependencies)) return $this->dependencies;
            
            return $this->getConfig('dependencies');
        }

        /**
         * Inittiert Objekt mit Daten aus der Datenbank, sofern ID vergeben wurde
         */
        protected function init() {
            $data = $this->dbcon->fetch($this->dbcon->select($this->table, '*', 'modkey = ?', array($this->modkey)));
            if (!$data) {
                return false;
            }

            $this->objExists    = true;
            $this->isInstalled  = true;
            
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
        
        /**
         * Gibt Klassename inkl. Namespace für ein Modul zurück,
         * / in Modulkey werden in \ ersetzt
         * @param string $key Modul-Key
         * @return string
         */
        public static function getModuleClassName($key) {
            return "\\fpcm\\modules\\".str_replace(DIRECTORY_SEPARATOR, '\\', $key)."\\".str_replace(array('\\', '/'), '', $key);
        }
        
        /**
         * Gibt Klassename inkl. Namespace für ein Modul zurück,
         * / in Modulkey werden in \ ersetzt
         * @param string $key Modul-Key
         * @param string $event Event-Klassen-Name
         * @return string
         */
        public static function getModuleEventNamespace($key, $event) {
            return "\\fpcm\\modules\\".str_replace(DIRECTORY_SEPARATOR, '\\', $key)."\\events\\{$event}";
        }
        
        /**
         * Gibt Module-Key anhand von Ordnerstruktur zurück
         * @param string $folder Pfad einer Datei
         * @return string
         */
        public static function getModuleKeyByFolder($folder) {

            $folder = explode('/', str_replace(\fpcm\classes\baseconfig::$moduleDir, '', $folder));
            
            if (count($folder) > 2) {
                $folder = array($folder[0], (isset($folder[1]) && trim($folder[1]) ? $folder[1] : ''));
            }
            
            return implode('/', $folder);
            
        }
    }