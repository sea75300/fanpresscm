<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\article;

/**
 * Module-Event: article/getShortLink
 * 
 * Event wird ausgeführt, wenn Kurzlink für einen Artikel erzeugt wird
 * Parameter: array Array mit Artikel-URL (artikellink) und Standardkurzlink (url)
 * Rückgabe: array Array mit Artikel-URL (artikellink) und Standardkurzlink (url), url wird vollständig zurückgegeben
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class getShortLink extends \fpcm\events\abstracts\eventReturnArray {

}
