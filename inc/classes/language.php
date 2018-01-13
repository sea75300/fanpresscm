<?php
    /**
     * FanPress CM 3.x
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\classes;

    /**
     * FanPress CM Language handler
     * 
     * @package fpcm\classes\language
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     */ 
    final class language {
        
        /**
         * Languages list
         * @var array
         */
        private $langList = [];
        
        /**
         * Language code
         * @var string
         */
        private $langCode = '';

        /**
         * Pfad für Sprachpaket
         * @var string
         */
        private $langPath = '';
        
        /**
         * Pfad zu Hilfe-Datei
         * @var string
         */
        private $helpFile = '';
        
        /**
         * Sprach-Cache
         * @var cache
         */
        private $cache;

        /**
         * Konstruktor
         * @param string $langCode
         * @return boolean
         */
        public function __construct($langCode = FPCM_DEFAULT_LANGUAGE_CODE)
        {
            
            $this->langPath = dirs::getIncDirPath('lang'.DIRECTORY_SEPARATOR.$langCode);

            if (!is_dir($this->langPath)) {
                trigger_error('Try to load undefined language: '.$langCode);
                return false;
            }
            
            $GLOBALS['langdata'] = [];
            $this->langCode = $langCode;

            $confFile = $this->langPath.'/lang.cfg';            
            if (!file_exists($confFile)) {
                trigger_error('Unable to find language config file in: '.$langCode);
                return false;
            }
            
            $this->langList[$langCode] = file_get_contents($confFile);
            $this->helpFile            = $this->langPath.'/help.php';

            $this->cache = loader::getObject('fpcm\classes\cache');
            $cacheName   = 'system/langcache'. strtoupper($langCode);
            
//            if (!$this->cache->isExpired($cacheName)) {
//                $GLOBALS['langdata'] = $this->cache->read($cacheName);                
//                return;
//            }            
            
//            $moduleLangFiles            = ($langCode != FPCM_DEFAULT_LANGUAGE_CODE
//                                        ? glob(dirs::getDataDirPath(dirs::DATA_MODULES, '*/*/lang/'.FPCM_DEFAULT_LANGUAGE_CODE.'/*.php'))
//                                        : []);
//
//            $moduleLangFiles_langcode   = glob(dirs::getDataDirPath(dirs::DATA_MODULES, '*/*/lang/'.$langCode.'/*.php'));
//            
//            if (is_array($moduleLangFiles_langcode)) {
//                $moduleLangFiles += $moduleLangFiles_langcode;
//            }

            $langfiles       = array_merge(
                glob($this->langPath.DIRECTORY_SEPARATOR.'*.php'),
                [] //(is_array($moduleLangFiles) ? $moduleLangFiles : [])
            );

            foreach ($langfiles as $file) {

                if (strpos($file, 'help.php') !== false) {
                    continue;
                }
                
                include $file;
                
                if (!isset($lang)) {
                    trigger_error('No language data defined in:'.$file);
                    continue;
                }
                
                $GLOBALS['langdata'] = array_merge($GLOBALS['langdata'], $lang);
            }

            $this->cache->write($cacheName, $GLOBALS['langdata'], FPCM_LANGCACHE_TIMEOUT);
        }
        
        /**
         * Gibt installierte Sprachpakete zurück
         * @return array
         */
        public function getLanguages()
        {
            
            $langs = glob(dirs::getIncDirPath('lang/*/lang.cfg'));
            
            foreach ($langs as $lang) {
                $langCode = basename(dirname($lang));
                $langName = file_get_contents($lang);
                
                $this->langList[$langCode] = $langName;
            }
            
            return $this->langList;
        }

        /**
         * Gibt aktuellen Sprachcode zurück
         * @return string
         */        
        public function getLangCode()
        {
            return $this->langCode;
        }        
        
        /**
         * Gibt Hilfe-XML-String zurück
         * @return string
         */
        public function getHelp()
        {
            return file_get_contents($this->helpFile);
        }

        /**
         * Gibt Text für übergebene Sprachavriable zurück
         * @param string $langvar Sprachvariable
         * @param array $replaceParams Liste von Platzhaltern in der Sprachvariable mit zu ersetzendem Text
         * * Aufbau: Key = Platzhalter => Value = Text
         * @return string
         */
        public function translate($langvar, array $replaceParams = [])
        {

            $langvar  = strtoupper($langvar);
            $langData = isset($GLOBALS['langdata'][$langvar]) ? $GLOBALS['langdata'][$langvar] : null;  

            $replacement = [];
            foreach ($replaceParams as $key => $val) {

                if (substr($key, 0, 2) !== '{{' && substr($key, -2) !== '}}') {
                    $key = '{{'.$key.'}}';
                }

                $replacement[$key] = $val;
            }
            
            $replaceParams = null;

            return is_null($langData) ? $langData : str_replace(array_keys($replacement), array_values($replacement), $langData);
        }
        
        /**
         * Ersetzt Monat (1-12) in sprachspezifischen String
         * @param int $monthId
         */
        public function writeMonth($monthId)
        {
            print isset($GLOBALS['langdata']['SYSTEM_MONTHS'][$monthId]) ? $GLOBALS['langdata']['SYSTEM_MONTHS'][$monthId] : null;
        }
        
        /**
         * Gibt sprachspezifische Monate zurück
         * @return array
         */
        public function getMonths()
        {
            return $GLOBALS['langdata']['SYSTEM_MONTHS'];
        }
        
        /**
         * Gibt sprachspezifische Tage zurück
         * @return array
         */
        public function getDays()
        {
            return $GLOBALS['langdata']['SYSTEM_DAYS'];
        }
        
        /**
         * Gibt sprachspezifische Tage zurück
         * @return array
         */
        public function getDaysShort()
        {            
            $days = $this->getDays();
            foreach ($days as &$day) {
                $day = substr($day, 0, 2);
            }            
            return $days;
        }

        /**
         * Schreibt Text für übergebene Sprachavriable an die Stelle des Aufrufs, sbwp. in einer View
         * @param string $langvar Sprachvariable
         * @param array $replaceParams Liste von Platzhaltern in der Sprachvariable mit zu ersetzendem Text
         * * Aufbau: Key = Platzhalter => Value = Text
         */
        public function write($langvar, array $replaceParams = [])
        {
            print $this->translate($langvar, $replaceParams);
        }

    }
?>