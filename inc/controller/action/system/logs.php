<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Log view controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class logs extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    /**
     * 
     * @var array
     */
    private $logs = [];

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->logs;
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'components/tabs';
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'HL_LOGS';
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $this->initLogs();
        

        $this->view->addTabs('tabs-logs', $this->events->trigger('logs\addToList', $this->logs) );
        $this->view->addJsFiles(['logs.js']);
        $this->view->addJsLangVars(['LOGS_CLEARED_LOG_OK', 'LOGS_CLEARED_LOG_FAILED', 'FILE_LIST_FILESIZE']);
        $this->view->addJsVars([
            'currentLog' => [
                'name' => \fpcm\model\files\logfile::FPCM_LOGFILETYPE_SESSION,
                'system' => 1,
                'key' => ''
            ]
        ]);

        $this->view->addButton((new \fpcm\view\helper\button('cleanLogs'))->setText('LOGS_CLEARLOG')->setIcon('trash'));

        $this->view->render();
    }

    /**
     * 
     * @return bool
     */
    private function initLogs() : bool
    {
        $baseUrl = \fpcm\classes\tools::getFullControllerLink('ajax/logs/reload', [
            'system' => 1,
            'log' => '',
        ]);
        
        $map = \fpcm\model\files\logfile::getLogMap();
        if (!defined('FPCM_DEBUG_EVENTS') || !FPCM_DEBUG_EVENTS) {
            unset($map[\fpcm\model\files\logfile::FPCM_LOGFILETYPE_EVENTS]);
        }

        $this->logs = array_map(function ($key) use ($baseUrl) {

            $tab = (new \fpcm\view\helper\tabItem('logs-' . $key))->setText('HL_LOGS_' . strtoupper($key))->setUrl($baseUrl.$key);
            if ($key === \fpcm\model\files\logfile::FPCM_LOGFILETYPE_PKGMGR) {
                return $tab;
            }
            
            $tab->setDataViewId('logs-'.$key);
            
            $this->view->addDataView(new \fpcm\components\dataView\dataView('logs-'.$key, false));
            return $tab;

        }, array_keys($map));
        
        array_unshift($this->logs, (new \fpcm\view\helper\tabItem('logs-sessions'))->setText('HL_LOGS_SESSIONS')->setUrl($baseUrl . \fpcm\model\files\logfile::FPCM_LOGFILETYPE_SESSION)->setDataViewId('logs-sessions'));
        $this->view->addDataView(new \fpcm\components\dataView\dataView('logs-sessions', false));
        return true;
    }

}

?>
