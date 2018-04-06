<?php

/**
 * FanPress CM 4
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\pub;

/**
 * Module-Event: templateHtmlTags
 * 
 * Event wird ausgeführt, bevor fpcm\model\pubtemplates-Objekte initialisiert werden um weitere HTML-Tags zur Validierung
 * zur Verfügung zu stellen
 * Parameter: array mit HTML-Tags
 * Rückgabe: array mit HTML-Tags
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class templateHtmlTags extends \fpcm\events\abstracts\eventReturnArray {

}
