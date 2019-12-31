<?php

/**
 * FanPress CM 4.x
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
 * @since FPCM 4.3.1
 */
final class io {

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

    public static function input($str, $exit = false)
    {
        return readline($str . ' ');
    }
    
}
