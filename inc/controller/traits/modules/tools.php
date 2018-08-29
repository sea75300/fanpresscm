<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\traits\modules;

/**
 * Module tools trait
 * 
 * @package fpcm\controller\traits\modules\tools
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait tools {

    protected function addLangVarPrefix($var)
    {
        $key = explode('\\controller', \fpcm\module\module::getKeyFromClass(get_class($this)), 2)[0];
        return \fpcm\module\module::getLanguageVarPrefixed($key).strtoupper($var);
    }
    
}
