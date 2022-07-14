<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM cli config module
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.5.1
 */
final class config extends \fpcm\model\abstracts\cli {

    /**
     * Modul ausführen
     * @return void
     */
    public function process()
    {

        if (!isset($this->funcParams[1])) {
            $this->output('Invalid params, no option set', true);
        }

        switch ($this->funcParams[0]) {

            case self::PARAM_ENABLE :

                switch ($this->funcParams[1]) {
                    case 'cronjobs' :

                        \fpcm\classes\baseconfig::enableAsyncCronjobs(true);
                        if (\fpcm\classes\baseconfig::asyncCronjobsEnabled()) {
                            $this->output('Asynchronous cronjob execution enabled!');
                        } else {
                            $this->output('Failed to enable asynchronous cronjob execution!');
                        }

                        break;

                    case 'installer' :

                        \fpcm\classes\baseconfig::enableInstaller(true);
                        if (\fpcm\classes\baseconfig::installerEnabled()) {
                            $this->output('Installer reenabled!');
                        } else {
                            $this->output('Failed to reenable installer!');
                        }

                        break;

                    case 'maintenance' :

                        if (\fpcm\classes\loader::getObject('\fpcm\model\system\config')->setMaintenanceMode(1)) {
                            $this->output('Maintenance mode enabled!');
                        } else {
                            $this->output('Failed to enable maintenance mode!');
                        }

                        break;

                    default:
                        break;
                }

                break;

            case self::PARAM_DISABLE :

                switch ($this->funcParams[1]) {
                    case 'cronjobs' :

                        \fpcm\classes\baseconfig::enableAsyncCronjobs(false);
                        if (!\fpcm\classes\baseconfig::asyncCronjobsEnabled()) {
                            $this->output('Asynchronous cronjob execution disabled!');
                        } else {
                            $this->output('Failed to disable asynchronous cronjob execution!');
                        }

                        break;

                    case 'installer' :

                        \fpcm\classes\baseconfig::enableInstaller(false);
                        if (!\fpcm\classes\baseconfig::installerEnabled()) {
                            $this->output('Installer disabled!');
                        } else {
                            $this->output('Failed to disable installer!');
                        }

                        break;

                    case 'maintenance' :

                        if (\fpcm\classes\loader::getObject('\fpcm\model\system\config')->setMaintenanceMode(0)) {
                            $this->output('Maintenance mode disabled!');
                        } else {
                            $this->output('Failed to disable maintenance mode!');
                        }

                    default:
                        break;
                }

                break;

            default:
                break;
        }

        return true;
    }

    /**
     * Hilfe-Text zurückgeben ausführen
     * @return array
     */
    public function help()
    {
        $lines = [];
        $lines[] = '> Configuration:';
        $lines[] = '';
        $lines[] = 'Usage: php (path to FanPress CM/)fpcmcli.php config <action params> <config_value>';
        $lines[] = '';
        $lines[] = '    Action params:';
        $lines[] = '';
        $lines[] = '      --enable      enable option';
        $lines[] = '      --disable     disable option';
        $lines[] = '';
        $lines[] = '    Options:';
        $lines[] = '';
        $lines[] = '      cronjobs      asynchronous cronjob execution';
        $lines[] = '      installer     installer';
        $lines[] = '      maintenance   maintenance mode';
        return $lines;
    }

}
