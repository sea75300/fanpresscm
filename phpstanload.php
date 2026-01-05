<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'spyc' . DIRECTORY_SEPARATOR . 'Spyc.php';

spl_autoload_register(function($class)
{
    if (strpos($class, 'fpcm') === false || strpos($class, 'fpcm\\modules\\') !== false) {
        return false;
    }

    $includePath = __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . str_replace(['fpcm\\', '\\'], ['', DIRECTORY_SEPARATOR], $class) . '.php';
    if (realpath($includePath) === false || !file_exists($includePath)) {
        return true;
    }

    require $includePath;
    return true;
});

spl_autoload_register(function($class)
{
    if (strpos($class, 'fpcm\\modules\\') === false) {
        return false;
    }

    $includePath = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR. 'modules' . DIRECTORY_SEPARATOR . str_replace(['fpcm\\modules\\', '\\'], ['', DIRECTORY_SEPARATOR], $class) . '.php';

    if (realpath($includePath) === false || !file_exists($includePath)) {
        return true;
    }

    require $includePath;
    return true;
});

spl_autoload_register(function($class)
{
    if (str_starts_with($class, 'fpcm\\')) {
        return false;
    }

    $includePath = __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . str_replace(['\\'], [DIRECTORY_SEPARATOR], $class) . '.php';
    
    if (realpath($includePath) === false || !file_exists($includePath)) {
        return true;
    }
    
    file_put_contents(__DIR__ . '/phpstan_autoload.log', sprintf("Include %s\r\n", $includePath), FILE_APPEND);

    require $includePath;
    return true;
});
