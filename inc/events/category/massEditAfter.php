<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\category;

/**
 * Module-Event: category/massEditAfter
 * 
 * Event wird ausgeführt, nachdem Massenbearbeitung von Kategorien ausgeführt wurde
 * Parameter: array Felder und Kategorie-IDs
 * Rückgabe: array Felder und Kategorie-IDs
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 4.3
 */
final class massEditAfter extends \fpcm\events\abstracts\eventReturnArray {

    /**
     * Executes a certain event
     * @param array $data
     * @return array
     */
    public function run()
    {
        $result = parent::run();
        $tmp = $result->getData();
        if (!count($tmp) || !isset($tmp['fields']) || !isset($tmp['articleIds'])) {
            return (new \fpcm\module\eventResult)->setContinue(true)->setData($this->data);
        }

        return $result;
    }

}
