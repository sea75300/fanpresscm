<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events;

/**
 * Module-Event: dashboardContainersLoad
 * 
 * Event wird ausgeführt, wenn Liste von Dashboard-Container-Klassen geladen wird
 * Parameter: array mit Liste von Container-Klassen
 * Rückgabe: array mit Liste von Container-Klassen, zurückgegebene Klassen müssen müssen das Interface "\fpcm\model\abstracts\dashcontainer" implementieren!
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class dashboardContainersLoad extends \fpcm\events\abstracts\eventReturnArray {

}
