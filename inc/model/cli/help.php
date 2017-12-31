<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\cli;

    /**
     * FanPress CM cli help module
     * 
     * @package fpcm\model\cli
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5.1
     */
    final class help extends \fpcm\model\abstracts\cli {

        /**
         * Modul ausführen
         * @return void
         */
        public function process() {

            $files = glob(__DIR__.'/*.php');

            $lines   = array_merge([''], $this->help());
            foreach ($files as $file) {

                $file = basename($file, '.php');
                
                $moduleClass = '\\fpcm\model\\cli\\'.$file;
                if (!class_exists($moduleClass) || $file === 'help') {
                    continue;
                }

                $cli = new $moduleClass([]);
                $lines = array_merge($lines, $cli->help());
                $lines[] = '';
                
            }

            $lines[] = '';
            $lines[] = '';

            $this->output($lines);
        }

        /**
         * Hilfe-Text zurückgeben ausführen
         * @return array
         */
        public function help() {
            
            $lines   = [];
            $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php <module name> <action params> <additional params>';
            $lines[] = '';
            $lines[] = '> Modules:';
            $lines[] = '';
            $lines[] = '      - cache       cache actions';
            $lines[] = '      - config      action on system configuration';
            $lines[] = '      - help        displays this text';
            $lines[] = '      - installer   runs the system installer on cli';
            $lines[] = '      - logs        logfile actions';
            $lines[] = '      - cron        cronjob actions';
            $lines[] = '      - module      module action';
            $lines[] = '      - pkg         package manager';
            $lines[] = '      - syscheck    system check';
            $lines[] = '      - users       user management';
            $lines[] = '';
            $lines[] = '> Example:';
            $lines[] = '';
            $lines[] = '      php fpcmcli.php help';
            $lines[] = '';
            return $lines;
        }

    }
