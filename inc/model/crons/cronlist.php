<?php

/**
 * FanPress CM Cronjob list
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\crons;

/**
 * Cronjob list object
 * 
 * @package fpcm\model\crons
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class cronlist extends \fpcm\model\abstracts\staticModel {

    /**
     * DB-Verbindung
     * @var \fpcm\classes\database
     */
    protected $dbcon;

    /**
     * Konstruktor
     */
    public function __construct()
    {
        $this->events = \fpcm\classes\loader::getObject('\fpcm\events\events');
    }
    
    /**
     * Register cronjob for execution
     * @param string $cronName
     * @param bool $async
     * @param string $module
     * @return boolean
     */
    public function registerCron($cronName, $async = false, $module = false)
    {
        $cronName   = $module
                    ? \fpcm\module\module::getCronNamespace($module, $cronName)
                    : \fpcm\model\abstracts\cron::getCronNamespace($cronName);
        if (!class_exists($cronName)) {
            trigger_error("Undefined cronjon {$cronName} called");
            return false;
        }

        /* @var $cron \fpcm\model\abstracts\cron */
        $cron = new $cronName();
        if (!($cron instanceof \fpcm\model\abstracts\cron)) {
            trigger_error("Cronjob class {$cronName} must be an instance of \"\fpcm\model\abstracts\cron\"!");
            return false;
        }

        if ($async && !$cron->getRunAsync()) {
            return false;
        }

        if (!$cron->checkTime()) {
            return null;
        }
        
        if ($cron->isRunning()) {
            fpcmLogCron('Skip execution "' . $cron->getCronName() . '", cronjob already running...');
            return true;
        }

        fpcmLogCron('Start cronjob "' . $cron->getCronName() . '"...');
        \fpcm\classes\timer::start(__METHOD__);

        $cron->setAsyncCurrent($async);
        $cron->setRunning();
        $cron->run();
        $cron->setFinished();
        $cron->updateLastExecTime();

        fpcmLogCron('Finished cronjob "' . $cron->getCronName() . '" in ' . \fpcm\classes\timer::cal(__METHOD__) . ' sec');

        if ($cron->getReturnData() !== null) {
            return $cron->getReturnData();
        }

        return true;
    }

    /**
     * Cronjob zur Ausführung via AJAX registrieren
     * @param \fpcm\model\abstracts\cron $cron
     * @return bool
     * @since 3.2.0
     */
    public function registerCronAjax(\fpcm\model\abstracts\cron $cron)
    {
        if (!$cron->getRunAsync()) {
            return false;
        }

        if ($cron->isRunning()) {
            fpcmLogCron('Skip execution "' . $cron->getCronName() . '", cronjob already running...');
            return true;
        }

        fpcmLogCron('Start cronjob "' . $cron->getCronName() . '" via AJAX...');
        \fpcm\classes\timer::start(__METHOD__);

        $cron->setRunning();
        $cron->run();
        $cron->setFinished();
        $cron->updateLastExecTime();
        usleep(500000);

        fpcmLogCron('Finished cronjob "' . $cron->getCronName() . '" via AJAX in ' . \fpcm\classes\timer::cal(__METHOD__) . ' sec');

        return true;
    }

    /**
     * Returns a list of cronjobs to be executed within the current request
     * @return array
     * @since 3.2.0
     */
    public function getExecutableCrons()
    {
        if (!$this->dbcon instanceof \fpcm\classes\database) {
            $this->dbcon = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        }

        $obj = (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableCronjobs))
                ->setWhere('(lastexec+execinterval) < ? AND execinterval > -1')
                ->setParams([time()])
                ->setFetchAll(true);

        return $this->getResult($this->dbcon->selectFetch($obj), true);        
        
    }

    /**
     * Returns a list of all registered cronjobs
     * @return array
     * @since 4.3
     */
    public function getAllCrons() : array
    {
        if (!$this->dbcon instanceof \fpcm\classes\database) {
            $this->dbcon = \fpcm\classes\loader::getObject('\fpcm\classes\database');
        }

        $obj = (new \fpcm\model\dbal\selectParams(\fpcm\classes\database::tableCronjobs))
                ->setWhere('1=1 ' . $this->dbcon->orderBy(['isrunning DESC', 'cjname ASC']) )
                ->setFetchAll(true);

        return $this->getResult($this->dbcon->selectFetch($obj));
    }
    
    /**
     * Creates result list
     * @param array $values
     * @param bool $activeOnly
     * @return array
     * @since 4.3
     */
    private function getResult(array $values, $activeOnly = false) : array
    {
        if (!count($values)) {
            return [];
        }

        $list = [];
        foreach ($values as $value) {

            if ($activeOnly && $value->modulekey && !(new \fpcm\module\module($value->modulekey))->isActive()) {
                continue;
            }

            $cronName   = trim($value->modulekey)
                        ? \fpcm\module\module::getCronNamespace($value->modulekey, $value->cjname)
                        : \fpcm\model\abstracts\cron::getCronNamespace($value->cjname);

            if (!class_exists($cronName)) {
                trigger_error("Cronjob class {$cronName} not found!");
                continue;
            }

            /**
             * @var \fpcm\model\abstracts\cron
             */
            $cron = new $cronName(false);

            if (!$cron instanceof \fpcm\model\abstracts\cron) {
                trigger_error("Cronjob class {$cronName} must be an instance of \"\fpcm\model\abstracts\cron\"!");
                return false;
            }

            $cron->createFromDbObject($value);

            $list[] = $cron;
        }

        return $list;
    }

}
