<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\abstracts;

/**
 * FanPress CM cli object
 * 
 * @package fpcm\model\abstracts
 * @abstract
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.3
 */
abstract class cli extends \fpcm\model\abstracts\staticModel {

    /**
     * CLI param: --install
     */
    const PARAM_INSTALL = '--install';

    /**
     * CLI param: --update
     */
    const PARAM_UPDATE = '--update';

    /**
     * CLI param: --upgrade
     */
    const PARAM_UPGRADE = '--upgrade';

    /**
     * CLI param: --upgrade-db
     */
    const PARAM_UPGRADE_DB = '--upgrade-db';

    /**
     * CLI param: --remove
     */
    const PARAM_REMOVE = '--remove';

    /**
     * CLI param: --exec
     */
    const PARAM_EXEC = '--exec';

    /**
     * CLI param: --clean
     */
    const PARAM_CLEAN = '--clean';

    /**
     * CLI param: --clear
     */
    const PARAM_CLEAR = '--clear';

    /**
     * CLI param: --info
     */
    const PARAM_INFO = '--info';

    /**
     * CLI param: --list
     */
    const PARAM_LIST = '--list';

    /**
     * CLI param: --size
     */
    const PARAM_SIZE = '--size';

    /**
     * CLI param: --enable
     */
    const PARAM_ENABLE = '--enable';

    /**
     * CLI param: --disable
     */
    const PARAM_DISABLE = '--disable';

    /**
     * CLI param: --exsystem
     * @ignore
     */
    const PARAM_RESET = '--reset';

    /**
     * CLI param: --passwd
     */
    const PARAM_PASSWD = '--passwd';

    /**
     * CLI param: --chgroll
     */
    const PARAM_CHGROLL = '--chgroll';

    /**
     * CLI param: --chgroll
     */
    const PARAM_LISTROLLS = '--listrolls';

    /**
     * CLI param: --exsystem
     * @ignore
     */
    const PARAM_EXECSYSTEM = '--exsystem';

    /**
     * CLI param: package manager type: system
     */
    const PARAM_TYPE_SYSTEM = 'system';

    /**
     * CLI param: package manager type: module
     */
    const PARAM_TYPE_MODULE = 'module';

    /**
     * Funktionsparameter
     * @var array
     */
    protected $funcParams = [];

    /**
     * Konstruktor
     * @param array $funcParams
     */
    public function __construct($funcParams)
    {
        parent::__construct();
        $this->funcParams = $funcParams;
        
        if (defined('FPCM_DEBUG') && FPCM_DEBUG) {
            $this->output('> CLI DEBUG: ');
            $this->debug($this->funcParams);
        }
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
     * CLI output
     * @param string $str
     * @param bool $exit
     */
    protected function output($str, $exit = false)
    {
        \fpcm\model\cli\io::output($str, $exit);
    }

    /**
     * CLI debug output
     * @param string $str
     * @param bool $exit
     */
    protected function debug($str, $exit = false)
    {
        if (is_array($str)) {
            $str = print_r($str, true);
        }

        $this->output($str, $exit);
    }

    /**
     * CLI input
     * @param string $str
     * @param mixed
     */
    protected function input($str)
    {
        return \fpcm\model\cli\io::input($str);
    }

    /**
     * Returns text by bool value
     * @param bool $value
     * @return string
     */
    protected function boolText($value)
    {
        return (bool) $value ? 'yes' : 'no';
    }

}
