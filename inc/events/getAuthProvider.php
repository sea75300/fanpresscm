<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: getAuthProvider
 * 
 * Event wird ausgeführt, wenn fpcmAPI::__call oder fpcmAPI::__callStatic aufgerufen wird
 * Parameter: array mit Funktionsname und Funktionsparametern
 * Rückgabe: Mixed, Rückgabewerte der Funktion
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @since 4
 */
final class getAuthProvider extends \fpcm\events\abstracts\event {

    /**
     * Defines type of returned data
     * @return string
     */
    protected function getReturnType()
    {
        return '\fpcm\model\abstracts\authProvider';
    }

}
