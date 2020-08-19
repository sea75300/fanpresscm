<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * Loader
 * 
 * @package fpcm\classes\loader
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class loader {

    /**
     * Globaler Generator für Objekte
     * @param string $class Class name including namespace
     * @param mixed $params Params for construct
     * @param bool $cache Load object from globals cache
     * @return object
     */
    public static function getObject($class, $params = null, $cache = true)
    {
        if (!class_exists($class)) {
            trigger_error('Undefined class ' . $class);
            return false;
        }

        $class = ltrim($class, '\\');
        if (isset($GLOBALS['fpcm']['objects'][$class]) && is_object($GLOBALS['fpcm']['objects'][$class]) && $cache) {
            return $GLOBALS['fpcm']['objects'][$class];
        }

        $GLOBALS['fpcm']['objects'][$class] = $params !== null ? new $class($params) : new $class();
        return $GLOBALS['fpcm']['objects'][$class];
    }

    /**
     * Returns library file path
     * @param string $libPath
     * @param boolean $exists
     * @return string
     */
    public static function libGetFilePath($libPath, $exists = true)
    {
        $path = dirs::getFullDirPath('lib', $libPath);
        if ($exists && !file_exists($path)) {
            trigger_error('Lib path ' . $path . ' does not exists!');
        }

        if (is_dir($path) && file_exists($path . DIRECTORY_SEPARATOR . 'autoload.php')) {
            return $path . DIRECTORY_SEPARATOR . 'autoload.php';
        }

        return $path;
    }

    /**
     * Returns library file URL
     * @param string $libPath
     * @return string
     */
    public static function libGetFileUrl($libPath)
    {
        return dirs::getLibUrl($libPath);
    }

    /**
     * Push data to globals stack cache
     * @param string $name
     * @param mixed $value
     * @param bool $force
     * @return bool
     */
    public static function stackPush($name, $value, $force = false)
    {
        if (isset($GLOBALS['fpcm']['stack'][$name]) && !$force) {
            return false;
        }

        $GLOBALS['fpcm']['stack'][$name] = $value;
        return true;
    }

    /**
     * Pull data to globals stack cache
     * @param string $name
     * @return mixed
     */
    public static function stackPull($name)
    {
        return isset($GLOBALS['fpcm']['stack'][$name]) ? $GLOBALS['fpcm']['stack'][$name] : null;
    }

}
