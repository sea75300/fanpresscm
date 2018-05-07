<?php

/**
 * AJAX logs clear controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\logs;

/**
 * AJAX-Controller zum leeren der Systemlogs
 * 
 * @package fpcm\controller\ajax\logs\clear
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class clear extends \fpcm\controller\abstracts\ajaxController {

    /**
     * System-Log-Typ
     * @var int
     */
    protected $log;

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        if (!$this->session->exists()) {
            return false;
        }

        if (!$this->permissions->check(array('system' => 'logs'))) {
            return false;
        }

        if ($this->getRequestVar('log') === null) {
            return false;
        }

        $this->log = $this->getRequestVar('log');

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (is_numeric($this->log)) {

            if ($this->log < 1) {
                $res = \fpcm\classes\loader::getObject('\fpcm\model\system\session')->clearSessions();
            } else {
                $logfile = new \fpcm\model\files\logfile($this->log);
                $res = $logfile->clear();
            }
        } else {
            $res = $this->events->trigger('logs\clearSystemLog', $this->log);
            fpcmLogEvents(__METHOD__.' '.$res);
        }

        $this->events->trigger('logs\clearSystemLogs');

        $this->returnData = [
            'txt' => $res ? 'LOGS_CLEARED_LOG_OK' : 'LOGS_CLEARED_LOG_FAILED',
            'type' => $res ? 'notice' : 'error',
        ];

        $this->getSimpleResponse();
    }

}

?>
