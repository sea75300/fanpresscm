<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper\traits;

/**
 * View helper with dedicated ui size 
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1-dev
 */
trait uiSizeHelper {

    /**
     * UI element size
     * @var string
     */
    protected $uiSize = '';

    /**
     * Return ui element size
     * @return string
     */
    public function getUiSize(): string
    {
        return $this->uiSize;
    }

    /**
     * Sets ui element size
     * @param string $uiSize
     * @return $this
     */
    public function setUiSize(string $uiSize)
    {
        $this->uiSize = $uiSize;
        return $this;
    }



}

?>