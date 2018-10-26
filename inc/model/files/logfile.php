<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\files;

/**
 * Log file object
 * 
 * @package fpcm\model\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.6
 */
final class logfile extends \fpcm\model\abstracts\file {

    /**
     * Systemlog
     */
    const FPCM_LOGFILETYPE_SYSTEM = 1;

    /**
     * Error-Log
     */
    const FPCM_LOGFILETYPE_PHP = 2;

    /**
     * SQl-Log
     */
    const FPCM_LOGFILETYPE_SQL = 3;

    /**
     * Paket Manager Log
     */
    const FPCM_LOGFILETYPE_PKGMGR = 4;

    /**
     * Cronjobs Log
     */
    const FPCM_LOGFILETYPE_CRON = 5;

    /**
     * Events Log
     */
    const FPCM_LOGFILETYPE_EVENTS = 6;

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
        $this->fileMap = [
            self::FPCM_LOGFILETYPE_SYSTEM => \fpcm\classes\baseconfig::$logFiles['syslog'],
            self::FPCM_LOGFILETYPE_PHP => \fpcm\classes\baseconfig::$logFiles['phplog'],
            self::FPCM_LOGFILETYPE_SQL => \fpcm\classes\baseconfig::$logFiles['dblog'],
            self::FPCM_LOGFILETYPE_PKGMGR => \fpcm\classes\baseconfig::$logFiles['pkglog'],
            self::FPCM_LOGFILETYPE_CRON => \fpcm\classes\baseconfig::$logFiles['cronlog'],
            self::FPCM_LOGFILETYPE_EVENTS => \fpcm\classes\baseconfig::$logFiles['eventslogs'],
        ];

        if (!isset($this->fileMap[$logFile])) {
            trigger_error('Invalid logfile type given');
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

}

?>