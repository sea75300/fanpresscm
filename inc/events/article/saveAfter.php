<?php

/**
 * Module-Event: articleSaveAfter
 * 
 * Event wird ausgeführt, nachdem ein Artikel Artikel gespeichert wurde
 * Parameter: int Artikel-ID
 * Rückgabe: void
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\article;

/**
 * Module-Event: article/saveAfter
 * 
 * Event wird ausgeführt, nachdem ein Artikel Artikel gespeichert wurde
 * Parameter: int Artikel-ID
 * Rückgabe: void
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 */
final class saveAfter extends \fpcm\events\abstracts\event {

    protected function getReturnType()
    {
        return '\fpcm\model\articles\article';
    }

}
