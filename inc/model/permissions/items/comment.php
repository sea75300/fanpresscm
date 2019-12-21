<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions\items;

/**
 * Comment permissions object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.4
 */
class comment extends editMass {

    /**
     * Approval processing
     * @var bool
     */
    public $approve;

    /**
     * Private edit processing
     * @var bool
     */
    public $private;

    /**
     * Move comments to articles
     * @var bool
     */
    public $move;

}
