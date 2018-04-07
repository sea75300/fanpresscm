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
    protected function getViewPath()
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
        $this->view->assign('customLogs', $this->events->trigger('logs\addToList', []));
        $this->view->assign('reloadBaseLink', \fpcm\classes\tools::getFullControllerLink('ajax/logs/reload', [
            'log' => ''
        ]));

        $this->view->addDataView(new \fpcm\components\dataView\dataView('logs', false));

        $this->view->addJsFiles(['logs.js']);
        $this->view->addJsLangVars(['LOGS_CLEARED_LOG_OK', 'LOGS_CLEARED_LOG_FAILED']);
        $this->view->addButton((new \fpcm\view\helper\button('fpcm-logs-clear_0'))->setText('LOGS_CLEARLOG')->setClass('fpcm-logs-clear fpcm-clear-btn')->setIcon('trash'));

        $this->view->render();
    }

}

?>
