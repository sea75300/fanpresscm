<?php
    /**
     * Image file object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Cache file objekt
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class cacheFile {

        const EXTENSION_CACHE  = '.cache';
        
        private $path;

        private $module;
        
        private $expires = null;

        public function __construct($cacheName)
        {
            $cacheName      = explode('/', $cacheName, 2);

            $this->module   = isset($cacheName[1]) && trim($cacheName[1]) ? $cacheName[0] : '';
            
            $this->path     = \fpcm\classes\dirs::getDataDirPath(
                $this->getType(),
                $this->initCacheModule($this->module).
                $this->initCacheName($this->module ? $cacheName[1] : $cacheName[0])
            ).$this->getExt();

        }

        /**
         * Inhalt in Cache-Datei schreiben
         * @param mixed $data
         * @param integer $expires
         * @return boolean
         */
        public function write($data, $expires)
        {   
            if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) return false;

            $parent = dirname($this->path);
            if ($this->module && !is_dir($parent) && !mkdir($parent)) {
                trigger_error('Unable to create cache subdirectory in '.ops::removeBaseDir($parent, true));
                return false;
            }
            
            $this->expires  = time() + $expires;
            
            if (is_object($data) || is_array($data)) {
                $data = serialize($data);
            }

            $data = [
                'expires'   => $this->expires,
                'data'      => $data
            ];

            if (!file_put_contents($this->path, json_encode($data))) {
                trigger_error('Unable to write cache file '.ops::removeBaseDir($this->path, true));
                return false;
            }
            
            return true;
        }
        
        /**
         * Inhalt aus Cache-Datei lesen
         * @return mixed|null
         */
        public function read($raw = false)
        {
            if (file_exists($this->path)) { 
                $return = json_decode(file_get_contents($this->path));
                return $raw ? $return : (isset($return->data) ? $return->data : null);                
            }
            
            return null;
        }
        
        /**
         * Ablaufzeit aus Cache-Datei lesen
         * @return mixed|null
         */
        public function expires()
        {
            if (file_exists($this->path)) { 
                return 0;
            }

            $data           = $this->read(true);
            $this->expires  = isset($data->expires) ? $data->expires : 0;
            return $this->expires;
        }

        /**
         * Cache-Datei bereinigen
         * @return boolean
         */
        public function cleanup()
        {
            if (!file_exists($this->path)) {
                return true;
            }

            return unlink($this->path);
        }

        /**
         * Cache-Name verschlüsseln
         * @param string $cacheName
         * @return string
         */
        protected function initCacheName($cacheName)
        {            
            if ($cacheName === null) return null;

            if (defined('FPCM_CACHE_DEBUG') && FPCM_CACHE_DEBUG) {
                return strtolower($cacheName);
            }
            
            return md5(strtolower($cacheName));
        }
        
        /**
         * Cache-Modul verschlüsseln
         * @param string $module
         * @return string
         * @since FPCM 3.4
         */
        protected function initCacheModule($module)
        {            
            if (!trim($module)) return '';

            if (defined('FPCM_CACHEMODULE_DEBUG') && FPCM_CACHEMODULE_DEBUG) {
                return strtolower($module).DIRECTORY_SEPARATOR;
            }
            
            return md5(strtolower($module)).DIRECTORY_SEPARATOR;
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
        
    }
?>