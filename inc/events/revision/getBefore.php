<?php

/**
 * Module-Event: getRevisionsBefore
 * 
 * Event wird ausgeführt, bevor die Revisionsliste abgerufen wird
 * Parameter: array Liste von Revisionsdateien für des Artikels
 * Rückgabe: array Liste der Revisionsdateien
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\revision;

/**
 * Module-Event: revision/getBefore
 * 
 * Event wird ausgeführt, bevor die Revisionsliste abgerufen wird
 * Parameter: array Liste von Revisionsdateien für des Artikels
 * Rückgabe: array Liste der Revisionsdateien
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class getBefore extends \fpcm\events\abstracts\event {

    protected function getReturnType()
    {
        return self::FPCM_MODULE_EVENT_RETURNTYPE_ARRAY;
    }

}
