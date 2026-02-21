<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\classes;

/**
 * FPCM date time helper
 *
 * @package fpcm\classes\tools
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-b2
 */
final class dateTimeHelper {

    /**
     * Validates date string
     * @param string $str
     * @return bool
     */
    public static function validateDateString(string $str, $widthTime = false) : bool
    {
        $regex  = $widthTime
                ? '/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})\ ([0-9]{2})\:([0-9]{2})$/'
                : '/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/';

        if (preg_match($regex, $str, $matches) !== 1) {
            return false;
        }

        if ((int) $matches[2] < 1 || (int) $matches[2] > 12) {
            return false;
        }

        if ((int) $matches[3] < 1 || (int) $matches[3] > 31) {
            return false;
        }

        if (!$widthTime) {
            return true;
        }

        if ((int) $matches[4] < 0 || (int) $matches[4] > 23) {
            return false;
        }

        if ((int) $matches[5] < 0 || (int) $matches[5] > 59) {
            return false;
        }

        return true;
    }


    /**
     * Returns timestamp from string data
     * @param string $date
     * @param string $time
     * @return int
     */
    public static function getTimestampFromString(string $date, string $time = '') : int
    {
        $dtObj = (new \DateTime($date));

        if (!$time) {
            return $dtObj->getTimestamp();
        }

        $timeArr = explode(':', $time, 3);
        $dtObj->setTime($timeArr[0], $timeArr[1], $timeArr[2] ?? '00');
        return $dtObj->getTimestamp();
    }

    /**
     * Returns timestamp for date + 23:59:59
     * @param string $date
     * @return int
     */
    public static function getLastDayTimestamp(string $date) : int
    {
        $puDt = new \DateTime($date);
        $puDt->setTime(23, 59, 59);
        return $puDt->getTimestamp();
    }
}
