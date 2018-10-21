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

    protected function getModuleKey()
    {
        $class = get_class($this);
        $stack = \fpcm\classes\loader::stackPull('modulekeys');
        if (isset($stack[$class])) {
            return $stack[$class];
        }

        $stack[$class] = explode('\\controller', \fpcm\module\module::getKeyFromClass(get_class($this)), 2)[0];
        \fpcm\classes\loader::stackPush('modulekeys', $stack);
        return $stack[$class];
    }

    protected function addLangVarPrefix($var)
    {
        return \fpcm\module\module::getLanguageVarPrefixed($this->getModuleKey()).strtoupper($var);
    }
    
}
