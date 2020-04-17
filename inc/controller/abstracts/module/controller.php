<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\abstracts\module;

/**
 * Module controller base
 * 
 * @package fpcm\module
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @abstract
 * @since FPCM 4.1
 */
class controller extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\module\tools;
    
    /**
     * Konstruktor
     * @return void
     */
    final public function __construct()
    {
        parent::__construct();
        $this->initConstruct();
    }

    /**
     * Initialises view object
     * @return bool
     */
    final protected function initView()
    {
        return parent::initView();
    }

    /**
     * Must return true, if controller is accessible
     * @return bool
     * @since FPCm 4.4
     */
    public function isAccessible(): bool
    {
        return true;
    }

}