<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\editor;

/**
 * Module-Event: getFileList
 * 
 * Event wird ausgeführt, wenn im Artikel-Editor die Liste vorhandener Upload geladen wird
 * Parameter: array mit Daten im Dateiindex, "label" enthält das Label für die Dateilist je nach aktivem Editor und "files"
 * die eigentliche Dateiliste
 * Rückgabe: array Liste mit Dateien in der obigen Form mit "label" und "files"
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class getFileList extends \fpcm\events\abstracts\eventReturnArray {

}
