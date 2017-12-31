<?php
    /**
     * System updater object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\updater;

    /**
     * System Updater Objekt
     * 
     * @package fpcm\model\updater
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class system extends \fpcm\model\abstracts\remoteModel {
        
        /**
         * Hinweis, dass allow_url_fopen nicht aktiv ist
         */
        const SYSTEMUPDATER_FURLOPEN_ERROR      = 400;
        
        /**
         * Fehler beim Prüfung, ob Update-Server erreichbar ist
         */
        const SYSTEMUPDATER_REMOTEFILE_ERROR    = 401;
        
        /**
         * Fehler beim Abrüfen der Update-Informationen
         */        
        const SYSTEMUPDATER_REMOTECONTENT_ERROR = 402;        
        
        /**
         * Status, dass Update erzwungen wird
         */        
        const SYSTEMUPDATER_FORCE_UPDATE        = 1001;
        
        /**
         * Cache name
         * @var string
         */
        protected $cacheName = 'fpcmsystemupdates';
        
        /**
         * Cache module
         * @var string
         */
        protected $cacheModule = 'pkgmgr';
        
        /**
         * Initialisiert System Updater
         * @param int $init
         */
        public function __construct() {
            parent::__construct();

            $this->remoteUrl = \fpcm\classes\baseconfig::$updateServer.'server3.php?data=';
            $this->checkParams  = array('version' => $this->config->system_version);
            
            if ($this->config->system_updates_devcheck) {
                $this->checkParams['dev'] = 1;
            }
            
            $this->encodeUrl();
        }
        
        /**
         * Prüft ob Updates verfügbar sind
         * @return boolean
         */
        public function checkUpdates() {

            if (!$this->canConnect) return self::SYSTEMUPDATER_FURLOPEN_ERROR;

            if ($this->cache->isExpired()) {

                if (!$this->remoteAvailable()) self::SYSTEMUPDATER_REMOTEFILE_ERROR;
                
                $this->remoteData = file_get_contents($this->remoteServer);

                if (!$this->remoteData) {
                    trigger_error('Error while fetching update informations from: '.$this->remoteServer);
                    return self::SYSTEMUPDATER_REMOTECONTENT_ERROR;
                }

                $this->decodeData();

                $this->cache->write($this->remoteData, $this->config->system_cache_timeout);
            } else {
                $this->remoteData = $this->cache->read();
            }

            $version = version_compare($this->config->system_version, $this->remoteData['version'], '<');
            if ($version && isset($this->remoteData['phpversion']) && version_compare(phpversion(), $this->remoteData['phpversion'], '<')) {
                fpcmLogSystem('FanPress CM version '.$this->remoteData['version'].' is available, but requires newer PHP version '.$this->remoteData['phpversion'].' or higher.');
                return true;
            }
            
            if (!$version) {
                return true;            
            }

            if ($this->remoteData['force']) return self::SYSTEMUPDATER_FORCE_UPDATE;                
            return false;
        }
        
        /**
         * Manueller Update-Check durchführen
         * @return bool
         */
        public function checkManual() {
            return (time() > filectime(\fpcm\classes\baseconfig::$versionFile) + $this->config->system_updates_manual) ? true : false;            
        }
        
        /**
         * Gibt Link für Manuelle Update-Prüfung zurück, seit FPCM 3.x Link zur Download-Seite von FanPress CM
         * @return string
         */
        public function getManualCheckAddress() {
            return \fpcm\classes\baseconfig::$updateServerManualLink;
        }

    }
?>