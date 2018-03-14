<?php

/**
 * Module-Event: createRevision
 * 
 * Event wird ausgeführt, wenn eine neue Artikel-Revision erzeugt wird
 * Parameter: array Revisionsinformationen
 * Rückgabe: array Revisionsinformationen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\revision;

/**
 * Module-Event: revision/create
 * 
 * Event wird ausgeführt, wenn eine neue Artikel-Revision erzeugt wird
 * Parameter: array Revisionsinformationen
 * Rückgabe: array Revisionsinformationen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class create extends \fpcm\events\abstracts\event {

    protected function getReturnType()
    {
        return self::FPCM_MODULE_EVENT_RETURNTYPE_ARRAY;
    }
}
