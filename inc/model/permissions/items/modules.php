<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions\items;

/**
 * Comment permissions object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
 */
class modules extends base {

    /**
     * Install module processing
     * @var bool
     */
    public $install;

    /**
     * Uninstall module processing
     * @var bool
     */
    public $uninstall;

    /**
     * Configure module processing
     * @var bool
     */
    public $configure;

}
