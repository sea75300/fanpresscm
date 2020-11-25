<?php

/**
 * AJAX logs clear controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\logs;

/**
 * AJAX-Controller zum leeren der Systemlogs
 * 
 * @package fpcm\controller\ajax\logs\clear
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class clear extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    /**
     * System-Log-Typ
     * @var int
     */
    protected $log;

    /**
     * Module key
     * @var int
     */
    protected $moduleKey;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->logs;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->log = $this->request->fromPOST('log');
        $this->moduleKey = $this->request->fromPOST('key', [
            \fpcm\model\http\request::FILTER_URLDECODE
        ]);

        if ($this->log === null) {
            return false;
        }

        return true;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $res = true;
        if (is_numeric($this->log)) {

            if ($this->log < 1) {
                $res = \fpcm\classes\loader::getObject('\fpcm\model\system\session')->clearSessions();
            } else {
                $logfile = new \fpcm\model\files\logfile($this->log, false);
                $res = $logfile->clear();
            }
        }
        elseif (trim($this->moduleKey) && \fpcm\module\module::validateKey($this->moduleKey)) {

            $res = $this->events->trigger('logs\clearModuleLog', [
                'key' => $this->moduleKey,
                'log' => $this->log
            ]);
        }

        $this->response->setReturnData(new \fpcm\view\message(
            $res ? 'LOGS_CLEARED_LOG_OK' : 'LOGS_CLEARED_LOG_FAILED',
            $res ? \fpcm\view\message::TYPE_NOTICE : \fpcm\view\message::TYPE_ERROR,
            $res ? \fpcm\view\message::ICON_NOTICE : \fpcm\view\message::ICON_ERROR
        ))->fetch();

    }

}

?>
