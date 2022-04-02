<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli syscheck module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.5.1
 */
final class syscheck extends \fpcm\model\abstracts\cli {

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {
        \fpcm\classes\loader::getObject('\fpcm\classes\language', FPCM_DEFAULT_LANGUAGE_CODE, false);

        $sysCheckAction = new \fpcm\controller\ajax\system\syscheck();
        $rows = $sysCheckAction->processCli();

        $lines = [PHP_EOL];

        $progress = new progress(count($rows));
        $progress->setOutputText('Fetch data for system check');

        $i = 1;

        /* @var $data \fpcm\model\system\syscheckOption */
        foreach ($rows as $descr => $data) {
            
            $progress->setCurrentValue($i++)->output();

            $lines[] = $data->asString(strip_tags($descr));
            usleep(50000);
        }

        $this->output(PHP_EOL . 'System check successfuly. Here are the results:');
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
