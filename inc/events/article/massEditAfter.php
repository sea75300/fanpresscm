<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\article;

/**
 * Module-Event: article/massEditAfter
 * 
 * Event wird ausgeführt, nachdem Massenbearbeitung von Artikeln ausgeführt wurde
 * Parameter: array Felder und Artikel-IDs
 * Rückgabe: array Felder und Artikel-IDs
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 * @since FPCM 3.6
 */
final class massEditAfter extends \fpcm\events\abstracts\eventReturnArray {

    /**
     * wird ausgeführt, bevor Massenbearbeitung von Artikeln ausgeführt wird
     * @param array $data
     * @return array
     */
    public function run()
    {
        $result = parent::run();
        if (!count($result) || !isset($result['fields']) || !isset($result['articleIds'])) {
            return $this->data;
        }

        return $result;
    }

}
