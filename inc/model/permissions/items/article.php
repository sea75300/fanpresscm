<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions\items;

/**
 * Article permissions object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.4
 */
class article extends editMass {

    /**
     * Add articles
     * @var bool
     */
    public $add;

    /**
     * To archive processing
     * @var bool
     */
    public $archive;

    /**
     * Approval of articles
     * @var bool
     */
    public $approve;

    /**
     * Revision management
     * @var bool
     */
    public $revisions;

    /**
     * Edit author
     * @var bool
     */
    public $authors;

}
