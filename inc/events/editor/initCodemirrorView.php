<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\editor;

/**
 * Module-Event: initCodemirrorView
 * 
 * Event wird ausgeführt, wenn wenn HTML-Editor initialisiert wird
 * Parameter: array mit Informationen zur Editor-Initialisierung (additionalData)
 * sowie Dummy um weitere Buttons einzufügen (extraButtons)
 * Rückgabe: array mit Daten für Editor-Initialisierung
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class initCodemirrorView extends \fpcm\events\abstracts\eventReturnArray {

}
