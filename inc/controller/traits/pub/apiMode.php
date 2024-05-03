<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\pub;

/**
 * Pub controller apiMode trait
 * 
 * @package fpcm\controller\traits\system\syscheck
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2023, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait apiMode {


    /**
     * Comment count
     * @var int
     */
    protected $commentCount = 0;

    /**
     * API mode
     * @var bool
     */
    protected $apiMode = false;

}
