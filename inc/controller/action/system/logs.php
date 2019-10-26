<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\system;

/**
 * Log view controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class logs extends \fpcm\controller\abstracts\controller {

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'logs'];
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'logs/overview';
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
        $baseUrl = \fpcm\classes\tools::getFullControllerLink('ajax/logs/reload', [
            'log' => ''
        ]);

        
        $logs = [
            (new \fpcm\view\helper\tabItem('logs-sessions'))->setText('HL_LOGS_SESSIONS')->setUrl('#loader')->setData(['href' => $baseUrl.'0'])->setDataViewId('logs'),
            (new \fpcm\view\helper\tabItem('logs-system'))->setText('HL_LOGS_SYSTEM')->setUrl($baseUrl.'1')->setDataViewId('logs'),
            (new \fpcm\view\helper\tabItem('logs-error'))->setText('HL_LOGS_ERROR')->setUrl($baseUrl.'2')->setDataViewId('logs'),
            (new \fpcm\view\helper\tabItem('logs-database'))->setText('HL_LOGS_DATABASE')->setUrl($baseUrl.'3')->setDataViewId('logs'),
            (new \fpcm\view\helper\tabItem('logs-crons'))->setText('HL_LOGS_CRONJOBS')->setUrl($baseUrl.'5')->setDataViewId('logs'),
            (new \fpcm\view\helper\tabItem('logs-package'))->setText('HL_LOGS_PACKAGES')->setUrl($baseUrl.'4')
        ];
        
        if (defined('FPCM_DEBUG_EVENTS') && FPCM_DEBUG_EVENTS) {
            $logs[] = (new \fpcm\view\helper\tabItem('logs-events'))->setText('HL_LOGS_EVENTS')->setUrl($baseUrl.'6')->setDataViewId('logs');
        }
        
        $this->view->assign('logs', $this->events->trigger('logs\addToList', $logs));
        $this->view->assign('fullheight', true);

        $this->view->addDataView(new \fpcm\components\dataView\dataView('logs', false));

        $this->view->addJsFiles(['logs.js']);
        $this->view->addJsLangVars(['LOGS_CLEARED_LOG_OK', 'LOGS_CLEARED_LOG_FAILED', 'FILE_LIST_FILESIZE']);
        $this->view->addButton((new \fpcm\view\helper\button('cleanLogs'))
                ->setText('LOGS_CLEARLOG')
                ->setIcon('trash')
                ->setData(['logid' => 0]));

        $this->view->render();
    }

}

?>
