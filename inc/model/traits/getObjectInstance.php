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
        trigger_error(__TRAIT__ . '::' . __FUNCTION__ . ' is deprecated as of FPCM 5.3.0-dev', E_USER_DEPRECATED);
        return static::getInstance();
        
    }

    /**
     * Common function to return class instance object
     * @return object
     * @since 5.1.0-a1
     * @see getObjectInstance
     */
    public static function getInstance()
    {
        $iClass = static::class;
        
        if (!isset($GLOBALS['fpcm']['objects'][$iClass])) {
            $GLOBALS['fpcm']['objects'][$iClass] = new $iClass();
        }
        
        return $GLOBALS['fpcm']['objects'][$iClass];
    }

}
