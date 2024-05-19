<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Module-Event-Base-Class
 * @package fpcm\module
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
abstract class event {
    
    use tools;

    /**
     * Event data
     * @var mixed
     */
    protected $data = '';

    /**
     * Extra event params
     * @var array
     * @since 5.2.0-rc3
     */
    protected array $params = [];

    /**
     * Konstruktor
     * @param mixed $data
     */
    final public function __construct($data)
    {
        $this->data = $data;
        if (func_num_args() > 1) {
            $this->params = func_get_args();
            array_shift($this->params);
        }        

        $this->init();
    }
        
    /**
     * Execute event
     * @return mixed
     */
    abstract public function run();


    /**
     * Initialize event
     * @return bool
     */
    abstract public function init();

}
