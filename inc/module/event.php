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
    
    use tools;

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
