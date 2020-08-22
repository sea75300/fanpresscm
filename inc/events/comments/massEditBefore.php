<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\comments;

/**
 * Module-Event: massEditBefore
 * 
 * Event wird ausgeführt, bevor Massenbearbeitung von Artikeln ausgeführt wird
 * Parameter: array Felder und Artikel-IDs
 * Rückgabe: array Felder und Artikel-IDs
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 3.6
 */
final class massEditBefore extends \fpcm\events\abstracts\eventReturnArray {

    /**
     * Executes a certain event
     * @param array $data
     * @return array
     */
    public function run()
    {
        $result = parent::run();
        if (!count($result) || !isset($result['fields']) || !isset($result['commentIds'])) {
            return $this->data;
        }

        return $result;
    }

}
