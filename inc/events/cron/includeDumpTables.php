<?php

/**
 * Module-Event: includeDumpTables
 * 
 * Event wird ausgeführt, wenn Cronjob für automatische Datenbank-Sicherung läuft
 * Parameter: array mit Liste der zu sichernden Tabellen
 * Rückgabe: array mit Liste der zu sichernden Tabellen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.1
 */

namespace fpcm\events\cron;

/**
 * Module-Event: includeDumpTables
 * 
 * Event wird ausgeführt, wenn Cronjob für automatische Datenbank-Sicherung läuft
 * Parameter: array mit Liste der zu sichernden Tabellen
 * Rückgabe: array mit Liste der zu sichernden Tabellen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 * @since FPCM 3.1
 */
final class includeDumpTables extends \fpcm\events\abstracts\eventReturnArray {

}
