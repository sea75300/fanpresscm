<?php
    /**
     * FanPress CM event model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\evets\abstracts;

    /**
     * Event model base
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class event implements \fpcm\model\interfaces\event {

        /**
         * Array returntype für Module-Event
         * @since FPCM 3.4
         */
        const FPCM_MODULE_EVENT_RETURNTYPE_ARRAY = 'array';

        /**
         * Object returntype für Module-Event
         * @since FPCM 3.4
         */
        const FPCM_MODULE_EVENT_RETURNTYPE_OBJ   = 'object';

        /**
         * Base instaces a module event has to implement
         */
        const EVENT_BASE_INSTANCE                = '\\fpcm\\events\\abstracts\\moduleEvent';

        /**
         * Event-Daten
         * @var array
         */
        protected $data;
        
        /**
         * Events mit aktuellem Event
         * @var array
         */
        protected $modules;
        
        /**
         * Liste mit aktiven Modulen
         * @var array
         */
        protected $activeModules = [];
        
        /**
         * Event-Cache
         * @var \fpcm\classes\cache
         */
        protected $cache;
        
        /**
         * Array mit zu prüfenden Berchtigungen
         * @var array
         */
        protected $checkPermission = [];
        
        /**
         * Berechtigungen
         * @var \fpcm\model\system\permissions
         */
        protected $permissions;

        /**
         * Datentyoe-String, welcher von einem Event definiert werden kann und
         *  auf den die Rückgabe eines Module-Events geprüft wird
         * @var string
         */
        protected $returnDataType = false;
        
        /**
         * Konstruktor
         * @param mixed $dataParams
         * @return boolean
         */
        public function __construct($dataParams = null)
        {
            $this->data  = $dataParams;
            $this->cache = loader::getObject('\fpcm\classes\cache');
            
            if (\fpcm\classes\baseconfig::installerEnabled()) {
                return false;
            }

            if (!$this->cache->isExpired('modules/activeeventscache')) {
                $this->activeModules = $this->cache->read('modules/activeeventscache');
                return;
            }

            $this->activeModules = \fpcm\classes\loader::getObject('\fpcm\model\modules\modulelist')->getEnabledInstalledModules();   
            $this->cache->write('modules/activeeventscache', $this->activeModules, FPCM_CACHE_DEFAULT_TIMEOUT);
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
        
        /**
         * Gibt Module-Key anhand des Event-Datei-Pfades zurück
         * @param string $path
         * @return string
         */
        public function getModuleKeyByEvent($path) {
            return \fpcm\model\abstracts\module::getModuleKeyByFolder($path);
        }
        
        /**
         * Prüft ob spezielle Berechtigungen für Event nötig sind
         * @return boolean
         */
        public function checkPermissions() {
            
            if (!$this->permissions || !count($this->checkPermission)) return true;
            
            return $this->permissions->check($this->checkPermission);
        }

        /**
         * Checks if module event class has implemented \fpcm\events\abstracts\moduleEvent
         * @param mixed $object
         * @return boolean
         */
        protected function is_a($object) {
            
            if (is_a($object, self::EVENT_BASE_INSTANCE)) return true;
            
            trigger_error('Event object of class '.  get_class($object).' must be an instance of '.self::EVENT_BASE_INSTANCE.'!');
            return false;
        }
        
        /**
         * Liefert Array mit Event-Klassen in installierten Modulen zurück
         * @return array
         * @since FPCM 3.3
         */
        protected function getEventClasses() {

            if (!count($this->activeModules)) {
                return [];
            }

            $classes = [];
            
            $eventBaseClass = DIRECTORY_SEPARATOR.'events'.DIRECTORY_SEPARATOR.$this->getEventClassBase().'.php';
            foreach ($this->activeModules as $module) {

                $path = \fpcm\classes\dirs::getDataDirPath(\fpcm\classes\dirs::DATA_MODULES, $module, $eventBaseClass);
                if (!file_exists($path)) {
                    continue;
                }
                
                $classes[] = $path;
            }

            return $classes;
        }

        /**
         * Liefert
         * @return string
         */
        protected function getEventClassBase() {
            return str_replace('fpcm\\events\\', '', get_class($this));
        }

        /**
         * Executed a certain events
         * @param array $data
         * @return array
         */
        public function run() {
            
            $eventClasses = $this->getEventClasses();
            
            if (!count($eventClasses)) return $data;
            
            $mdata = $data;
            foreach ($eventClasses as $eventClass) {
                
                $classkey = $this->getModuleKeyByEvent($eventClass);                
                $eventClass = \fpcm\model\abstracts\module::getModuleEventNamespace($classkey, $this->getEventClassBase());
                
                /**
                 * @var \fpcm\model\abstracts\event
                 */
                $module = new $eventClass();

                if (!$this->is_a($module)) continue;
                
                $mdata = $module->run($mdata);
            }

            if (!$mdata) {
                return $data;
            }

            if ($this->returnDataType === self::FPCM_MODULE_EVENT_RETURNTYPE_ARRAY && !is_array($mdata)) {
                trigger_error('Invalid data type. Returned data type must be an array');
                return $data;
            }
            elseif ($this->returnDataType === self::FPCM_MODULE_EVENT_RETURNTYPE_OBJ && !is_object($mdata)) {
                trigger_error('Invalid data type. Returned data type must be an object');
                return $data;
            }
            elseif ($this->returnDataType !== false && !is_array($mdata) && !is_a($mdata, $this->returnDataType) ) {
                trigger_error('Invalid data type. Returned data type must be instance of '.$this->returnDataType);
                return $data;
            }

            return $mdata;
            
        }
    }
