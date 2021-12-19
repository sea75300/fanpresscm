<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\interfaces;

/**
 * Interface for fiel type validation function
 * 
 * @package fpcm\controller\interfaces
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5
 */
interface validateFileType {

    /**
     * Must return true, if exext and mime type in $type matches
     * @return bool
     */
    public static function isValidType(string $ext, string $type, array $map = []) : bool;

}

?>