<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: controllerRedirect
 * 
 * Event wird ausgeführt, wenn ein Controller-Redirect via PHP durchgeführt wird
 * Parameter: string Redirect-String inkl. Controller und Parametern
 * Rückgabe: string Redirect-String inkl. Controller und Parametern
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class controllerRedirect extends \fpcm\events\abstracts\event {

}
