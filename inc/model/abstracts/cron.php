<?php

/**
 * FanPress CM 5.x
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
 * @abstract
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class cron implements \fpcm\model\interfaces\cron {

    use \fpcm\model\traits\preparedObjectFields;
    
    const string ERROR_DESTRUCT = 'CE_DESTRUCT_CALL';
    
    /**
     * Datenbank-Objekt
     * @var \fpcm\classes\database
     */
    protected ?database $dbcon = null;

    /**
     * Database table
     * @var string
     */
    protected string $table = '';

    /**
     * Events object
     * @var \fpcm\events\events
     */
    protected ?\fpcm\events\events $events = null;

    /**
     * Name des Crons
     * @var string
     */
    protected string $cronName = '';

    /**
     * Zeitpunkt der letzten Ausführung
     * @var int
     */
    protected int $lastExecTime = 0;

    /**
     * Interval der Ausführung
     * @var int
     * @since 3.2.0
     */
    protected int $execinterval = 0;

    /**
     * Module key string
     * @var string
     * @since 4.3.0
     */
    protected string $modulekey = '';

    /**
     * Cronjob is running
     * @var bool
     * @since 4.5.0-a1
     */
    protected bool|int $isrunning = false;

    /**
     * Error code string
     * @var string
     */
    protected string $error_code = '';

    /**
     * asynchrone Ausführung über cronasync-AJAX-Controller deaktivieren,
     * false wenn cronasync-AJAX nicht ausgführt werden soll
     * @var bool
     */
    protected bool $runAsync = true;

    /**
     * Daten, die von Cronjob zurückgegeben werden sollen
     * @var mixed
     */
    protected mixed $returnData = null;

    /**
     * Wird Cronjob aktuell asynchron ausgeführt
     * @var bool
     */
    protected bool $asyncCurrent = false;

    /**
     * add execution parameters
     * @var array
     * @since 5.0.0-b3
     */
    protected array $execParams = [];

    /**
     * Object properties to be excluded indatabase statements
     * @var array
     */
    protected $dbExcludes = ['cronName'];
    
    /**
     * Konstruktor
     * @param bool $init
     */
    public function __construct($init = true)
    {
        $this->table = database::tableCronjobs;
        $this->dbcon = loader::getObject('\fpcm\classes\database');
        $this->events = loader::getObject('\fpcm\events\events');
        $this->cronName = basename(str_replace('\\', DIRECTORY_SEPARATOR, static::class));

        if (!$init) {
            return;
        }

        $this->init();
    }

    /**
     * Destructor call if cronjob is still running
     * to set finished and update last exec time
     * @return void
     * @since 5.3.2
     */
    public function __destruct()
    {
        if (!$this->isrunning) {
            return;
        }
        
        fpcmLogCron('Destruct called!');
        
        $this->isrunning = 0;
        $this->lastExecTime = time();
        $this->error_code = static::ERROR_DESTRUCT;
        $this->update();
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
     * Returns translatetable language variable from cronjob names
     * @param string $label
     * @return string
     */
    public function getCronNameLangVar(string $label = '') : string
    {
        $return = 'CRONJOB_' . $label . strtoupper($this->cronName);
        if ($this->modulekey) {
            $return = \fpcm\module\module::getLanguageVarPrefixed($this->modulekey) . $return;
        }

        return $return;
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
            ->setItem('lastexec, execinterval, isrunning, modulekey, error_code')
            ->setWhere('cjname = ?')
            ->setParams([$this->cronName])
        );

        $this->lastExecTime = intval($res->lastexec ?? 0);
        $this->execinterval = intval($res->execinterval ?? 0);
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
            $this->execinterval = (int) $data->execinterval;
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
        if ($this->getIntervalTime() === -1) {
            return '';
        }

        if (!$this->getLastExecTime()) {
            return time();
        }

        return $this->getLastExecTime() + $this->getIntervalTime();
    }

    /**
     * Updates database entry
     * @return bool
     */
    public function update()
    {
        $values = $this->getPreparedSaveParams();
        
        $fields = array_keys($values);
        $values[] = $this->cronName;
        
        
        return $this->dbcon->update($this->table, $fields, $values, 'cjname = ?');
    }

    /**
     * Check is cronjob is running
     * @return bool
     */
    public function isRunning() : bool
    {
        return (bool) $this->isrunning;
    }

    /**
     * Is cronjob running longer than interval
     * @return bool
     * @since 5.2.0
     */
    public function forceCancelation() : bool
    {
        return $this->isrunning && time() > $this->getNextExecTime();
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
     * Creates email from a template
     * @param array $vars
     * @param array $html
     * @param array $fromData
     * @param array $attachments
     * @return bool
     * @since 5.1.0-a1
     */
    protected function submitMailNotification(array $vars = [], bool $html = false, bool $fromData = false, array $attachments = []) : bool
    {
        /* @var $conf \fpcm\model\system\config */
        $conf = \fpcm\model\system\config::getInstance();

        /* @var $lang \fpcm\classes\language */
        $lang = \fpcm\classes\loader::getObject('\fpcm\classes\language');

        $lkey = $this->getCronNameLangVar('MAIL_SUBJECT_');

        $email = new \fpcm\classes\email(
            to: $conf->system_email,
            subject: $lang->translate($lkey),
            html: $html
        );
        
        if (!$email->fromTemplate($this->cronName, $vars, $fromData)) {
            return false;
        }

        if (count($attachments)) {
            $email->setAttachments($attachments);
        }

        return $email->submit();
    }

    /**
     * Sets execution parameter
     * @param array $execParams
     * @since 5.0.0-b3
     */
    final public function setExecParams(array $execParams)
    {
        $this->execParams = $execParams;
    }

    /**
     * Return execution parameter
     * @param string $val
     * @return mixed
     * @since 5.0.0-b3
     */
    final public function getExecParams(string $val = '')
    {
        if (!trim($val)) {
            return $this->execParams;
        }
        
        return $this->execParams[$val] ?? null;
    }

    /**
     * Gibt Klassen-Namepsace für Cronjob-Klassen zurück
     * @param string $cronId
     * @return string
     * @since 3.3
     */
    public static function getCronNamespace($cronId)
    {
        return "\\fpcm\\model\\crons\\{$cronId}";
    }

    /**
     * Returns error code
     * @return string
     */
    public function getErrorCode(): string {
        return $this->error_code;
    }

    /**
     * Set error code
     * @param string $code
     * @return static
     * @since 5.4.0-a1
     */
    public function setErrorCode(string $code): static {
        $this->error_code = $code;
        return $this;
    }


}
