<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Auto-detected database table name
 * 
 * @package fpcm\model\traits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.1.0-a1
 */
trait getObjectInstance {

    /**
     * Common function to return class instance object
     * @return object
     * @since 5.1.0-a1
     */
    public static function getObjectInstance()
    {
        $iClass = static::class;
        
        if (!isset($GLOBALS['fpcm']['objects'][$iClass])) {
            $GLOBALS['fpcm']['objects'][$iClass] = new $iClass();
        }
        
        return $GLOBALS['fpcm']['objects'][$iClass];
        
    }

}
