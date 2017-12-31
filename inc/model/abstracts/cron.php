<?php
    /**
     * FanPress CM cronjob model
     * 
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * Cronjob model base
     * 
     * @package fpcm\model\abstracts
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */ 
    abstract class cron implements \fpcm\model\interfaces\cron {

        /**
         * Datenbank-Objekt
         * @var \fpcm\classes\database
         */
        protected $dbcon;

        /**
         * Name des Crons
         * @var string
         */
        protected $cronName;
        
        /**
         * Zeitpunkt der letzten Ausführung
         * @var int
         */
        protected $lastExecTime;
        
        /**
         * Interval der Ausführung
         * @var int
         * @since FPCM 3.2.0
         */
        protected $execinterval;

        /**
         * asynchrone Ausführung über cronasync-AJAX-Controller deaktivieren
         * @var bool, false wenn cronasync-AJAX nicht ausgführt werden soll
         */
        protected $runAsync = true;
        
        /**
         * Daten, die von Cronjob zurückgegeben werden sollen
         * @var mixed
         */
        protected $returnData = null;
        
        /**
         * Wird Cronjob aktuell asynchron ausgeführt
         * @var bool
         */
        protected $asyncCurrent = false;

        /**
         * Konstruktor
         * @param string $cronName Cronjob-Name
         * @param bool $init Objekt mit Daten aus Datenbank-Tabelle initialisieren
         */
        public function __construct($cronName, $init = true) {
            $this->table    = \fpcm\classes\database::tableCronjobs;
            $this->dbcon    = \fpcm\classes\baseconfig::$fpcmDatabase;
            $this->events   = \fpcm\classes\baseconfig::$fpcmEvents;
            $this->cronName = basename(str_replace('\\', DIRECTORY_SEPARATOR, $cronName));
            
            if ($init) {
                $this->init();
            }
        }
        
        /**
         * Häufigkeit der Ausführung einschränken
         * @return boolean
         */
        public function checkTime() {
            if (time() > $this->getNextExecTime()) return false;            

            return true;
        }
        
        /**
         * Gibt Zeitpunkt der letzten Ausführung des Cronjobs zurück
         * @return int
         */
        public function getLastExecTime() {            
            return (int) $this->lastExecTime;
        }
        
        /**
         * Gibt Zeitpunkt der letzten Ausführung des Cronjobs zurück
         * @return int
         */
        public function updateLastExecTime() { 
            $this->lastExecTime = time();
            $Res = $this->dbcon->update($this->table, array('lastexec'), array($this->lastExecTime, $this->cronName), 'cjname '.$this->dbcon->dbLike().' ?');
            return $Res;
        }
        
        /**
         * Läuft Cronjob auch asynchron
         * @return bool
         */
        public function getRunAsync() {
            return $this->runAsync;
        }        
        
        /**
         * Daten, die für Rückgabe vorgesehen sind abrufen
         * @return mixed
         */
        public function getReturnData() {
            return $this->returnData;
        }

        /**
         * Daten, die für Rückgabe vorgesehen sind setzen
         * @param mixed $returnData
         */
        public function setReturnData($returnData) {
            $this->returnData = $returnData;
        }
        
        /**
         * Gibt Cronjob-Name zurück
         * @return string
         */
        public function getCronName() {
            return $this->cronName;
        }
                
        /**
         * Gibt Status zurück, ob Cronjob aktuell asynchron ausgführt wird
         * @return bool
         */
        public function getAsyncCurrent() {
            return $this->asyncCurrent;
        }

        /**
         * Setzt Status, ob Cronjob aktuell asynchron ausgführt wird
         * @param bool $asyncCurrent
         */
        public function setAsyncCurrent($asyncCurrent) {
            $this->asyncCurrent = $asyncCurrent;
        }

        /**
         * Setzt Interval des Cronjobs
         * @param int $execinterval
         */
        function setExecinterval($execinterval) {
            $this->execinterval = (int) $execinterval;
        }
                        
        /**
         * Initialisiert
         */
        protected function init() {
            $res = $this->dbcon->fetch($this->dbcon->select($this->table, 'lastexec, execinterval', 'cjname '.$this->dbcon->dbLike().' ?', array($this->cronName)));            
            $this->lastExecTime = isset($res->lastexec) ? $res->lastexec : 0;
        }
        
        /**
         * Initialisiert anhand von Datenbank-Result-Set
         * @param object $data
         */
        public function createFromDbObject($data) {
            $this->lastExecTime = $data->lastexec;
            
            if (isset($data->cjname)) {
                $this->cronName = $data->cjname;
            }

            if (isset($data->execinterval)) {
                $this->execinterval = $data->execinterval;
            }
        }
        
        /**
         * Zeitpunkt der nächsten Ausführung berechnen
         * getLastExecTime() + getIntervalTime()
         * @return int
         */
        public function getNextExecTime() {
            
            if (!$this->lastExecTime) {
                return time();
            }
            
            return $this->getLastExecTime() + $this->getIntervalTime();
        }

        /**
         * Interval-Dauer zurückgeben
         * @return int
         */
        public function getIntervalTime() {
            return (int) $this->execinterval;
        }

        /**
         * Aktualisiert einen Artikel in der Datenbank
         * @return boolean
         */        
        public function update() {
            return $this->dbcon->update($this->table, array('execinterval'), array($this->execinterval, $this->cronName), 'cjname '.$this->dbcon->dbLike().' ?');
        }

        /**
         * Gibt Klassen-Namepsace für Cronjob-Klassen zurück
         * @param string $cronId
         * @return string
         * @since FPCM 3.3
         */
        public static function getCronNamespace($cronId) {
            return "\\fpcm\\model\\crons\\{$cronId}";
        }
        
    }
