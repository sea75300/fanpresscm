<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\cli;

/**
 * FanPress CM CLI io wrapper
 * 
 * @package fpcm\model\cli
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.3.1
 */
final class io {

    /**
     * CLI output
     * @param mixed $str
     * @param bool $exit
     */
    public static function output($str, $exit = false)
    {
        if (is_array($str)) {
            $str = implode(PHP_EOL, $str);
        }
        
        $str = $str . PHP_EOL;

        if ($exit) {
            exit($str);
        }

        print $str;
    }

    /**
     * CLI input reader
     * @param string $str
     * @return string
     */
    public static function input($str)
    {
        return readline($str . ' ');
    }
    
}
