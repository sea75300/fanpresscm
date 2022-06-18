<?php

/**
 * FanPress CM clear log files Cronjob
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\crons;

/**
 * Cronjob system logs cleanup
 * 
 * @package fpcm\model\crons
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class clearLogs extends \fpcm\model\abstracts\cron {

    /**
     * Logfile-Größe, bei der automatisch bereinigt werden soll >= 1MB = 1 * 1024 * 1024
     * @var int
     */
    protected $maxsize = 1048576;

    /**
     * Auszuführender Cron-Code
     */
    public function run()
    {
        $dateStr = date('Ymd') . '.txt';
        
        clearstatcache();
        
        $list = array_filter(\fpcm\model\files\logfile::getLogMap(), function ($filename) {
            
            if (!file_exists($filename)) {
                return false;
            }

            if (filesize($filename) < $this->maxsize) {
                return false;
            }

            return true;
        });
        
        if (!is_array($list) || !count($list)) {
            fpcmLogCron('No logs to cleanup, exit.');
            return true;
        }

        foreach ($list as $key => $filename) {
            
            fpcmLogCron("Cleanup {$key} log in: " . \fpcm\model\files\ops::removeBaseDir($filename));
            
            if ($key !== \fpcm\model\files\logfile::FPCM_LOGFILETYPE_SESSION) {
                copy($filename, $filename . '.' . $dateStr);
            }

            $this->clear($key);
        }

        return true;
    }

    /**
     * Log-Datei leeren
     * @param int $log
     * @return bool
     */
    private function clear($log)
    {
        if ($log === \fpcm\model\files\logfile::FPCM_LOGFILETYPE_SESSION) {
            return \fpcm\classes\loader::getObject('\fpcm\model\system\session')->clearSessions();
        }

        $logfile = new \fpcm\model\files\logfile($log);
        return $logfile->clear();
    }

}
