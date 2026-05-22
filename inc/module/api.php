<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Module api wrapper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\module
 */
class api extends \fpcm\model\abstracts\staticModel {

    use tools;
    
    /**
     * Constructor
     * @ignore
     */
    final public function __construct()
    {
        parent::__construct();
    }

    /**
     * Intialize function
     * @return void
     */
    public function init() : void
    {
        return;
    }

    /**
     * Init function
     * @ignore
     */
    final public function initConstruct()
    {
        $this->init();
    }

    /**
     * dynamic call
     * @param type $name
     * @param type $arguments
     * @return bool
     * @ignore
     */
    final public function __call($name, $arguments)
    {
        throw new \Exception(sprintf("Undefined function calls %s in %s", $name, __CLASS__));
    }

    /**
     * dynamic static call
     * @param type $name
     * @param type $arguments
     * @return bool
     * @ignore
     */
    final public static function __callStatic($name, $arguments)
    {
        throw new \Exception(sprintf("Undefined function calls %s in %s", $name, __CLASS__));
    }
}
