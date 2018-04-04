<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\article;

/**
 * Module-Event: article/save
 * 
 * Event wird ausgeführt, wenn Artikel gespeichert wird
 * Parameter: array Artikel-Daten-Array
 * Rückgabe: array Artikel-Daten-Array
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class save extends \fpcm\events\abstracts\eventReturnArray {

    protected function getReturnType()
    {
        return \fpcm\events\abstracts\event::FPCM_MODULE_EVENT_RETURNTYPE_ARRAY;
    }

}
