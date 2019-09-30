<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli module module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.5.1
 */
final class module extends \fpcm\model\abstracts\cli {

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {
        $module = new \fpcm\module\module($this->funcParams[1]);
        if (!$module->isInstalled()) {
            $this->output('The selected module is not installed. Exiting...', true);
        }
        
        if ($this->funcParams[0] === self::PARAM_ENABLE) {
            
            if (!$module->enable()) {
                $this->output('Failed to enable module ' . $module->getKey(), true);
            }

            $this->output('Module successfuly enabled.');
            return true;
        }
        
        if ($this->funcParams[0] === self::PARAM_DISABLE) {
            
            if (!$module->disable()) {
                $this->output('Failed to disable module ' . $module->getKey(), true);
            }

            $this->output('Module successfuly disabled.');
            return true;
        }
        
        $this->output('Invalid parameters', true);
        return true;

    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {
        $lines = [];
        $lines[] = '> Modules:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php module <action params> <module key>';
        $lines[] = '';
        $lines[] = '    Action params:';
        $lines[] = '';
        $lines[] = '      --enable      enable module';
        $lines[] = '      --disable     disable module';
        return $lines;
    }

}
