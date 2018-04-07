<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\logs;

/**
 * Module-Event: clearSystemLog
 * 
 * Event wird ausgeführt, wenn über eines der Systemlogs über den Button "Leeren" aufgeräumt wird
 * Parameter: string Log-ID
 * Rückgabe: bool true wenn Log geleert
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 * @since FPCM 3.3
 */
final class clearSystemLog extends \fpcm\events\abstracts\event {

}
