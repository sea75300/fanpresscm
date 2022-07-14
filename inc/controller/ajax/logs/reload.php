<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\logs;

/**
 * AJAX-Controller zum Reload der Systemloads
 * 
 * @package fpcm\controller\ajax\logs
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class reload extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\common\dataView;

    /**
     * Log file
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
     * Array mit Benutzern
     * @var array
     */
    protected $userList = [];

    /**
     *
     * @var array
     */
    private $notfoundStr = '';

    /**
     *
     * @var int
     */
    private $logsize = '';

    /**
     *
     * @var \fpcm\model\files\logfileResult
     */
    private $logObj = '';

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->log = $this->request->fromGET('log');
        $this->moduleKey = $this->request->fromGET('key');
        $this->isSystem = $this->request->fromGET('system', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);
        
        return $this->log !== null;
    }

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
        return $this->log === \fpcm\model\files\logfile::FPCM_LOGFILETYPE_PKGMGR ? 'logs/packages' : '';
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {    
        if (!$this->isSystem) {
            return $this->getModuleLog();
        }
        
        if (method_exists($this, 'loadLog' . $this->log)) {
            return call_user_func(array($this, 'loadLog' . $this->log));
        }

        return $this->loadGeneric();
    }

    /**
     * 
     * @return bool
     */
    private function getModuleLog() : bool
    {
        if ($this->isSystem || !trim($this->moduleKey) || !\fpcm\module\module::validateKey($this->moduleKey)) {
            return false;
        }

        /* @var $log \fpcm\model\files\logfileResult */
        $this->logObj = $this->events->trigger('logs\getModuleLog', [
            'key' => $this->moduleKey,
            'log' => $this->log
        ]);

        if (!$this->logObj instanceof \fpcm\model\logs\logfileResult) {
            return false;
        }

        if (!$this->logObj->asObject()) {
            $this->view->assign('items', $this->logObj->fetchData());
            $this->view->assign('size', \fpcm\classes\tools::calcSize( $this->logObj->getSize() ) );
            $this->view->render();
            return true;
        }

        $this->logsize = $this->logObj->getSize();
        $this->items = $this->logObj->fetchData();
        $this->itemsCount = $this->logObj->getItemsCount();

        $this->initDataView();
        $this->assignDataViewvars();
        return true;
    }

    /**
     * Lädt Sessions-Log (Typ 0)
     * @return bool
     */
    private function loadLogSessions()
    {
        $this->items = $this->session->getSessions();
        $this->userList = (new \fpcm\model\users\userList())->getUsersAll();

        $this->notfoundStr = $this->language->translate('GLOBAL_NOTFOUND');

        $this->initDataView();
        $this->assignDataViewvars();
        return true;
    }

    /**
     * Lädt Sessions-Log (Typ 0)
     * @return bool
     */
    private function loadGeneric()
    {
        $log = new \fpcm\model\files\logfile($this->log, false);
        $this->items = $log->fetchData();
        $this->logsize = $log->getFilesize();

        $this->initDataView();
        $this->assignDataViewvars();
        return true;
    }

    /**
     * Lädt Cronjob-Log (Typ 4)
     * @return bool
     */
    private function loadLogPackages()
    {
        $this->initView();
        $log = new \fpcm\model\files\logfile($this->log);

        $this->view->assign('colorCb', function(string $data) {
            return preg_replace(
                '/(.+\ \>{1}\ .+)(skipped)/im',
                '<span class="fpcm ui-color-font-grey">$0</span>',
                $data
            );
        });

        $this->view->assign('items', $log->fetchData());
        $this->view->assign('size', \fpcm\classes\tools::calcSize($log->getFilesize()));
        $this->view->render();
    }

    /**
     * 
     * @return bool
     */
    private function assignDataViewvars()
    {
        $dvVars = $this->dataView->getJsVars();

        $this->response->setReturnData([
            'dataViewVars' => $dvVars['dataviews']['logs'],
            'dataViewName' => 'logs',
            'logsize' => \fpcm\classes\tools::calcSize($this->logsize),
            'fullheight' => $this->logsize > 1048576 ? false : true
        ])->fetch();
    }

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'logs';
    }

    /**
     * Init data view cols
     * @return array
     */
    protected function getDataViewCols()
    {
        if ($this->isSystem) {
            
            if (method_exists($this, 'getCols' . $this->log)) {
                return call_user_func(array($this, 'getCols' . $this->log));
            }

            return $this->getColsGeneric();
        }

        if (!$this->logObj instanceof \fpcm\model\logs\logfileResult) {
            return [];
        }
        
        return $this->logObj->colsCallback()();
    }

    /**
     * Generic log cols
     * @return array
     */
    private function getColsGeneric() : array
    {
        return [
            (new \fpcm\components\dataView\column('time', 'LOGS_LIST_TIME'))->setSize(2),
            (new \fpcm\components\dataView\column('text', 'LOGS_LIST_TEXT'))->setSize(10),
        ];
    }

    /**
     * Session log cols
     * @return array
     */
    private function getColsSessions() : array
    {
        return [
            (new \fpcm\components\dataView\column('sessionid', 'LOGS_LIST_SESSIONID'))->setSize(4),
            (new \fpcm\components\dataView\column('user', 'LOGS_LIST_USER'))->setSize(1),
            (new \fpcm\components\dataView\column('ipaddress', 'LOGS_LIST_IPADDRESS'))->setSize(2),
            (new \fpcm\components\dataView\column('login', 'LOGS_LIST_LOGIN'))->setSize(1),
            (new \fpcm\components\dataView\column('logout', 'LOGS_LIST_LOGOUT'))->setSize(1),
            (new \fpcm\components\dataView\column('useragent', 'LOGS_LIST_USERAGENT'))->setSize(2),
            (new \fpcm\components\dataView\column('external', 'GLOBAL_EXTERNAL', 'flex-grow-1'))->setSize('auto ')->setAlign('center'),
        ];
    }

    /**
     * Init data view row
     * @param mixed $item
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($item)
    {
        if ($this->isSystem) {
            
            if (method_exists($this, 'getRow' . $this->log)) {
                return call_user_func([$this, 'getRow' . $this->log], $item);
            }

            return $this->getRowGeneric($item);
        }
        
        if (!$this->logObj instanceof \fpcm\model\logs\logfileResult) {
            return [];
        }

        return $this->logObj->rowCallback()($item);

    }

    /**
     * Generic log row
     * @param \fpcm\model\files\logfile $item
     * @return \fpcm\components\dataView\row
     */
    private function getRowGeneric($item) : \fpcm\components\dataView\row
    {
        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('time', $item->time),
            new \fpcm\components\dataView\rowCol('text', str_replace(['&NewLine;', PHP_EOL], '<br>', new \fpcm\view\helper\escape($item->text)), 'pre-box'),
        ], ( isset($item->type) && trim($item->type) ? 'fpcm ui-logs-'.$item->type : '' ) );
    }
    
    /**
     * Session log row
     * @param \fpcm\model\system\session $item
     * @return \fpcm\components\dataView\row
     */
    private function getRowSessions($item) : \fpcm\components\dataView\row
    {
        $username = isset($this->userList[$item->getUserId()]) ? $this->userList[$item->getUserId()]->getDisplayName() : $this->notfoundStr;

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('sessionid', new \fpcm\view\helper\escape($item->getSessionId()), 'text-truncate'),
            new \fpcm\components\dataView\rowCol('user', new \fpcm\view\helper\escape($username)),
            new \fpcm\components\dataView\rowCol('ipaddress', new \fpcm\view\helper\escape($item->getIp())),
            new \fpcm\components\dataView\rowCol('login', new \fpcm\view\helper\dateText($item->getLogin())),
            new \fpcm\components\dataView\rowCol('logout', new \fpcm\view\helper\dateText( $item->getLogout() ? $item->getLogout() : $item->getLastaction() )),
            new \fpcm\components\dataView\rowCol('useragent', new \fpcm\view\helper\escape($item->getUseragent())),
            new \fpcm\components\dataView\rowCol('external', (new \fpcm\view\helper\boolToText(uniqid('sessext')))->setValue($item->getExternal())),
        ]);
    }

    /**
     * Events log row
     * @param \fpcm\model\files\logfile $item
     * @return \fpcm\components\dataView\row
     */
    private function getRowEvents($item) : \fpcm\components\dataView\row
    {
        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('time', $item->time),
            new \fpcm\components\dataView\rowCol('text', str_replace(['&NewLine;', PHP_EOL], '<br>', new \fpcm\view\helper\escape($item->text)), 'pre-box'),
        ]);
    }

}

?>
