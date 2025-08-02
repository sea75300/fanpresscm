<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\traits;

/**
 * Remidner check trait
 * 
 * @package fpcm\model\traits
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.3.0-dev
 */
trait hasReminder {

    /**
     * Returns true object has reminders
     * @return bool
     */
    public function hasReminder() : bool
    {
        $rem = \fpcm\model\reminders\reminders::getInstance()->getRemindersForDatasets(self::class, []);
        
        if (!isset($rem[$this->id])) {
            return false;
        }
        
        return count($rem[$this->id]) > 0;
    }
    
}
