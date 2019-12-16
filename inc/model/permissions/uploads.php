<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions;

/**
 * File manager permissions object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.4
 */
class uploads extends base {

    /**
     * File managew is visible
     * @var bool
     */
    public $visible;

    /**
     * Upload file processing
     * @var bool
     */
    public $add;

    /**
     * Delete file processing
     * @var bool
     */
    public $delete;

    /**
     * Thumbnail processing
     * @var bool
     */
    public $thumbs;

    /**
     * Rename processing
     * @var bool
     */
    public $rename;

}
