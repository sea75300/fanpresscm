<?php

/**
 * Module-Event: articlesByCondition
 * 
 * Event wird ausgeführt, wenn Artikel-Suche ausgeführt wird
 * Parameter: array Suchbedingungen
 * Rückgabe: array Suchbedingungen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.4
 */

namespace fpcm\events\article;

/**
 * Module-Event: article/getByCondition
 * 
 * Event wird ausgeführt, wenn Artikel-Suche ausgeführt wird
 * Parameter: array Suchbedingungen
 * Rückgabe: array Suchbedingungen
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm/model/events
 * @since FPCM 3.4
 */
final class getByCondition extends \fpcm\events\abstracts\event {
    
    protected function getReturnType()
    {
        return self::FPCM_MODULE_EVENT_RETURNTYPE_ARRAY;
    }

}
