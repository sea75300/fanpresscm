<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli cron module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.5.1
 */
final class cron extends \fpcm\model\abstracts\cli {

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {
        $cjClassName    = in_array($this->funcParams[0], [self::PARAM_EXEC, self::PARAM_RESET])
                        ? "\\fpcm\\model\\crons\\{$this->funcParams[1]}"
                        : false;

        if (!$cjClassName) {
            return true;
        }

        /* @var $cronjob \fpcm\model\abstracts\cron */
        $cronjob = new $cjClassName($this->funcParams[1]);
        if (!is_a($cronjob, '\fpcm\model\abstracts\cron')) {
            $this->output("Cronjob class {$this->funcParams[1]} must be an instance of \"\fpcm\model\abstracts\cron\"!", true);
        }
                        
        if ($this->funcParams[0] === self::PARAM_EXEC) {
            $this->execCron($cronjob);
        }
                        
        if ($this->funcParams[0] === self::PARAM_RESET) {
            $this->resetCron($cronjob);
        }

        return true;
    }

    /**
     * Executed cronjob
     * @param \fpcm\model\abstracts\cron $cronjob
     */
    private function execCron($cronjob)
    {
        $this->output('Execute cronjob ' . $this->funcParams[1]);
        $this->output('Cronjob execution finished. Returned code: ' . $cronjob->run());
        $cronjob->updateLastExecTime();
        return true;
    }

    /**
     * Resets cronjob execution state data
     * @param \fpcm\model\abstracts\cron $cronjob
     */
    private function resetCron($cronjob)
    {
        $this->output('Reset running flag for cronjob ' . $this->funcParams[1].'. Running: '. $this->boolText($cronjob->isRunning()) );
        $this->output('Reset of cronjob finished. Returned code: ' .(int) ($cronjob->isRunning() && $cronjob->setFinished()) );
        $cronjob->updateLastExecTime();
        return true;
    }

    /**
     * Returns help text array
     * @return array
     */
    public function help()
    {
        $lines = [];
        $lines[] = '> Cronjobs:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php cron <action params> <cronjob name>';
        $lines[] = '';
        $lines[] = '    Action params:';
        $lines[] = '';
        $lines[] = '      --exec        executes given cronjob';
        $lines[] = '      --reset       resets cronjob running state data';
        return $lines;
    }

}
