<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\template;

/**
 * Module-Event: parse
 * 
 * Event wird ausgeführt, wenn ein Template geparst wird das keine eigene Parse-Funktion besitzt
 * Parameter: array Liste mit Artikel-Platzhaltern und zugewiesenen Daten
 * Rückgabe: array Liste mit Platzhaltern und Daten
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class parse extends \fpcm\events\abstracts\eventReturnArray {

}
