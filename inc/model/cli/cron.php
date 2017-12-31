<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\cli;

    /**
     * FanPress CM cli cron module
     * 
     * @package fpcm\model\cli
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5.1
     */
    final class cron extends \fpcm\model\abstracts\cli {

        /**
         * Modul ausführen
         * @return void
         */
        public function process() {

            if ($this->funcParams[0] === self::FPCMCLI_PARAM_EXEC) {

                $cjClassName = "\\fpcm\\model\\crons\\{$this->funcParams[1]}";

                /* @var $cronjob \fpcm\model\abstracts\cron */
                $cronjob = new $cjClassName($this->funcParams[1]);

                if (!is_a($cronjob, '\fpcm\model\abstracts\cron')) {
                    $this->output("Cronjob class {$this->funcParams[1]} must be an instance of \"\fpcm\model\abstracts\cron\"!", true);             
                }

                $this->output('Execute cronjob '.$this->funcParams[1]);
                $success = $cronjob->run();

                $cronjob->updateLastExecTime();

                $this->output('Cronjob execution finished. Returned code: '.$success);

            }
            
            return true;

        }

        /**
         * Hilfe-Text zurückgeben ausführen
         * @return array
         */
        public function help() {
            $lines   = [];
            $lines[] = '> Cronjobs:';
            $lines[] = '';
            $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php cron <action params> <cronjob name>';
            $lines[] = '';
            $lines[] = '    Action params:';
            $lines[] = '';
            $lines[] = '      --exec        executes given cronjob';
            return $lines;
        }

    }
