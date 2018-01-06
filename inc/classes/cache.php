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
         * Crypt Object
         * @var crypt
         */
        private $crypt;

        /**
         * Konstruktor
         * @return void
         */
        public function __construct() {
            $this->crypt     = loader::getObject('crypt');
        }

        /**
         * Ist Cache-Inhalt veraltet
         * @return bool
         */
        public function isExpired($cacheName) {

            if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) return true;

            $cacheFile = loader::getObject('\fpcm\model\files\cacheFile', $cacheName);
            return $cacheFile->expires() <= time() ?  true : false;
        }

        /**
         * Cache-Inhalt schreiben
         * @param mixed $data
         * @param int $expires
         */
        public function write($cacheName, $data, $expires = 0)
        {
            if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) return false;
            
            $cacheFile = loader::getObject('\fpcm\model\files\cacheFile', $cacheName);
            return $cacheFile->write($data, $expires ? $expires : FPCM_CACHE_DEFAULT_TIMEOUT);
        }
        
        /**
         * Cache-Inhalt lesen
         * @return string
         */
        public function read($cacheName)
        {
            if (defined('FPCM_INSTALLER_NOCACHE') && FPCM_INSTALLER_NOCACHE) return false;

            $cacheFile = loader::getObject('\fpcm\model\files\cacheFile', $cacheName);
            return $cacheFile->read();
        }
        
        /**
         * Cache-Inhalt leeren
         * @param string $path
         * @param string $module
         * @return bool
         */
        public function cleanup($cacheName)
        {

            if (substr($cacheName, -1) === '*') {
                
            }
            
            $cacheFile = loader::getObject('\fpcm\model\files\cacheFile', $cacheName);
            

            
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
        public function getSize()
        {
            return array_sum(array_map('filesize', $this->getCacheComplete()));
        }

        /**
         * Liefert alle *cache-Dateien in cache-ordner zurück
         * @return array
         * @since FPCM 3.4
         */
        public function getCacheComplete() {
            return array_merge(glob(baseconfig::$cacheDir.'*.cache'), glob(baseconfig::$cacheDir.'*/*.cache'));
        }

    }

?>