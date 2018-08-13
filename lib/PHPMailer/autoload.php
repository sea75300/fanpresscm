<?php

/**
 * PHPMailer Libary Autoloader
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
spl_autoload_register(function($class) {

    if (strpos($class, 'PHPMailer') === false) {
        return false;
    }

    $path = __DIR__ . DIRECTORY_SEPARATOR . basename(str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.php';
    if (file_exists($path)) {
        include $path;
        return true;
    }

    throw new \Exception('PHPMailer Autoloader: Class ' . $class . ' not found in ' . $path);
    return true;
});
