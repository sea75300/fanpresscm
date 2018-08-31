<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\module;

/**
 * Module-Event-Base-Class
 * @package fpcm\module
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
abstract class event {

    /**
     * Module key
     * @var string
     */
    protected $key = '';

    /**
     * Event data
     * @var mixed
     */
    protected $data = '';

    /**
     * Konstruktor
     * @param mixed $data
     */
    final public function __construct($data)
    {
        $this->key = explode('\\events', module::getKeyFromClass(get_class($this)), 2)[0];
        $this->data = $data;

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
