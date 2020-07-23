<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

use fpcm\classes\database;
use fpcm\classes\loader;
use fpcm\model\dbal\selectParams;
use fpcm\model\files\fileOption;

/**
 * Cronjob model base
 * 
 * @package fpcm\model\abstracts
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
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
     * Module key string
     * @var string
     * @since FPCM 4.3.0
     */
    protected $modulekey;

    /**
     * Cronjob is running
     * @var bool
     * @since FPCM 4.5.0-a1
     */
    protected $isrunning;

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
     * @param bool $init
     */
    public function __construct($init = true)
    {
        $this->table = database::tableCronjobs;
        $this->dbcon = loader::getObject('\fpcm\classes\database');
        $this->events = loader::getObject('\fpcm\events\events');
        $this->cronName = basename(str_replace('\\', DIRECTORY_SEPARATOR, get_class($this)));

        if (!$init) {
            return;
        }

        $this->init();
    }

    /**
     * Häufigkeit der Ausführung einschränken
     * @return bool
     */
    public function checkTime()
    {
        if (time() > $this->getNextExecTime())
            return false;

        return true;
    }

    /**
     * Gibt Zeitpunkt der letzten Ausführung des Cronjobs zurück
     * @return int
     */
    public function getLastExecTime()
    {
        return (int) $this->lastExecTime;
    }

    /**
     * Gibt Zeitpunkt der letzten Ausführung des Cronjobs zurück
     * @return int
     */
    public function updateLastExecTime()
    {
        $this->lastExecTime = time();
        return $this->dbcon->update($this->table, ['lastexec'], [$this->lastExecTime, $this->cronName], 'cjname=?');
    }

    /**
     * Läuft Cronjob auch asynchron
     * @return bool
     */
    public function getRunAsync()
    {
        return $this->runAsync;
    }

    /**
     * Returns module key string
     * @return string
     */
    public function getModuleKey() {
        return $this->modulekey;
    }

    /**
     * Interval-Dauer zurückgeben
     * @return int
     */
    public function getIntervalTime()
    {
        return (int) $this->execinterval;
    }
    
    /**
     * Daten, die für Rückgabe vorgesehen sind abrufen
     * @return mixed
     */
    public function getReturnData()
    {
        return $this->returnData;
    }

    /**
     * Daten, die für Rückgabe vorgesehen sind setzen
     * @param mixed $returnData
     */
    public function setReturnData($returnData)
    {
        $this->returnData = $returnData;
    }

    /**
     * Gibt Cronjob-Name zurück
     * @return string
     */
    public function getCronName()
    {
        return $this->cronName;
    }

    /**
     * Gibt Sprachvariable zur Übersetzung des Cronjob-Namen zurück
     * @return string
     */
    public function getCronNameLangVar()
    {
        if ($this->modulekey) {
            return \fpcm\module\module::getLanguageVarPrefixed($this->modulekey). 'CRONJOB_' . strtoupper($this->cronName);
        }
        
        return 'CRONJOB_' . strtoupper($this->cronName);
    }

    /**
     * Gibt Status zurück, ob Cronjob aktuell asynchron ausgführt wird
     * @return bool
     */
    public function getAsyncCurrent()
    {
        return $this->asyncCurrent;
    }

    /**
     * Setzt Status, ob Cronjob aktuell asynchron ausgführt wird
     * @param bool $asyncCurrent
     */
    public function setAsyncCurrent($asyncCurrent)
    {
        $this->asyncCurrent = $asyncCurrent;
    }

    /**
     * Setzt Interval des Cronjobs
     * @param int $execinterval
     */
    public function setExecinterval($execinterval)
    {
        $this->execinterval = (int) $execinterval;
    }

    /**
     * Initialisiert
     */
    public function init()
    {
        $res = $this->dbcon->selectFetch((new selectParams($this->table))
            ->setItem('lastexec, execinterval, isrunning, modulekey')
            ->setWhere('cjname = ?')
            ->setParams([$this->cronName])
        );

        $this->lastExecTime = isset($res->lastexec) ? $res->lastexec : 0;
    }

    /**
     * Initialisiert anhand von Datenbank-Result-Set
     * @param object $data
     */
    public function createFromDbObject($data)
    {
        $this->lastExecTime = $data->lastexec;

        if (isset($data->cjname)) {
            $this->cronName = $data->cjname;
        }

        if (isset($data->execinterval)) {
            $this->execinterval = $data->execinterval;
        }

        if (isset($data->modulekey)) {
            $this->modulekey = $data->modulekey;
        }
    }

    /**
     * Zeitpunkt der nächsten Ausführung berechnen
     * getLastExecTime() + getIntervalTime()
     * @return int
     */
    public function getNextExecTime()
    {
        if (!$this->lastExecTime) {
            return time();
        }

        return $this->getLastExecTime() + $this->getIntervalTime();
    }

    /**
     * Aktualisiert einen Artikel in der Datenbank
     * @return bool
     */
    public function update()
    {
        return $this->dbcon->update($this->table, ['execinterval'], [$this->execinterval, $this->cronName], 'cjname = ?');
    }

    /**
     * Check is cronjob is running
     * @return bool
     */
    public function isRunning()
    {
        return $this->isrunning ? true : false;
    }

    /**
     * Set file option, that cronjob is running
     * @return bool
     */
    public function setRunning()
    {
        $this->isrunning = 1;
        return $this->dbcon->update($this->table, ['isrunning'], [$this->isrunning, $this->cronName], 'cjname=?');        
    }

    /**
     * Removes file option for running cronjon
     * @return bool
     */
    public function setFinished()
    {
        $this->isrunning = 0;
        return $this->dbcon->update($this->table, ['isrunning'], [$this->isrunning, $this->cronName], 'cjname=?');        
    }

    /**
     * Gibt Klassen-Namepsace für Cronjob-Klassen zurück
     * @param string $cronId
     * @return string
     * @since FPCM 3.3
     */
    public static function getCronNamespace($cronId)
    {
        return "\\fpcm\\model\\crons\\{$cronId}";
    }

}
