<?php
/**
 * nkorg Libary Autoloader
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

spl_autoload_register(function($class){
    
    if (strpos($class, 'nkorg') === false) {
        return false;
    }
    
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $path  = dirname(__DIR__).DIRECTORY_SEPARATOR.$class.'.php';

    if (file_exists($path)) {
        include $path;
        return true;   
    }
    
    throw new \Exception('class '.$class.' not found in '.$path);
    return false;
    
});