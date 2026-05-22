<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\image;

/**
 * Module-Event: thumbnailCreate
 * 
 * Event wird ausgeführt, wenn neuer Thumbnial für ein Bild erzeugt wird
 * Parameter: Objekt vom Type fpcm\model\files\mediaFile
 * Rückgabe: \fpcm\model\files\mediaFile
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 * @deprecated 5.3.0-a1
 */
final class thumbnailCreate extends \fpcm\events\abstracts\event {

    protected function beforeRun(): void 
    {
        trigger_error(sprintf('Event %s is desprecated as of FPCM 5.3, use the "mediafile" event instead!', self::class), E_USER_DEPRECATED);
    }

}
