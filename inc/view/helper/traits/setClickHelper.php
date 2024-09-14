<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper\traits;

/**
 * Set onClick helper
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.2
 */
trait setClickHelper {

    /**
     * Bind function to button click
     * @param string $func
     * @param type $args
     * @return $this
     * @since 5.0-dev
     */
    final public function setOnClick(string $func, $args = null)
    {
        if (!$func) {
            return $this;
        }
        
        $this->data['fn'] = $func;
        $this->data['fn-arg'] = $args;
        return $this;
    }

}
