<?php
    /**
     * Module updater object
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\updater;

    /**
     * Module Updater Objekt
     * 
     * @package fpcm\model\updater
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    final class modules extends \fpcm\model\abstracts\remoteModel {
        
        /**
         * Hinweis, dass allow_url_fopen nicht aktiv ist
         */
        const MODULEUPDATER_FURLOPEN_ERROR      = 400;
        
        /**
         * Fehler beim Prüfung, ob Update-Server erreichbar ist
         */
        const MODULEUPDATER_REMOTEFILE_ERROR    = 401;
        
        /**
         * Fehler beim Abrüfen der Update-Informationen
         */        
        const MODULEUPDATER_REMOTECONTENT_ERROR = 402;        
        
        /**
         * Status, dass Update erzwungen wird
         */        
        const MODULEUPDATER_FORCE_UPDATE        = 1001;            
        
        /**
         * Cache name
         * @var string
         */
        protected $cacheName = 'fpcmmoduleupdates'; 
        
        /**
         * Cache module
         * @var string
         */
        protected $cacheModule = 'pkgmgr';
        
        /**
         * Initialisiert System Updater
         */
        public function __construct() {
            parent::__construct();

            $this->remoteUrl = \fpcm\classes\baseconfig::$moduleServer.'server3.php?data=';
            $this->checkParams  = array('version'=> $this->config->system_version);
            
            $this->encodeUrl();
        }
        
        /**
         * Prüft ob Updates verfügbar sind
         * @param bool $force Cache-Daten nicht verwenden
         * @return boolean
         */
        public function getModulelist($force = false) {

            if (!$this->canConnect) return self::MODULEUPDATER_FURLOPEN_ERROR;

            if ($this->cache->isExpired() || $force) {
                
                if (!$this->remoteAvailable()) self::MODULEUPDATER_REMOTEFILE_ERROR;

                $this->remoteData = file_get_contents($this->remoteServer);

                if (!$this->remoteData) {
                    trigger_error('Error while fetching update informations from: '.$this->remoteServer);
                    return self::MODULEUPDATER_REMOTECONTENT_ERROR;
                }
                
                $this->decodeData();
                
                $this->cache->write($this->remoteData, $this->config->system_cache_timeout);
            } else {
                $this->remoteData = $this->cache->read();
            }            

            return true;            
        }
        
        /**
         * Prüft ob Updates für Module vorhanden sind
         * @return boolean
         */
        public function checkUpdates() {
            
            if (!$this->canConnect) return self::MODULEUPDATER_FURLOPEN_ERROR;
            
            $list   = new \fpcm\model\modules\modulelist();
            
            $local  = $list->getModulesLocal();
            $remote = $list->getModulesRemote();

            $updates = 0;
            foreach ($local as $key => $module) {
                if (!isset($remote[$key]) || (isset($remote[$key]) && version_compare($remote[$key]->getVersionRemote(), $module->getVersion(), '<='))) continue;
                $updates++;
            }
            
            return $updates > 0 ? true : false;
        }
    }
?>