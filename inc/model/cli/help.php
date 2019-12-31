<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli help module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5.1
 */
final class help extends \fpcm\model\abstracts\cli {

    private $exclude = ['help', 'io'];

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {
        $module = isset($this->funcParams[0]) ? $this->funcParams[0] : false;

        $files = glob(__DIR__ . '/*.php');

        $lines = $module && !in_array($module, $this->exclude) ?  [] : array_merge([''], $this->help());
        foreach ($files as $file) {

            $file = basename($file, '.php');
            
            if ($module !== false && $file !== $module) {
                continue;
            }

            $moduleClass = '\\fpcm\model\\cli\\' . $file;

            if (!class_exists($moduleClass) || in_array($file, $this->exclude)) {
                continue;
            }

            $cli = new $moduleClass([]);
            $lines = array_merge($lines, $cli->help());
            $lines[] = '';
        }
        
        if (!count($lines)) {
            $this->output('Invalid module name, no help data found.', true);
        }

        $lines[] = '';
        $lines[] = '';

        $this->output($lines);
    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {

        $lines = [];
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
        $lines[] = '      php fpcmcli.php help <module>';
        $lines[] = '';
        return $lines;
    }

}
