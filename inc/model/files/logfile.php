<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Log file object
 *
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.6
 */
final class logfile extends \fpcm\model\abstracts\file {

    /**
     * Systemlog
     */
    const FPCM_LOGFILETYPE_SESSION = 'sessions';

    /**
     * Systemlog
     */
    const FPCM_LOGFILETYPE_SYSTEM = 'system';

    /**
     * Error-Log
     */
    const FPCM_LOGFILETYPE_PHP = 'error';

    /**
     * SQl-Log
     */
    const FPCM_LOGFILETYPE_SQL = 'database';

    /**
     * Paket Manager Log
     */
    const FPCM_LOGFILETYPE_PKGMGR = 'packages';

    /**
     * Cronjobs Log
     */
    const FPCM_LOGFILETYPE_CRON = 'cronjobs';

    /**
     * Events Log
     */
    const FPCM_LOGFILETYPE_EVENTS = 'events';

    /**
     * Mapping für Integer-Logtyp auf intere Datei
     * * 1 = Systemlog
     * * 2 = Errorlog
     * * 3 = Sqlog
     * * 4 = Paketmanagerlog
     * * 5 = Cronjobslog
     * * 6 =Eventslogs
     * @var array
     */
    protected $fileMap = [];

    /**
     * Konstruktor
     * @param int $logFile
     * @param bool $init
     * @return bool
     */
    public function __construct($logFile, bool $init = true)
    {
        $this->fileMap = self::getLogMap();

        if (!isset($this->fileMap[$logFile])) {
            trigger_error("Invalid logfile type given: {$logFile}");
            return false;
        }

        parent::__construct($this->fileMap[$logFile]);

        if (!$init) {
            return;
        }

        $this->init();
    }

    /**
     * Returns base path for file
     * @param string $filename
     * @return string
     */
    protected function basePath($filename)
    {
        return $filename;
    }

    /**
     * Speichert eine neue temporäre Datei in data/temp/
     * @return bool
     */
    public function save()
    {
        if (!$this->isWritable()) {
            return false;
        }

        return file_put_contents($this->fullpath, $this->content);
    }

    /**
     * Logdatei leeren
     * @return bool
     */
    public function clear()
    {
        $this->content = '';
        if ($this->save() === false) {
            return false;
        }

        return true;
    }

    /**
     * Logdatei auslesen
     * @return array
     */
    public function fetchData()
    {
        if (!$this->exists() || !$this->isReadable()) {
            return [];
        }

        $content = file($this->fullpath, FILE_SKIP_EMPTY_LINES);
        if ($content === false) {
            trigger_error('Unable to read data from ' . $this->filename);
            return [];
        }

        return array_map('json_decode', $content);
    }

    /**
     *
     * @param array $elements
     * @param string $term
     * @return array
     * @since 5.2.0-rc2
     */
    public function search(array $elements, string $term) : array
    {
        return array_filter($elements, function($line) use ($term) {
            
            $text = $line->text;
            
            if (is_array($text)) {
                $text = implode(' ', $text);
            }
            
            return str_contains($text, $term);
        });
    }

    /**
     * Initialisiert Objekt einer temporären Datei
     * @return void
     */
    public function init()
    {
        if (!$this->exists()) {
            return;
        }

        $this->loadContent();
    }

    /**
     * Returns logs map
     * @return array
     * @since 4.5.1-b1
     */
    final public static function getLogMap() : array
    {
        return [
            self::FPCM_LOGFILETYPE_SYSTEM => \fpcm\classes\baseconfig::$logFiles['syslog'],
            self::FPCM_LOGFILETYPE_PHP => \fpcm\classes\baseconfig::$logFiles['phplog'],
            self::FPCM_LOGFILETYPE_SQL => \fpcm\classes\baseconfig::$logFiles['dblog'],
            self::FPCM_LOGFILETYPE_PKGMGR => \fpcm\classes\baseconfig::$logFiles['pkglog'],
            self::FPCM_LOGFILETYPE_CRON => \fpcm\classes\baseconfig::$logFiles['cronlog'],
            self::FPCM_LOGFILETYPE_EVENTS => \fpcm\classes\baseconfig::$logFiles['eventslogs'],
        ];
    }

}
