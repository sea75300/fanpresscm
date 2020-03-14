<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\interfaces;

/**
 * Process button clicks as functions, function name has to be "onBUTTONNAME"
 * 
 * @package fpcm\controller\interfaces
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 4.4
 */
interface requestFunctions {

    /**
     * Must return true, if controller is accessible
     * @return bool
     */
    public function processButtons() : bool;
}

?>