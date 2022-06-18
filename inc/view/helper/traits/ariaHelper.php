<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper\traits;

/**
 * Escape elemtn value helper
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait ariaHelper {

    /**
     * Ement aria config
     * @var array
     * @since 5.0-dev
     */
    protected $aria = [];

    /**
     * Return class string
     * @return string
     */
    protected function getAriaString()
    {
         return $this->assocArrayToString('aria', $this->aria);
    }

    /**
     * Add array for 'data-'-params to element
     * @param array $data
     * @return $this
     */
    public function setAria(array $aria)
    {
        $this->aria = $aria;
        return $this;
    }

}

?>