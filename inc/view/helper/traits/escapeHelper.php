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
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait escapeHelper {

    /**
     * Escapes given values
     * @param string $value
     * @param int $mode
     * @return void
     */
    public function escapeVal($value, $mode = null)
    {
        if ($value === null) {
            return $value;
        }

        return htmlentities($value, ($mode !== null ? (int) $mode : ENT_COMPAT | ENT_HTML5));
    }

}

?>