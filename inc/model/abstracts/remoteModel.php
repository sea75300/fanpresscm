<?php
    /**
     * FanPress CM remote data
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * Remote data model
     * 
     * @package fpcm\model\abstracts
     * @abstract
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class remoteModel extends staticModel {
        
        /**
         * URL zum Server inkl. Datenparameter
         * @var string
         */
        protected $remoteUrl    = '';        

        /**
         * URL zum Server
         * @var string
         */
        protected $remoteServer = '';    
        
        /**
         * Datenparameter
         * @var array
         */
        protected $checkParams  = [];
        
        /**
         * Verbindungen zu anderem Server möglich
         * @var bool
         */
        protected $canConnect   = false;
        
        /**
         * vom Server zurückgegebene Daten
         * @var string
         */
        protected $remoteData   = '';

        /**
         * Konstruktor
         * @param int $init
         */
        public function __construct() {
            parent::__construct();            
            $this->canConnect   = \fpcm\classes\baseconfig::canConnect();
        }
        
        /**
         * Daten zurückgeben, die vom Server abgerufen wurden
         * @param string $key
         * @return array
         */
        public function getRemoteData($key = false) {
            return $key && isset($this->remoteData[$key]) ? $this->remoteData[$key] : $this->remoteData;
        }        
        
        /**
         * Update-Server-URL codieren
         */
        protected function encodeUrl() {
            $this->remoteServer = $this->remoteUrl.str_rot13(base64_encode(json_encode($this->checkParams)));
        }
        
        /**
         * abgrufene Daten dekodieren
         */
        protected function decodeData() {
            $this->remoteData = json_decode(base64_decode(str_rot13($this->remoteData)), true);
        }
        
        /**
         * Prüft, ob Update-Server verfügbar ist
         * @return boolean
         */
        protected function remoteAvailable() {
            $remoteTest = @fsockopen(parse_url($this->remoteUrl, PHP_URL_HOST), '80');            

            if (!$remoteTest) {
                trigger_error('Unable to connect to remote server: '.$this->remoteUrl);
                return false;
            }            

            fclose($remoteTest);  
            
            return true;
        }
        
        /**
         * Gibt codierte Update-Server-URL zurück
         * @return string
         */
        public function getRemoteServer() {
            return $this->remoteServer;
        }

    }
