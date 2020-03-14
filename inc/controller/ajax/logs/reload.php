<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\logs;

/**
 * AJAX-Controller zum Reload der Systemloads
 * 
 * @package fpcm\controller\ajax\logs
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class reload extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\common\dataView;

    /**
     * System-Logs-Typ
     * @var int
     */
    protected $log;

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
     * @var array
     */
    private $sessionTimeoutStr = '';

    /**
     *
     * @var int
     */
    private $logsize = '';

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->log = $this->request->fromGET('log');
        return $this->log === null ? false : true;
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
        return $this->log == 4 ? 'logs/packages' : '';
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        if (method_exists($this, 'loadLog' . $this->log)) {
            return call_user_func(array($this, 'loadLog' . $this->log));
        }

        $this->logsize = (int) $this->events->trigger('logs\getLogSize', $this->log);
        $this->items = $this->events->trigger('logs\load', $this->log);
        if (!is_array($this->items))  {
            return true;
        }

        $this->initDataView();
        $this->assignDataViewvars();
        return true;
    }

    /**
     * Lädt Sessions-Log (Typ 0)
     * @return bool
     */
    private function loadLog0()
    {
        $this->items = $this->session->getSessions();
        $this->userList = (new \fpcm\model\users\userList())->getUsersAll();

        $this->notfoundStr = $this->language->translate('GLOBAL_NOTFOUND');
        $this->sessionTimeoutStr = $this->language->translate('LOGS_LIST_TIMEOUT');

        $this->initDataView();
        $this->assignDataViewvars();
        return true;
    }

    /**
     * Lädt System-Log (Typ 1)
     * @return bool
     */
    private function loadLog1()
    {
        $log = new \fpcm\model\files\logfile($this->log, false);
        $this->items = $log->fetchData();
        $this->logsize = $log->getFilesize();

        $this->initDataView();
        $this->assignDataViewvars();
        return true;
    }

    /**
     * Lädt PHP-Error-Log (Typ 2)
     * @return bool
     */
    private function loadLog2()
    {
        return $this->loadLog1();
    }

    /**
     * Lädt Datenbank-Log (Typ 3)
     * @return bool
     */
    private function loadLog3()
    {
        return $this->loadLog1();
    }

    /**
     * Lädt Cronjob-Log (Typ 4)
     * @return bool
     */
    private function loadLog4()
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
     * Lädt Cronjob-Log (Typ 5)
     * @return bool
     */
    private function loadLog5()
    {
        return $this->loadLog1();
    }

    /**
     * Lädt Events-Log (Typ 6)
     * @return bool
     */
    private function loadLog6()
    {
        return $this->loadLog1();
    }

    /**
     * 
     * @return bool
     */
    private function assignDataViewvars()
    {
        $dvVars = $this->dataView->getJsVars();
        $this->returnData = [
            'dataViewVars' => $dvVars['dataviews']['logs'],
            'dataViewName' => 'logs',
            'logsize' => \fpcm\classes\tools::calcSize($this->logsize),
            'fullheight' => $this->logsize > 1048576 ? false : true
        ];

        $this->getSimpleResponse();

        return true;
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
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        if (method_exists($this, 'getCols' . $this->log)) {
            return call_user_func(array($this, 'getCols' . $this->log));
        }

        return $this->events->trigger('logs\getCols', $this->log);
    }

    /**
     * Lädt System-Log (Typ 1)
     */
    private function getCols0()
    {
        return [
            (new \fpcm\components\dataView\column('user', 'LOGS_LIST_USER', 'fpcm-ui-padding-md-left'))->setSize(2),
            (new \fpcm\components\dataView\column('ipaddress', 'LOGS_LIST_IPADDRESS'))->setSize(2),
            (new \fpcm\components\dataView\column('login', 'LOGS_LIST_LOGIN'))->setSize(2),
            (new \fpcm\components\dataView\column('logout', 'LOGS_LIST_LOGOUT'))->setSize(2),
            (new \fpcm\components\dataView\column('external', 'GLOBAL_EXTERNAL'))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('useragent', 'LOGS_LIST_USERAGENT'))->setSize(3)->setAlign('center'),
        ];
    }

    /**
     * 
     * @return array
     */
    private function getCols1()
    {
        return [
            (new \fpcm\components\dataView\column('time', 'LOGS_LIST_TIME', 'fpcm-ui-padding-md-left'))->setSize(2),
            (new \fpcm\components\dataView\column('text', 'LOGS_LIST_TEXT'))->setSize(10),
        ];
    }

    /**
     * 
     * @return array
     */
    private function getCols2()
    {
        return $this->getCols1();
    }

    /**
     * 
     * @return array
     */
    private function getCols3()
    {
        return $this->getCols1();
    }

    /**
     * 
     * @return array
     */
    private function getCols5()
    {
        return $this->getCols1();
    }

    /**
     * 
     * @return array
     */
    private function getCols6()
    {
        return $this->getCols1();
    }

    /**
     * 
     * @param mixed $item
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($item)
    {
        if (method_exists($this, 'getRow' . $this->log)) {
            return call_user_func(array($this, 'getRow' . $this->log), $item);
        }

        return $this->events->trigger('logs\getRow', [
            'log' => $this->log,
            'item' => $item
        ]);
    }
    
    /**
     * Lädt System-Log (Typ 0)
     * @param \fpcm\model\system\session $item
     * @return \fpcm\components\dataView\row
     */
    private function getRow0($item)
    {
        $username = isset($this->userList[$item->getUserId()]) ? $this->userList[$item->getUserId()]->getDisplayName() : $this->notfoundStr;

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('user', new \fpcm\view\helper\escape($username)),
            new \fpcm\components\dataView\rowCol('ipaddress', new \fpcm\view\helper\escape($item->getIp())),
            new \fpcm\components\dataView\rowCol('login', new \fpcm\view\helper\dateText($item->getLogin())),
            new \fpcm\components\dataView\rowCol('logout', ($item->getLogout() ? new \fpcm\view\helper\dateText($item->getLogout()) : $this->sessionTimeoutStr)),
            new \fpcm\components\dataView\rowCol('external', (new \fpcm\view\helper\boolToText(uniqid('sessext')))->setValue($item->getExternal())),
            new \fpcm\components\dataView\rowCol('useragent', new \fpcm\view\helper\escape($item->getUseragent()))
        ]);
    }

    /**
     * 
     * @param \fpcm\model\files\logfile $item
     * @return \fpcm\components\dataView\row
     */
    private function getRow1($item)
    {
        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('time', $item->time, 'fpcm-ui-dataview-align-self-start'),
            new \fpcm\components\dataView\rowCol('text', str_replace(['&NewLine;', PHP_EOL], '<br>', new \fpcm\view\helper\escape($item->text)), 'pre-box'),
        ]);
    }

    /**
     * 
     * @param \fpcm\model\files\logfile $item
     * @return \fpcm\components\dataView\row
     */
    private function getRow2($item)
    {
        return $this->getRow1($item);
    }

    /**
     * 
     * @param \fpcm\model\files\logfile $item
     * @return \fpcm\components\dataView\row
     */
    private function getRow3($item)
    {
        return $this->getRow1($item);
    }

    /**
     * 
     * @param \fpcm\model\files\logfile $item
     * @return \fpcm\components\dataView\row
     */
    private function getRow5($item)
    {
        return $this->getRow1($item);
    }

    /**
     * 
     * @param \fpcm\model\files\logfile $item
     * @return \fpcm\components\dataView\row
     */
    private function getRow6($item)
    {
        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('time', $item->time, 'fpcm-ui-dataview-align-self-start'),
            new \fpcm\components\dataView\rowCol('text', str_replace(['&NewLine;', PHP_EOL], '<br>', new \fpcm\view\helper\escape($item->text)), 'pre-box'),
        ]);
    }

}

?>
