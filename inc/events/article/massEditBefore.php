<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\article;

/**
 * Module-Event: article/massEditBefore
 * 
 * Event wird ausgeführt, bevor Massenbearbeitung von Artikeln ausgeführt wird
 * Parameter: array Felder und Artikel-IDs
 * Rückgabe: array Felder und Artikel-IDs
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 3.6
 */
final class massEditBefore extends \fpcm\events\abstracts\eventReturnArray {
    
    /**
     * Esxecutes a certain event
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
