<?php
/**
 * FanPress CM 3.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

require_once __DIR__.'/inc/common.php';

/**
 * FanPress CM cli class
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcmcli
 * @since FPCM 3.3
 */    
class fpcmCLI {

    /**
     * CLI-params
     * @var array
     */
    private $params = [];

    /**
     * Konstruktor, prüft PHP-Version, Installer-Status und Datenbank-Config-Status
     * @param array $params
     */
    public function __construct(array $params) {

        $title = 'FanPress CM '.\fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_version.' command line ';
        
        if (!\fpcm\classes\baseconfig::isCli()) {
            $this->output($title.' must be run from console!', true);
        }
        
        $this->output(PHP_EOL.'--- '.$title.' ---');

        if (version_compare(PHP_VERSION, FPCM_PHP_REQUIRED, '<')) {
            $this->output($title.' requires PHP '.FPCM_PHP_REQUIRED.' or better!', true);
        }

        if (!count($params)) {
            $this->output($title.' requires at least on parameter.', true);
        }
        
        $this->params = $params;

    }

    /**
     * Befehlt an CLI ausführen
     * @param array $params
     */
    public function process() {

        $moduleClass = '\\fpcm\model\\cli\\'. preg_replace('/([^A-Za-z0-9\_]+)/is', '', trim($this->params[0]));
        if (!$moduleClass || !class_exists($moduleClass)) {
            $this->output('Invalid module given.', true);
        }

        $cli = new $moduleClass(array_slice($this->params, 1));
        $cli->process();
        
    }

    /**
     * Ausgabe der CLI
     * @param string $str
     * @param bool $die
     */
    final private function output($str, $die = false) {
        
        if ($die) {
            exit($str.PHP_EOL);
        }

        print $str.PHP_EOL;

    }
    
}

if (!isset($argv)) {
    $argv = [];
}

$cli = new fpcmCLI(array_slice($argv, 1));
$cli->process();