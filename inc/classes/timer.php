<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\classes;

    /**
     * Static timer class, internal only!
     * 
     * @package fpcm\classes\timer
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.1.0
     */ 
    final class timer {
        
        /**
         * Start micro time
         * @var int
         */
        private static $start = [];
        
        /**
         * Stop micro time
         * @var int
         */
        private static $stop = [];
        
        /**
         * Start timer
         * @param string $which
         */
        public static function start($which = 'sys') {
            self::$start[$which] = microtime(true);
        }

        /**
         * Stop timer
         * @param string $which
         */
        public static function stop($which = 'sys') {
            self::$start[$which] = microtime(true);
        }
        
        /**
         * Calc diff, calls @see timer::stop if not called before
         * @param string $which
         * @return int
         */
        public static function cal($which = 'sys') {
            
            if (!isset(self::$stop[$which]) || !self::$stop[$which]) {
                self::$stop[$which] = microtime(true);
            }
            
            if (self::$stop[$which] < self::$start[$which]) {
                return number_format(self::$start[$which] - self::$stop[$which], 4);
            }
            
            return number_format(self::$stop[$which] - self::$start[$which], 4);
        }

    }

?>