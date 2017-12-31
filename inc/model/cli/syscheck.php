<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\cli;

    /**
     * FanPress CM cli syscheck module
     * 
     * @package fpcm\model\cli
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5.1
     */
    final class syscheck extends \fpcm\model\abstracts\cli {

        /**
         * Modul ausführen
         * @return void
         */
        public function process() {

            \fpcm\classes\baseconfig::$fpcmLanguage = new \fpcm\classes\language('en');
            
            $sysCheckAction = new \fpcm\controller\ajax\system\syscheck();
            $rows = $sysCheckAction->processCli();

            $this->output(PHP_EOL.'Executing system check...'.PHP_EOL);
            
            $lines = [];
            foreach ($rows as $descr => $data) {

                print '.';
                
                $line = array(
                    '> '.strip_tags($descr),
                    '   current value     : '.(string) $data['current'],
                    '   recommended value : '.(string) $data['recommend'],
                    '   result            : '.($data['result'] ? 'OK' : '!!'),
                    isset($data['notice']) && trim($data['notice']) ? ' '.$data['notice'].PHP_EOL : ''
                );
                
                $lines[] = implode(PHP_EOL, $line);

                usleep(50000);
                
            }

            $this->output(PHP_EOL.PHP_EOL.'System check executed, results are:'.PHP_EOL);
            usleep(250000);

            $this->output($lines);

        }

        /**
         * Hilfe-Text zurückgeben ausführen
         * @return array
         */
        public function help() {
            $lines   = [];
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
