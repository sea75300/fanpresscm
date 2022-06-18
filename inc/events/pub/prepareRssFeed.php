<?php

/**
 * FanPress CM 5
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\pub;

/**
 * Module-Event: prepareRssFeed
 * 
 * Event wird ausgeführt, wenn RSS-Feed-Struktur aufgebaut wird
 * Parameter: DOMDocument Objekt
 * Rückgabe: DOMDocument Objekt
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 3.4
 */
final class prepareRssFeed extends \fpcm\events\abstracts\event {

    /**
     * Defines type of returned data
     * @return string|bool
     */
    protected function getReturnType()
    {
        return '\DOMDocument';
    }

}
