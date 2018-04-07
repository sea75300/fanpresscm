<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\logs;

/**
 * Module-Event: addToList
 * 
 * Event wird ausgeführt, wenn Systemlogs angezeigt werden
 * Parameter: void
 * Rückgabe: array Liste mit Logs
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class addToList extends \fpcm\events\abstracts\eventReturnArray {

    /**
     * 
     * @return array
     */
    public function run()
    {
        $result = parent::run();
        if (!count($result)) {
            return $this->data;
        }

        foreach ($result as $index => $row) {

            if (empty($row['id'])) {
                trigger_error('Invalid params, missing index "id" for log ' . $index);
                trigger_error(implode(PHP_EOL, $row));
                return $this->data;
            }

            if (empty($row['title'])) {
                trigger_error('Invalid params, missing index "title" for log ' . $index);
                trigger_error(implode(PHP_EOL, $row));
                return $this->data;
            }
        }

        return $result;
    }

}
