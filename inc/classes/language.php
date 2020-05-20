<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * FanPress CM Language handler
 * 
 * @package fpcm\classes\language
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
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
     * @return bool
     */
    public function __construct($langCode = FPCM_DEFAULT_LANGUAGE_CODE)
    {
        $this->langPath = dirs::getIncDirPath('lang' . DIRECTORY_SEPARATOR . $langCode);

        if (!is_dir($this->langPath)) {
            trigger_error('Try to load undefined language: ' . $langCode);
            return false;
        }

        if (!isset($GLOBALS['langdata'])) {
            $GLOBALS['langdata'] = [];
        }

        $this->langCode = $langCode;

        $confFile = $this->langPath . '/lang.cfg';
        if (!file_exists($confFile)) {
            trigger_error('Unable to find language config file in: ' . $langCode);
            return false;
        }

        $this->langList[$langCode] = file_get_contents($confFile);
        $this->helpFile = $this->langPath . '/help.php';

        $this->cache = loader::getObject('\fpcm\classes\cache');
        $cacheName = 'system/langcache' . strtoupper($langCode);
        
        if (!$this->cache->isExpired($cacheName)) {
            $GLOBALS['langdata'] = $this->cache->read($cacheName);
            return;
        }
        
        if (count($GLOBALS['langdata'])) {
            return;
        }

        $langfiles = array_merge(glob($this->langPath . DIRECTORY_SEPARATOR . '*.php'));
        foreach ($langfiles as $file) {

            if (strpos($file, 'help.php') !== false) {
                continue;
            }

            include $file;

            if (!isset($lang)) {
                trigger_error('No language data defined in:' . $file);
                continue;
            }

            $GLOBALS['langdata'] = array_merge($GLOBALS['langdata'], $lang);
        }
        
        $this->getModuleLanguage();

        $this->cache->write($cacheName, $GLOBALS['langdata'], FPCM_LANGCACHE_TIMEOUT);
    }

    /**
     * fetch module language files
     * @return array
     */
    private function getModuleLanguage()
    {
        if (baseconfig::installerEnabled() || !baseconfig::dbConfigExists()) {
            return true;
        }

        $activeModules = loader::getObject('\fpcm\module\modules')->getEnabledDatabase();
        if (!count($activeModules)) {
            return true;
        }
        
        foreach ($activeModules as $module) {
            
            $file = \fpcm\module\module::getLanguageFileByKey($module, $this->langCode);
            if (!file_exists($file)) {
                $file = \fpcm\module\module::getLanguageFileByKey($module, FPCM_DEFAULT_LANGUAGE_CODE);
            }

            if (!file_exists($file)) {
                trigger_error('No '. strtoupper($this->langCode).' language data found for '.$module.', file '.\fpcm\model\files\ops::removeBaseDir($file, true).' does not exists!');
                continue;
            }

            include $file;

            if (!isset($lang)) {
                trigger_error('No language data defined in:' . $file);
                continue;
            }

            if (!count($lang)) {
                continue;
            }
            
            $prefix = \fpcm\module\module::getLanguageVarPrefixed($module);
            
            $keys = array_keys($lang);
            array_walk($keys, [$this, 'addModulePrefix'], ['prefix' => $prefix]);

            $lang = array_combine($keys, array_values($lang));
            $GLOBALS['langdata'] = array_merge($GLOBALS['langdata'], $lang);
        }

        return true;        
    }

    /**
     * Add module prefix to language vars
     * @param string $val
     * @param string $key
     * @param array $args
     */
    private function addModulePrefix(&$val, $key, $args)
    {
        $val = $args['prefix'].$val;
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
        $langvarUc = strtoupper($langvar);
        $langData = $GLOBALS['langdata'][$langvarUc] ?? $langvar;

        $replacement = [];
        foreach ($replaceParams as $key => $val) {

            if (substr($key, 0, 2) !== '{{' && substr($key, -2) !== '}}') {
                $key = '{{' . $key . '}}';
            }

            $replacement[$key] = $val;
        }

        return tools::strReplaceArray($langData, $replacement);
    }

    /**
     * Ersetzt Monat (1-12) in sprachspezifischen String
     * @param int $monthId
     * @param bool $return
     * @return type
     */
    public function writeMonth($monthId, $return = false)
    {
        $monthId = (string) $monthId;
        $result = $GLOBALS['langdata']['SYSTEM_MONTHS'][$monthId] ?? null;

        if ($return) {
            return $result;
        }

        print $result;
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
     * Returns full language data
     * @return array
     * @since FPCM 4.4
     */
    public function getAll() : array
    {
        return $GLOBALS['langdata'];
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
