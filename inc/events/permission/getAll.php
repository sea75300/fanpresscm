<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\permission;

/**
 * Module-Event: getAll
 * 
 * Event wird ausgeführt, nachdem Berechtigungsinformationen aus Datenbank abgerufen wurden
 * Parameter: array Berechtigungsinformationen aus Datenbank
 * Rückgabe: array Berechtigungsinformationen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class getAll extends \fpcm\events\abstracts\eventReturnArray {

}
