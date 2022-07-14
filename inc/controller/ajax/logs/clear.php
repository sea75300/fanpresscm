<?php

/**
 * AJAX logs clear controller
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\logs;

/**
 * AJAX-Controller zum leeren der Systemlogs
 * 
 * @package fpcm\controller\ajax\logs\clear
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class clear extends \fpcm\controller\abstracts\ajaxController
{

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
     * Is system logfile
     * @var int
     */
    protected $isSystem = 0;

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

        $this->isSystem = $this->request->fromPOST('system', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        return $this->log !== null;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $res = true;
        if ($this->isSystem) {

            $res = method_exists($this, 'clear' . $this->log)
                 ? call_user_func(array($this, 'clear' . $this->log))
                 : $this->clearGeneric();

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

    /**
     * 
     * @return bool
     */
    private function clearSessions() : bool
    {
        return \fpcm\classes\loader::getObject('\fpcm\model\system\session')->clearSessions();
    }

    /**
     * 
     * @return bool
     */
    private function clearGeneric() : bool
    {
        $logfile = new \fpcm\model\files\logfile($this->log, false);
        return $logfile->clear();
    }

}

?>
