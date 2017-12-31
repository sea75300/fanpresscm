<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\classes;

    /**
     * Cache system
     * 
     * @package fpcm\classes\cache
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     */ 
    final class cache {
        
        /**
         * kodierter Cache-Datei-Name als MD5-Hash
         * @var string
         */
        private $cacheName;
        
        /**
         * Cache-Datei-Pfad inkl. Name
         * @var string
         */
        private $fileName;

        /**
         * Cache-Inhalt
         * @var mixed
         */
        private $data;
        
        /**
         * Zeitpunkt des Verfalls
         * @var int
         */
        private $expirationTime;

        /**
         * Status-Flag, ob Cache-Modul angegeben wurde doer nicht
         * @var bool
         * @since FPCM 3.4
         */
        private $hasModule = false;

        /**
         * Crypt Object
         * @var crypt
         * @since FPCM 3.5
         */
        private $crypt = false;

        /**
         * Der Konstruktur
         * @param string $cacheName
         * @param string $module
         */
        public function __construct($cacheName = null, $module = '') {

            $this->cacheName = $this->initCacheName($cacheName);
            $this->crypt     = new crypt();

            if (is_null($this->cacheName)) {
                return;
            }

            $this->fileName = baseconfig::$cacheDir.$this->initCacheModule($module).$this->cacheName.'.cache';

            if (!file_exists($this->fileName)) {
                return false;
            }

            $data = unserialize(base64_decode(file_get_contents($this->fileName)));
            $this->data = $data['data'];
            $this->expirationTime = $data['expires'];
        }
        
        /**
         * Gibt Dateiname von aktuellem Cache zurück
         * @return string
         */
        public function getCacheFileName() {
            return $this->fileName;
        }

        /**
         * Ist Cache-Inhalt veraltet
         * @return bool
         */
        public function isExpired() {
            if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) return true;
            
            return ($this->expirationTime <= time() || !file_exists($this->fileName)) ?  true : false;
        }

        /**
         * Cache-Inhalt schreiben
         * @param mixed $data
         * @param int $expires
         */
        public function write($data, $expires = 0) {
            if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) return false;

            $parent = dirname($this->fileName);
            if ($this->hasModule && !is_dir($parent) && !mkdir($parent)) {
                trigger_error('Unable to create cache subdirectory in '.\fpcm\model\files\ops::removeBaseDir($parent, true));
                return false;
            }

            if (!is_null($this->fileName)) {
                file_put_contents($this->fileName, base64_encode(serialize(array('data' => $data,'expires' => time() + $expires))));
            }
        }
        
        /**
         * Cache-Inhalt lesen
         * @return string
         */
        public function read() {

            if (!is_null($this->fileName) && file_exists($this->fileName)) { 
                $data = unserialize(base64_decode(file_get_contents($this->fileName)));
                return $data['data'];
            }
            
            return '';
        }
        
        /**
         * Cache-Inhalt leeren
         * @param string $path
         * @param string $module
         * @return bool
         */
        public function cleanup($path = false, $module = '') {

            $cacheBaseDir = baseconfig::$cacheDir.$this->initCacheModule($module);
            
            if ($path) {
                $cacheFiles = glob($cacheBaseDir.$this->initCacheName($path).'.cache');
            }
            elseif ($module) {
                $cacheFiles = glob($cacheBaseDir.'*.cache');
            }
            else {
                $cacheFiles = $this->getCacheComplete();
            }

            if (!is_array($cacheFiles) || !count($cacheFiles)) return false;
            

            foreach ($cacheFiles as $cacheFile) {

                if (!file_exists($cacheFile)) {
                    continue;
                }

                unlink($cacheFile);
            }
 
            return true;
        }
        
        /**
         * Gibt aktuelle Größe des Caches in byte zurück
         * @return int
         */
        public function getSize() {
            return array_sum(array_map('filesize', $this->getCacheComplete()));
        }

        /**
         * Gibt Zeitspanne zurück, bis Cache verfällt
         * @return int
         * @since FPCM 3.3
         */
        public function getExpirationTime() {
            return $this->expirationTime;
        }

        /**
         * Liefert alle *cache-Dateien in cache-ordner zurück
         * @return array
         * @since FPCM 3.4
         */
        public function getCacheComplete() {
            return array_merge(glob(baseconfig::$cacheDir.'*.cache'), glob(baseconfig::$cacheDir.'*/*.cache'));
        }

                /**
         * Cache-Name verschlüsseln
         * @param string $cacheName
         * @return string
         */
        protected function initCacheName($cacheName) {
            
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
        protected function initCacheModule($module) {
            
            if (!trim($module)) return '';

            $this->hasModule = true;

            if (defined('FPCM_CACHEMODULE_DEBUG') && FPCM_CACHEMODULE_DEBUG) {
                return strtolower($module).'/';
            }
            
            return md5(strtolower($module)).'/';
        }

    }

?>