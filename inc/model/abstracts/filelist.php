<?php
    /**
     * FanPress CM filelist model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * File list model base
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class filelist extends tablelist {
        
        /**
         * erlaute Dateierweiterungen
         * @var array
         */
        protected $exts = [];
        
        /**
         * Dateilisten-Basispfad
         * @var string
         */
        protected $basepath = '';
        
        /**
         * Dateilisten-Basispfad
         * @var string
         */
        protected $pathprefix = '';

        /**
         * Gibt Liste von Dateien mit den erlaubten Dateierweiterungen zurück
         * @return array
         */
        public function getFolderList() {            

            $res = [];
            
            foreach ($this->exts as $ext) {
                $extLower = glob($this->basepath.$this->pathprefix.'*.'.$ext);
                $extUpper = glob($this->basepath.$this->pathprefix.'*.'.strtoupper($ext));
                
                if (!$extLower) $extLower = [];
                if (!$extUpper) $extUpper = [];
                
                $res = array_merge($res, $extLower, $extUpper);
            }
            
            return $res;
        }
        
        /**
         * Gibt Pfadprefix zurück
         * @return string
         */
        public function getPathprefix() {
            return $this->pathprefix;
        }

        /**
         * Setzt Pfadprefix
         * @param string $pathprefix
         */
        public function setPathprefix($pathprefix) {
            $this->pathprefix = $pathprefix;
        }        
        
    }
