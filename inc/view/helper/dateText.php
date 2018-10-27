<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\view\helper;

/**
 * Timestamp to date text view helper
 * 
 * @package fpcm\view\helper
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
final class dateText {

    /**
     * Date format string according to PHP date() function
     * @var string
     */
    protected $format = '';

    /**
     * Unix timestamp
     * @var int
     */
    protected $timespan = 0;

    /**
     * Flag if element string was return by @see __toString
     * @var boolean
     */
    protected $returned = false;

    /**
     * Konstruktor
     * @param string $timestamp
     * @param string $format
     */
    final public function __construct($timestamp, $format = false)
    {
        $this->format = is_string($format) && trim($format) ? trim($format) : \fpcm\classes\loader::getObject('\fpcm\model\system\config')->system_dtmask;

        $this->timespan = (int) $timestamp;
    }

    /**
     * 
     * @return string
     * @ignore
     */
    final public function __toString()
    {
        $this->returned = true;
        return date($this->format, $this->timespan);
    }

    /**
     * 
     * @return void
     * @ignore
     */
    final public function __destruct()
    {
        if ($this->returned) {
            return;
        }

        print date($this->format, $this->timespan);
    }

}

?>