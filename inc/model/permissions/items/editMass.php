<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions\items;

/**
 * Edit and delete permissions object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
 */
abstract class editMass extends base {
    
    /**
     * Edit own comments
     * @var bool
     */
    public $edit;

    /**
     * Edit all comments
     * @var bool
     */
    public $editall;

    /**
     * Delete processing
     * @var bool
     */
    public $delete;

    /**
     * Mass edit processing
     * @var bool
     */
    public $massedit;

}
