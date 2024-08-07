<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\permissions\items;

/**
 * File manager permissions object
 * 
 * @package fpcm\model\permissions
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.4
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

    /**
     * Get default permission values
     * @see base::getDefault()
     * @return array
     */
    final public function getDefault() : array
    {
        return array_merge($this->getObjectVars(), [
            'visible' => 1,
            'add' => 1,
            'thumbs' => 1
        ]);
    }
}
