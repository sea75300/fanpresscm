<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\editor;

/**
 * Module-Event: addLinks
 * 
 * Event wird ausgeführt, wenn im Artikel-Editor die Link-Liste für den "Link einfügen"-Dialog geladen wird
 * Parameter: void
 * Rückgabe: array mit Link-Informationen gemäß dem übergebenen Dummy-Eintrag
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class addLinks extends \fpcm\events\abstracts\event {

    /**
     * Executes a certain event
     * @param void $data
     * @return array
     */
    public function run() : \fpcm\module\eventResult
    {
        $this->data = [ ['label' => 'Example', 'value' => 'Example'] ];
        return parent::run();
    }

}
