<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\model\abstracts;

    /**
     * FanPress CM cli object
     * 
     * @package fpcm\model\system
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.3
     */
    abstract class cli extends \fpcm\model\abstracts\staticModel {

        /**
         * CLI param: --install
         */
        const FPCMCLI_PARAM_INSTALL = '--install';

        /**
         * CLI param: --update
         */
        const FPCMCLI_PARAM_UPDATE  = '--update';

        /**
         * CLI param: --upgrade
         */
        const FPCMCLI_PARAM_UPGRADE = '--upgrade';

        /**
         * CLI param: --upgrade-db
         */
        const FPCMCLI_PARAM_UPGRADE_DB = '--upgrade-db';

        /**
         * CLI param: --remove
         */
        const FPCMCLI_PARAM_REMOVE  = '--remove';

        /**
         * CLI param: --exec
         */
        const FPCMCLI_PARAM_EXEC    = '--exec';

        /**
         * CLI param: --clean
         */
        const FPCMCLI_PARAM_CLEAN   = '--clean';

        /**
         * CLI param: --clear
         */
        const FPCMCLI_PARAM_CLEAR   = '--clear';

        /**
         * CLI param: --info
         */
        const FPCMCLI_PARAM_INFO    = '--info';

        /**
         * CLI param: --list
         */
        const FPCMCLI_PARAM_LIST    = '--list';

        /**
         * CLI param: --size
         */
        const FPCMCLI_PARAM_SIZE    = '--size';

        /**
         * CLI param: --enable
         */
        const FPCMCLI_PARAM_ENABLE  = '--enable';

        /**
         * CLI param: --disable
         */
        const FPCMCLI_PARAM_DISBALE = '--disable';

        /**
         * CLI param: --passwd
         */
        const FPCMCLI_PARAM_PASSWD = '--passwd';

        /**
         * CLI param: package manager type: system
         */
        const FPCMCLI_PARAM_TYPE_SYSTEM    = 'system';

        /**
         * CLI param: package manager type: module
         */
        const FPCMCLI_PARAM_TYPE_MODULE    = 'module';

        /**
         * Funktionsparameter
         * @var array
         */
        protected $funcParams = [];

        /**
         * Konstruktor
         * @param array $funcParams
         */
        public function __construct($funcParams) {
            
            parent::__construct();
            $this->funcParams = $funcParams;
        }

        /**
         * Modul ausführen
         * @return void
         */
        abstract public function process();

        /**
         * Hilfe-Text zurückgeben ausführen
         * @return array
         */
        abstract public function help();

        /**
         * CLI Ausgabe
         * @param string $str
         * @param bool $die
         */
        protected function output($str, $die = false) {

            if (is_array($str)) {
                $str = implode(PHP_EOL, $str);
            }
            
            if ($die) {
                die($str.PHP_EOL);
            }

            print $str.PHP_EOL;

        }

        /**
         * CLI Ausgabe
         * @param string $str
         * @param bool $die
         */
        protected function debug($str, $die = false) {

            if (is_array($str)) {
                $str = print_r($str, true);
            }

            $this->output($str, $die);

        }

        /**
         * CLI Eingabe
         * @param string $str
         * @param mixed
         */
        protected function input($str) {
            return readline($str.' ');
        }
    }
