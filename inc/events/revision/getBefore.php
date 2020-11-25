<?php

/**
 * FanPress CM 4.x
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
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class getBefore extends \fpcm\events\abstracts\eventReturnArray {

}
