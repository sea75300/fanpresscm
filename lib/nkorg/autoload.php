<?php

/**
 * nkorg Library Autoloader
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2017, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
spl_autoload_register(function($class) {

    if (strpos($class, 'nkorg') === false || strpos($class, 'modules') !== false) {
        return false;
    }

    $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (file_exists($path)) {
        include $path;
        return true;
    }

    throw new \Exception('nkorg Autoloader: class ' . $class . ' not found in ' . $path);
    return true;
});
