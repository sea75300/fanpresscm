<?php
    /**
     * FanPress CM 3.x
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\classes;

    /**
     * FanPress CM Language handler
     * 
     * @package fpcm\classes\language
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
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
        public function __construct($langCode) {
            
            if (!$langCode) $langCode = FPCM_DEFAULT_LANGUAGE_CODE;
            
            if (!is_dir(baseconfig::$langDir.$langCode)) {
                trigger_error('Try to load undefined language: '.$langCode);
                return false;
            }
            
            $GLOBALS['langdata'] = [];
            
            $this->langCode = $langCode;

            $confFile = baseconfig::$langDir.$langCode.'/lang.cfg';            
            if (!file_exists($confFile)) {
                trigger_error('Unable to find language config file in: '.$langCode);
                return false;
            }
            
            $this->langList[$langCode] = file_get_contents($confFile);
            
            $this->helpFile            = baseconfig::$langDir.$langCode.'/help.php';
            
            $this->cache = new cache('langcache_'.$langCode, 'system');
            
            if (!$this->cache->isExpired()) {
                $GLOBALS['langdata'] = $this->cache->read();                
                return;
            }            
            
            $moduleLangFiles = ($langCode != FPCM_DEFAULT_LANGUAGE_CODE ? glob(baseconfig::$moduleDir.'*/*/lang/'.FPCM_DEFAULT_LANGUAGE_CODE.'/*.php') : array());
            $moduleLangFiles_langcode = glob(baseconfig::$moduleDir.'*/*/lang/'.$langCode.'/*.php');
            
            if (is_array($moduleLangFiles_langcode)) {
                $moduleLangFiles += $moduleLangFiles_langcode;
            }

            $langfiles       = array_merge(glob(baseconfig::$langDir.$langCode.'/*.php'), (is_array($moduleLangFiles) ? $moduleLangFiles : array()));
            
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

            $this->cache->write($GLOBALS['langdata'], FPCM_LANGCACHE_TIMEOUT);
        }
        
        /**
         * Gibt installierte Sprachpakete zurück
         * @return array
         */
        public function getLanguages() {
            
            $langs = glob(baseconfig::$langDir.'*/lang.cfg');
            
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
        public function getLangCode() {
            return $this->langCode;
        }        
        
        /**
         * Gibt Hilfe-XML-String zurück
         * @return string
         */
        public function getHelp() {
            return file_get_contents($this->helpFile);
        }

        /**
         * Gibt Text für übergebene Sprachavriable zurück
         * @param string $langvar Sprachvariable
         * @param array $replaceParams Liste von Platzhaltern in der Sprachvariable mit zu ersetzendem Text
         * * Aufbau: Key = Platzhalter => Value = Text
         * @return string
         */
        public function translate($langvar, array $replaceParams = array()) {

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
        public function writeMonth($monthId) {
            print isset($GLOBALS['langdata']['SYSTEM_MONTHS'][$monthId]) ? $GLOBALS['langdata']['SYSTEM_MONTHS'][$monthId] : null;
        }
        
        /**
         * Gibt sprachspezifische Monate zurück
         * @return array
         */
        public function getMonths() {
            return $GLOBALS['langdata']['SYSTEM_MONTHS'];
        }
        
        /**
         * Gibt sprachspezifische Tage zurück
         * @return array
         */
        public function getDays() {
            return $GLOBALS['langdata']['SYSTEM_DAYS'];
        }
        
        /**
         * Gibt sprachspezifische Tage zurück
         * @return array
         */
        public function getDaysShort() {            
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
        public function write($langvar, array $replaceParams = array()) {
            print $this->translate($langvar, $replaceParams);
        }
        
        /**
         * Gibt OK aus
         */
        public function printOk() {
            print $this->translate('GLOBAL_OK');
        }
        
        /**
         * Gibt "Ja" aus
         */
        public function printYes() {
            print $this->translate('GLOBAL_YES');
        }
        
        /**
         * Gibt "Nein" aus
         */
        public function printNo() {
            print $this->translate('GLOBAL_NO');
        }
        
        /**
         * Gibt "Speichern" aus
         */
        public function printSave() {
            print $this->translate('GLOBAL_SAVE');
        }
        
        /**
         * Gibt "Zurück" aus
         */
        public function printBack() {
            print $this->translate('GLOBAL_BACK');
        }
        
        /**
         * Gibt "Schließen" aus
         */
        public function printClose() {
            print $this->translate('GLOBAL_CLOSE');
        }
        
    }
?>