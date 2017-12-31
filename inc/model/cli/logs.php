<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\cli;

    /**
     * FanPress CM cli logs module
     * 
     * @package fpcm\model\cli
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5.1
     */
    final class logs extends \fpcm\model\abstracts\cli {

        /**
         * Modul ausführen
         * @return void
         */
        public function process() {

            if (empty($this->funcParams[1])) {
                $this->output('Invalid params', true);
            }
            
            $path = \fpcm\classes\baseconfig::$logDir.$this->funcParams[1].'.txt';
            
            $this->output('--- Logfile: '.\fpcm\model\files\ops::removeBaseDir($path, true).' ---');
            
            if (!file_exists($path) || !in_array($path, \fpcm\classes\baseconfig::$logFiles)) {
                $this->output('Logfile '.\fpcm\model\files\ops::removeBaseDir($path, true).' not found!', true);
            }
            
            if ($this->funcParams[0] === self::FPCMCLI_PARAM_CLEAR && !file_put_contents($path, '') === false) {
                $this->output('Unable to clear logfile '.\fpcm\model\files\ops::removeBaseDir($path, true).'!', true);
            }
            
            $rows = file($path, FILE_SKIP_EMPTY_LINES);
            if (!is_array($rows)) {
                $this->output('Unable to load logfile '.\fpcm\model\files\ops::removeBaseDir($path, true).'!', true);
            }
            
            if (!count($rows)) {
                $this->output('     >> No data available...');
                return true;
            }
            
            $rows = array_map('json_decode', $rows);
            
            $is_pkg_log = ($path === \fpcm\classes\baseconfig::$logFiles['pkglog'] ? true : false);
            if ($this->funcParams[0] === self::FPCMCLI_PARAM_LIST) {

                foreach ($rows as $row) {

                    if (!is_object($row)) {
                        continue;
                    }

                    $this->output('Entry added on: '.$row->time);
                    if ($is_pkg_log) {
                        $this->output('Package name: '.$row->pkgname);
                    }

                    $this->output($row->text);
                    $this->output('-----');

                }

            }
            
            return true;

        }

        /**
         * Hilfe-Text zurückgeben ausführen
         * @return array
         */
        public function help() {
            $lines   = [];
            $lines[] = '> Logfiles:';
            $lines[] = '';
            $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php logs <action params> <logfile name>';
            $lines[] = '';
            $lines[] = '    Action params:';
            $lines[] = '';
            $lines[] = '      --list        show entries of selected logfile';
            $lines[] = '      --clear       clear selected logfile';
            return $lines;
        }

    }
