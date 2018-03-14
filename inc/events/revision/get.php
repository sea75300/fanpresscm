<?php

/**
 * Module-Event: getRevision
 * 
 * Event wird ausgeführt, wenn Artikel-Revision abgerufen wird
 * Parameter: array mit Daten der Revision
 * Rückgabe: array mit Daten der Revision
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\revision;

/**
 * Module-Event: revision/get
 * 
 * Event wird ausgeführt, wenn Artikel-Revision abgerufen wird
 * Parameter: array mit Daten der Revision
 * Rückgabe: array mit Daten der Revision
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class get extends \fpcm\events\abstracts\event {

    protected function getReturnType()
    {
        return '\fpcm\model\articles\revision';
    }

}
