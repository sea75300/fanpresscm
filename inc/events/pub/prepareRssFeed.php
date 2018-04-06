<?php

/**
 * FanPress CM 4
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
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 * @since FPCM 3.4
 */
final class prepareRssFeed extends \fpcm\events\abstracts\event {

    protected function getReturnType()
    {
        return '\DOMDocument';
    }

}
