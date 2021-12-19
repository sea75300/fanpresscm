<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\events\logs;

/**
 * Module-Event: addToList
 * 
 * Event wird ausgeführt, wenn Systemlogs angezeigt werden
 * Parameter: void
 * Rückgabe: array Liste mit Logs
 * 
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2021, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\events
 */
final class addToList extends \fpcm\events\abstracts\eventReturnArray {

    /**
     * Return list of tabs for system log view, adds unique dataview list id
     * @return array
     * @since 4.5.1-b2
     */
    public function run() {
        
        $result = parent::run();
        
        $blacklist = [
            'logs-' . \fpcm\model\files\logfile::FPCM_LOGFILETYPE_PKGMGR
        ];
        
        array_walk($result, function (\fpcm\view\helper\tabItem &$tab) use ($blacklist) {
            
            if (in_array($tab->getId(), $blacklist)) {
                return false;
            }
            
            if (!trim($tab->getDataViewId()) || $tab->getDataViewId() === 'logs') {
                $tab->setDataViewId($tab->getDataViewId().'-'.\fpcm\classes\tools::getHash($tab->getCleanName()));
            }

            return true;
        });
        
        return $result;
    }

    
}
