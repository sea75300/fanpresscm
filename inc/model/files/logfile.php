<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\files;

    /**
     * Log file object
     * 
     * @package fpcm\model\files
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.6
     */
    final class logfile extends \fpcm\model\abstracts\file {

        /**
         * Systemlog
         */
        const FPCM_LOGFILETYPE_SYSTEM   = 1;

        /**
         * Error-Log
         */
        const FPCM_LOGFILETYPE_PHP      = 2;

        /**
         * SQl-Log
         */
        const FPCM_LOGFILETYPE_SQL      = 3;

        /**
         * Paket Manager Log
         */
        const FPCM_LOGFILETYPE_PKGMGR   = 4;

        /**
         * Cronjobs Log
         */
        const FPCM_LOGFILETYPE_CRON     = 5;

        /**
         * Mapping für Integer-Logtyp auf intere Datei
         * * 1 = Systemlog
         * * 2 = Errorlog
         * * 3 = Sqlog
         * * 4 = Paketmanagerlog
         * * 5 = Cronjobslog
         * @var array
         */
        protected $fileMap = [];

        /**
         * Konstruktor
         * @param int $logFile Dateiname
         */
        public function __construct($logFile) {
            
            $this->fileMap = [
                static::FPCM_LOGFILETYPE_SYSTEM => \fpcm\classes\baseconfig::$logFiles['syslog'],
                static::FPCM_LOGFILETYPE_PHP    => \fpcm\classes\baseconfig::$logFiles['phplog'],
                static::FPCM_LOGFILETYPE_SQL    => \fpcm\classes\baseconfig::$logFiles['dblog'],
                static::FPCM_LOGFILETYPE_PKGMGR => \fpcm\classes\baseconfig::$logFiles['pkglog'],
                static::FPCM_LOGFILETYPE_CRON   => \fpcm\classes\baseconfig::$logFiles['cronlog']
            ];

            if (!isset($this->fileMap[$logFile])) {
                trigger_error('Invalid logfile type given');
                return false;
            }
            
            $path = $this->fileMap[$logFile];
            parent::__construct(basename($path), dirname($path).DIRECTORY_SEPARATOR);
            
            $this->init();
        }
        
        /**
         * Speichert eine neue temporäre Datei in data/temp/
         * @return bool
         */
        public function save() {
            return file_put_contents($this->fullpath, $this->content);            
        }

        /**
         * Logdatei leeren
         * @return bool
         */
        public function clear() {

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
        public function fetchData() {

            if (!$this->exists()) {
                return [];
            }

            $content = file($this->fullpath, FILE_SKIP_EMPTY_LINES);
            if ($content === false) {
                trigger_error('Unable to read data from '.$this->filename);
                return [];
            }

            return array_map('json_decode', $content);
        }
        
        /**
         * Initialisiert Objekt einer temporären Datei
         * @return void
         */
        protected function init() {
            if (!$this->exists()) return;
            $this->loadContent();
        }
    }
?>