<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli syscheck module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5.1
 */
final class syscheck extends \fpcm\model\abstracts\cli {

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {
        \fpcm\classes\loader::getObject('\fpcm\classes\language', 'en', false);

        $sysCheckAction = new \fpcm\controller\ajax\system\syscheck();
        $rows = $sysCheckAction->processCli();

        $this->output(PHP_EOL . 'Fetch data for system check...' . PHP_EOL);

        $lines = [PHP_EOL];

        /* @var $data \fpcm\model\system\syscheckOption */
        foreach ($rows as $descr => $data) {
            print '.';
            $lines[] = $data->asString(strip_tags($descr));
            usleep(50000);
        }

        $this->output(PHP_EOL . PHP_EOL . 'System check successfuly. Here are the results:' . PHP_EOL);
        usleep(250000);

        $this->output($lines);
    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {
        $lines = [];
        $lines[] = '> System check:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php syscheck';
        $lines[] = '';
        $lines[] = '    - The system check has no params to set.';
        $lines[] = '    - Executing the system check via FanPress CM CLI may result in wrong "current" values and check results';
        $lines[] = '      for "PHP memory limit" and "PHP max execution time" due to different settings for web and CLI access in php.ini.';
        return $lines;
    }

}
