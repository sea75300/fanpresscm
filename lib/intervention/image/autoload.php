<?php

/**
 * Intervention/image Library Autoloader
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
spl_autoload_register(function($class) {

    $lprefix = 'Intervention\Image';
    
    if (!str_starts_with($class, $lprefix)) {
        return false;
    }
    
    $cn = str_replace([$lprefix, '\\'], ['', DIRECTORY_SEPARATOR], $class);
    $path = __DIR__ . $cn . '.php';

    if (file_exists($path)) {
        include $path;
        return true;
    }

    throw new \Exception('Intervention/image Autoloader: Class ' . $class . ' not found in ' . $path);
    return true;
});
