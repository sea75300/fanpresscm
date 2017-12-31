<?php
    /**
     * Package object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\packages;

    /**
     * Update package objekt
     * 
     * @package fpcm\model\packages
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @since FPCM 3.1
     */
    class update extends package {
 
        /**
         * Konstruktor
         * @param string $type Package-Type
         * @param string $key Package-Key
         * @param string $version Package-Version
         * @param string $signature Package-Signature
         */
        public function __construct($type, $key, $version = '', $signature = '') {
            parent::__construct('update', $key, $version, $signature);
        }

        /**
         * Kopiert Inhalt von Paket von Quelle nach Ziel
         * @return boolean
         */
        public function copy() {    
            
            if (!file_exists($this->tempListFile)) {
                \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
                return false;
            }
            
            $this->loadPackageFileListFromTemp();
            
            if (!count($this->files)) {
                \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
                return false;
            }
            
            $res = true;
            foreach ($this->files as $zipFile) {

                $source = $this->extractPath.$zipFile;

                $dest   = dirname(\fpcm\classes\baseconfig::$baseDir).$this->copyDestination.$zipFile;
                $dest   = is_dir($source) ? dirname($dest).'/'.basename($dest) : $dest;                
                $dest   = $this->replaceFanpressDirString($dest);

                if (substr($dest, -8) === 'fanpress') {
                    continue;
                }
                
                if (is_dir($source)) {
                    if (!file_exists($dest) && !mkdir($dest, 0777)) {
                        if (!is_array($res)) $res = [];
                        $res[] = $dest;
                    }
                    continue;
                }

                if (file_exists($dest)) {
                    
                    if (sha1_file($source) == sha1_file($dest)) {
                        $this->updateProtocol($zipFile, -1);
                        continue;
                    }

                    $backFile = $dest.'.back';
                    if (file_exists($backFile)) {
                        unlink($backFile);
                    }

                    rename($dest, $backFile);

                }

                $success = copy($source, $dest);
                if (!$success) {
                    if (!is_array($res)) $res = [];                    
                    $res[] = $dest;
                }

                $this->updateProtocol($zipFile, $success);
            }            
            
            if (is_array($res)) {
                $this->copyErrorPaths = $res;
            }

            $this->saveProtocolTemp();
            return is_array($res) ? self::FPCMPACKAGE_FILESCOPY_ERROR : $res;
        }

    }
