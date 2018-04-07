<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\image;

/**
 * Module-Event: thumbnailCreate
 * 
 * Event wird ausgeführt, wenn neuer Thumbnial für ein Bild erzeugt wird
 * Parameter: Objekt vom Type fpcm\model\files\image
 * Rückgabe: \fpcm\model\files\image
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/events
 */
final class thumbnailCreate extends \fpcm\events\abstracts\event {

    protected function getReturnType()
    {
        return '\fpcm\model\files\image';
    }

}
