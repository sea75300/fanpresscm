<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * FanPress CM Language handler
 *
 * @package fpcm\classes\language
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class language {

    private const FILENAME_VARS = 'vars';

    private const FILENAME_LISTS = 'lists';

    public const VARTEXT_NEWLINE = '[NL]';

    public const LANGCODE_VALIDATION = '/[^a-zA-Z0-9\-]/';

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
    public function __construct($langCode = '')
    {

        if (trim($langCode)) {
            $langCode = preg_replace(self::LANGCODE_VALIDATION, '', $langCode);
        }

        if (!trim($langCode)) {
            $langCode = FPCM_DEFAULT_LANGUAGE_CODE;
        }

        $this->langPath = dirs::getIncDirPath('lang' . DIRECTORY_SEPARATOR . $langCode);

        if (!is_dir($this->langPath)) {
            trigger_error('Try to load undefined language: ' . $langCode);
            return false;
        }

        if ($langCode && $this->langCode !== $langCode) {
            $GLOBALS['langdata'] = [];
        }

        $this->langCode = $langCode;
        $this->init();
    }

    /**
     * Load language variables
     * @return bool
     * @since 4.5
     */
    private function init() : bool
    {
        if (!isset($GLOBALS['langdata'])) {
            $GLOBALS['langdata'] = [];
        }

        $confFile = $this->langPath . '/lang.cfg';
        if (!file_exists($confFile)) {
            trigger_error('Unable to find language config file in: ' . $this->langCode);
            return false;
        }

        $this->langList[$this->langCode] = file_get_contents($confFile);
        $this->helpFile = $this->langPath . '/help.php';
        if (!file_exists($this->helpFile)) {
            $this->helpFile = dirs::getIncDirPath('lang' . DIRECTORY_SEPARATOR . FPCM_DEFAULT_LANGUAGE_CODE . DIRECTORY_SEPARATOR . '/help.php');
        }

        $this->cache = loader::getObject('\fpcm\classes\cache');
        $cacheName = 'system/langcache' . strtoupper($this->langCode);

        if (!$this->cache->isExpired($cacheName)) {
            $GLOBALS['langdata'] = $this->cache->read($cacheName);
            return true;
        }

        if (count($GLOBALS['langdata'])) {
            return true;
        }

        $this->loadDataFromSystem(self::FILENAME_VARS);
        $this->loadDataFromSystem(self::FILENAME_LISTS);
        $this->getModuleLanguage();

        $this->cache->write($cacheName, $GLOBALS['langdata'], FPCM_LANGCACHE_TIMEOUT);
        return true;
    }

    /**
     * Get language file name and path
     * @param string $name
     * @return string|null
     * @since 4.5
     */
    private function getFileName(string $name) : ?string
    {
        $file = $this->langPath . DIRECTORY_SEPARATOR . $name . '.php';
        return file_exists($file) ? $file : null;
    }

    /**
     * Load language data from file
     * @param string $name
     * @return void
     * @since 4.5
     */
    private function loadDataFromSystem(string $name) : void
    {
        $file = $this->getFileName($name);
        if ($file === null) {
            trigger_error('Language file ' . $file . ' does not exists!');
            print 'ERR LANG 1 vars';
            return;
        }

        include $file;
        if (!isset($lang) || !is_array($lang)) {
            trigger_error('No language data defined in:' . $file);
            print 'ERR LANG 1 vars';
            return;
        }

        $GLOBALS['langdata'] = array_merge($GLOBALS['langdata'], $lang);
        unset($file);
    }

    /**
     * fetch module language files
     * @return void
     */
    private function getModuleLanguage() : void
    {
        if (baseconfig::installerEnabled() || !baseconfig::dbConfigExists()) {
            return;
        }

        $activeModules = loader::getObject('\fpcm\module\modules')->getEnabledDatabase();
        if (!count($activeModules)) {
            return;
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

        return;
    }

    /**
     * Add module prefix to language vars
     * @param string $val
     * @param string $key
     * @param array $args
     */
    private function addModulePrefix(&$val, $key, $args)
    {
        if (strpos($val, 'SYSCHECK_FOLDER_') === 0) {
            return true;
        }

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

    /**
     * Return value of language variable
     * @param string|null $langvar language variable
     * @param array $replaceParams replacement values for placeholder in variable text
     * @param bool $spf use vsprintf instead of old {{placehodler}} variables (@since 5.1-dev)
     * @return string
     */
    public function translate(?string $langvar, array $replaceParams = [], bool $spf = false)
    {
        if ($langvar === null || !trim($langvar)) {
            return '';
        }

        $langData = $GLOBALS['langdata'][strtoupper($langvar)] ?? $langvar;

        if ($spf) {
            \fpcm\view\helper\icon::parseLangvarIcon($langData);
            return vsprintf($langData, array_values($replaceParams));
        }

        $replacement = [];
        foreach ($replaceParams as $key => $val) {

            if (substr($key, 0, 2) !== '{{' && substr($key, -2) !== '}}') {
                $key = '{{' . $key . '}}';
            }

            $replacement[$key] = $val;
        }

        unset($val);

        if (!method_exists('\fpcm\view\helper\icon', 'parseLangvarIcon')) {
            return tools::strReplaceArray($langData, $replacement);
        }

        \fpcm\view\helper\icon::parseLangvarIcon($langData);
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
     * @since 4.4
     */
    public function getAll(bool $force = false) : array
    {
        if ($force) {
            $GLOBALS['langdata'] = [];
            $this->init();

            return $GLOBALS['langdata'];
        }

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
     * Replaces special characters of current language
     * @param string $str
     * @return string
     * @since 5.2.0-rc2
     */
    public function replaceSpecialCharacters(string $str) : string
    {
        $map = $GLOBALS['langdata']['LANGUAGE_CHARACTER_REPLACE'] ?? [];
        if (!$map) {
            return $str;
        }
        
        return strtr($str, $map);
    }

    /**
     * Directly writes text of given language variable
     * @param string|null $langvar language variable
     * @param array $replaceParams replacement values for placeholder in variable text
     * @param bool $spf use vsprintf instead of old {{placehodler}} variables (@since 5.1-dev)
     * @return void
     */
    public function write(?string $langvar, array $replaceParams = [], bool $spf = false): void
    {
        print $this->translate($langvar, $replaceParams, $spf);
    }

    /**
     * Save language vars to corresponding files
     * @param array $vars
     * @param array $lists
     * @return bool
     * @since 4.5
     */
    public function saveFiles(array $vars, array $lists) : bool
    {
        if (!count($vars) || !count($lists)) {
            trigger_error('Language files cannot be saved with empty data.');
            return false;
        }

        $res = $this->writeNewFile(self::FILENAME_VARS, implode(PHP_EOL, [
            '<?php',
            '',
            '/**',
            ' * FanPress CM language variables file: '.$this->getLangCode(),
            ' * @author Stefan Seehafer <sea75300@yahoo.de>',
            ' * @copyright (c) 2011-'.date('Y').', Stefan Seehafer',
            ' * @license http://www.gnu.org/licenses/gpl.txt GPLv3',
            ' */',
            '',
            '$lang = '. var_export($vars, true).';',
            ''
        ]));

        if (!$res) {
            return false;
        }

        $res = $this->writeNewFile(self::FILENAME_LISTS, implode(PHP_EOL, [
            '<?php',
            '',
            '/**',
            ' * FanPress CM language list file: '.$this->getLangCode(),
            ' * @author Stefan Seehafer <sea75300@yahoo.de>',
            ' * @copyright (c) 2011-'.date('Y').', Stefan Seehafer',
            ' * @license http://www.gnu.org/licenses/gpl.txt GPLv3',
            ' */',
            '',
            '$lang = '. var_export($lists, true).';',
            ''
        ]));

        if (!$res) {
            return false;
        }

        $this->cache->cleanup('system/langcache' . strtoupper($this->getLangCode()));
        unset($GLOBALS['langdata']);
        $this->init();
        return true;
    }

    /**
     * Write file content
     * @param string $file
     * @param string $content
     * @return bool
     * @since 4.5
     */
    private function writeNewFile(string $file, string $content) : bool
    {
        $file = $this->getFileName($file);

        if ($file === null || !is_writable($file)) {
            trigger_error('Language file ' . $file . ' does not exists or is not writable!');
            return false;
        }

        $backFile = $file.'lebck';
        if (!copy($file, $backFile)) {
            trigger_error('Unable to create back file '.$backFile.' for ' . $file);
            return false;
        }

        $res = file_put_contents($file, $content, LOCK_EX);
        if (!$res) {
            trigger_error('Unable to write language file ' . $file);
            return false;
        }

        unlink($backFile);
        return true;
    }

}
