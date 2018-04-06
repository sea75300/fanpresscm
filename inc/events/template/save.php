<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\template;

/**
 * Module-Event: save
 * 
 * Event wird ausgeführt, wenn Template gespeichert wird
 * Parameter: array mit Dateiname in "file" und HTML-Code des Templates "content"
 * Rückgabe: array mit Dateiname in "file" und HTML-Code des Templates "content", "content" wird genutzt zum Speichern
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class save extends \fpcm\events\abstracts\eventReturnArray {

}
