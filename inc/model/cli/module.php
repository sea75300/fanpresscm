<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\cli;

    /**
     * FanPress CM cli module module
     * 
     * @package fpcm\model\cli
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.5.1
     */
    final class module extends \fpcm\model\abstracts\cli {

        /**
         * Modul ausführen
         * @return void
         */
        public function process() {

            $moduleList = new \fpcm\model\modules\modulelist();
            $list       = $moduleList->getModulesLocal();

            $keyData    = \fpcm\model\packages\package::explodeModuleFileName($this->funcParams[1]);

            if (!array_key_exists($keyData[0], $list)) {
                $this->output('The requested module was not found in local module storage. Check your module key.', true);
            }

            /* @var $module \fpcm\model\modules\listitem */
            $module = $list[$keyData[0]];                    
            if (!$module->isInstalled()) {
                $this->output('The selected module is not installed. Exiting...', true);
            }

            if ($this->funcParams[0] === self::FPCMCLI_PARAM_ENABLE) {
                if (!$moduleList->enableModules(array($keyData[0]))) {
                    $this->output('Unable to enable module '.$keyData[0], true);
                }

                $this->output('Module '.$keyData[0].' was enabled successfully.');

            }

            if ($this->funcParams[0] === self::FPCMCLI_PARAM_DISBALE) {
                if (!$moduleList->disableModules(array($keyData[0]))) {
                    $this->output('Unable to disable module '.$keyData[0], true);
                }

                $this->output('Module '.$keyData[0].' was disableed successfully.');
            }
            
            return true;

        }

        /**
         * Hilfe-Text zurückgeben ausführen
         * @return array
         */
        public function help() {
            $lines   = [];
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
