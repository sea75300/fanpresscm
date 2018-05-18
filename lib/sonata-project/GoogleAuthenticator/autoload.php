<?php

foreach (glob(__DIR__.DIRECTORY_SEPARATOR.'*.php') as $file) {

    if (!trim($file) || $file === __DIR__) {
        continue;
    }

    include_once $file;
}