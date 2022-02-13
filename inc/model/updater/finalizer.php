<?php

/**
 * System update finalizer object
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\updater;

/**
 * System Update Finalizer Objekt
 * 
 * @package fpcm\model\updater
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class finalizer extends \fpcm\model\abstracts\model {

    /**
     * CLI progress
     * @var \fpcm\model\cli\progress
     */
    private $cliProgress = false;

    /**
     * Initialisiert System Update
     * @param int $init
     */
    public function __construct()
    {
        parent::__construct();
        $this->config = new \fpcm\model\system\config();
    }

    /**
     * Führt abschließende Update-Schritte aus
     * @return bool
     */
    public function runUpdate()
    {
        if (!$this->runMigrations()) {
            return false;
        }
        
        if (!$this->updateVersion()) {
            return false;
        }

        if (\fpcm\classes\baseconfig::canConnect()) {
            (new \fpcm\model\crons\updateCheck())->run();
        }

        return true;
    }

    /**
     * 
     * @return bool
     * @since 4.5-b3
     */
    private function runMigrations() : bool
    {
        $migrations = glob(\fpcm\classes\dirs::getIncDirPath('migrations/v*.php'));

        if (!is_array($migrations)) {
            return true;
        }

        array_walk($migrations, function (&$item) {
            $item ='fpcm\\migrations\\' . basename($item, '.php');
        });

        $migrations = array_filter($migrations, function ($class) {
            return class_exists($class);
        });

        if (!count($migrations)) {
            return true;
        }

        array_walk($migrations, function (&$item) {
            $item = new $item;
        });

        $migrations = array_filter($migrations, function ($obj) {
            return $obj->isRequired();
        });

        
        if (!count($migrations)) {
            
            fpcmLogSystem('Executing default migration...');

            $migrations = [
                new \fpcm\migrations\defaultAll()
            ];
        }
        
        $this->cliProgress = new \fpcm\model\cli\progress(count($migrations));

        $i = 1;

        /* @var $migration \fpcm\migrations\migration */
        foreach ($migrations as $migration) {

            $this->cliProgress->setCurrentValue($i)->output();
            
            if (!$migration->process()) {
                trigger_error('Processing of migration '. get_class($migration).' failed!.', E_USER_ERROR);
                return false;
            }

            $i++;
        }
        
        return true;
    }

    /**
     * aktualisiert Versionsinfos in Datenbank
     * @return bool
     */
    private function updateVersion()
    {
        $this->config->setNewConfig([
            'system_version' => \fpcm\classes\baseconfig::getVersionFromFile()
        ]);

        return $this->config->update();
    }

}
