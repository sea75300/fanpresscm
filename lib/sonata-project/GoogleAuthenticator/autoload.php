<?php

/**
 * GoogleAuthenticator Library Autoloader
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2017/18, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
spl_autoload_register(function($class) {

    if (strpos($class, 'Sonata\\GoogleAuthenticator') === false) {
        return false;
    }

    $path = __DIR__ . DIRECTORY_SEPARATOR . str_replace(['Sonata\\GoogleAuthenticator\\', '\\'], ['', DIRECTORY_SEPARATOR], $class) . '.php';
    if (file_exists($path)) {
        include $path;
        return true;
    }

    throw new \Exception('GoogleAuthenticator Autoloader: class ' . $class . ' not found in ' . $path);
    return false;
});