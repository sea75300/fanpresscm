<?php

/**
 * System install controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\installer;

define('FPCM_INSTALLER_NOCACHE', true);
define('FPCM_MODE_NOPAGETOKEN', true);

class main extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\system\syscheck,
        \fpcm\controller\traits\common\timezone;

    /**
     *
     * @var \fpcm\classes\language
     */
    protected $language;

    /**
     *
     * @var string
     */
    protected $langCode = 'de';

    /**
     *
     * @var int
     */
    protected $step = 1;

    /**
     *
     * @var bool
     */
    protected $afterStepResult = true;

    /**
     *
     * @var bool
     */
    protected $showReloadBtn = false;

    /**
     *
     * @var array
     */
    protected $subTemplates = array(
        1 => '01_selectlang',
        2 => '02_syscheck',
        3 => '03_dbdata',
        4 => '04_createtables',
        5 => '05_sysconfig',
        6 => '06_firstuser',
        7 => '07_finalize'
    );

    /**
     *
     * @var array
     */
    protected $subTabs = [
        '01_selectlang' => [
            'icon' => 'language',
            'descr' => 'INSTALLER_LANGUAGE_SELECT',
            'back' => 1
        ],
        '02_syscheck' => [
            'icon' => 'medkit',
            'descr' => 'INSTALLER_SYSCHECK',
            'back' => 2
        ],
        '03_dbdata' => [
            'icon' => 'database',
            'descr' => 'INSTALLER_DBCONNECTION',
            'back' => 3
        ],
        '04_createtables' => [
            'icon' => 'table',
            'descr' => 'INSTALLER_CREATETABLES',
            'back' => 4
        ],
        '05_sysconfig' => [
            'icon' => 'cog',
            'descr' => 'INSTALLER_SYSTEMCONFIG',
            'back' => 5
        ],
        '06_firstuser' => [
            'icon' => 'user-plus',
            'descr' => 'INSTALLER_ADMINUSER',
            'back' => 6
        ],
        '07_finalize' => [
            'icon' => 'check-square',
            'descr' => 'INSTALLER_FINALIZE',
            'back' => false
        ]
    ];

    /**
     * Konstruktor
     */
    public function __construct()
    {
        session_start();
        return true;
    }
    
    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'installer/main';
    }

    /**
     * 
     * @return bool
     */
    public function hasAccess()
    {
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function request()
    {
        if (!\fpcm\classes\baseconfig::installerEnabled()) {
            trigger_error('Access to disabled installer from ip address ' . \fpcm\classes\http::getIp());
            $this->view = new \fpcm\view\error('The FanPress CM installer is not enabled!');
            $this->view->render();
            exit;
        }

        $this->step = $this->request->fromGET('step', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if (!$this->step) {
            $this->step = 1;
        }

        $this->langCode = $this->request->fromGET('language');
        if (!$this->langCode) {
            $this->langCode = FPCM_DEFAULT_LANGUAGE_CODE;
        }

        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language', $this->langCode);
        $this->initView();

        return true;
    }

    public function process()
    {
        $maxStep = max(array_keys($this->subTemplates));

        if ($this->step > $maxStep) {
            $this->view = new \fpcm\view\error('Undefined installer step!');
            $this->view->render();
            exit;
        }

        $disabledTabs = array_keys(array_keys($this->subTemplates));
        $disabledTabs = array_slice($disabledTabs, ($this->step === 1 ? 1 : $this->step), $maxStep);

        $this->view->addJsVars([
            'disabledTabs' => $disabledTabs,
            'activeTab' => $this->step === 1 ? 0 : $this->step - 1,
            'noRefresh' => true
        ]);

        $this->view->addJsLangVars([
            'INSTALLER_DBCONNECTION_FAILEDMSG',
            'INSTALLER_CREATETABLES_STEP',
            'SAVE_FAILED_PASSWORD_MATCH',
            'INSTALLER_DBCONNECTION_FAILEDMSG'
        ]);
        
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->assign('tabCounter', 1);
        $this->view->assign('subTabs', $this->subTabs);
        $this->view->assign('subTemplate', $this->subTemplates[$this->step]);
        $this->view->assign('maxStep', $maxStep);
        $this->view->assign('step', $this->step + 1);
        $this->view->assign('languages', array_flip($this->language->getLanguages()));
        $this->view->addJsFiles(['{$coreJs}installer.js', '{$coreJs}systemcheck.js', \fpcm\classes\loader::libGetFileUrl('nkorg/passgen/passgen.js')]);

        if (method_exists($this, 'runAfterStep' . ($this->step - 1))) {
            call_user_func(array($this, 'runAfterStep' . ($this->step - 1)));
        }

        if (method_exists($this, 'runStep' . $this->step)) {
            call_user_func(array($this, 'runStep' . $this->step));
        }

        $this->view->setFormAction('installer', [
            'step' => $this->step + 1,
            'language' => $this->langCode
        ]);

        $buttons = [];
        if ($this->showReloadBtn) {
            $buttons[] = (new \fpcm\view\helper\linkButton('reloadbtn'))->setText('GLOBAL_RELOAD')->setUrl(\fpcm\classes\tools::getControllerLink('installer', [
                'step' => $this->step,
                'language' => $this->step > 1 ? $this->langCode : ''
            ]))->setIcon('sync');
        }
        elseif ($this->step < count($this->subTemplates)) {
            $buttons[] = (new \fpcm\view\helper\submitButton('submitNext'))->setText('GLOBAL_NEXT')->setClass('fpcm-installer-next-'.$this->step)->setIcon('chevron-circle-right');
        }

        $this->view->addButtons($buttons);
        $this->view->showPageToken(false);
        $this->view->render();
    }

    /**
     * Installer Step 2
     */
    protected function runStep2()
    {
        $sysCheckResults = $this->getCheckOptionsSystem();

        $isOk = true;
        
        /* @var $value \fpcm\model\system\syscheckOption */
        foreach ($sysCheckResults as $key => $value) {
            
            if ($value->getOptional() || $value->getResult()) {
                continue;;
            }

            $isOk = false;
        }

        if (!$isOk) {
            $this->showReloadBtn = true;
            $this->view->addErrorMessage('INSTALLER_SYSCHECK_FAILEDMSG');
        }

        $this->view->assign('checkOptions', $sysCheckResults);
    }

    /**
     * Installer Step 2 after
     */
    protected function runAfterStep2()
    {
        $availableDrivers = \PDO::getAvailableDrivers();

        $sqlDrivers = [];
        foreach (\fpcm\classes\database::$supportedDBMS as $driver => $name) {

            if (!in_array($driver, $availableDrivers)) {
                continue;
            }

            $sqlDrivers[$name] = $driver;
        }

        $this->view->assign('sqlDrivers', $sqlDrivers);
    }

    /**
     * Installer Step 4
     */
    protected function runStep4()
    {
        $sqlFiles = [];

        $files = \fpcm\classes\database::getTableFiles();
        foreach ($files as $value) {
            $sqlFiles[] = [
                'descr' => substr(basename($value, '.yml'), 2),
                'path' => base64_encode(str_rot13(base64_encode($value)))
            ];
        }

        $this->view->addJsVars(array(
            'sqlFilesCount' => count($sqlFiles),
            'sqlFiles' => $sqlFiles,
        ));
    }

    /**
     * Installer Step 5
     */
    protected function runStep5()
    {
        if ($this->request->fromGET('cserr') !== null) {
            $this->view->addErrorMessage('SAVE_FAILED_OPTIONS');
        }

        $this->view->assign('timezoneAreas', $this->getTimeZonesAreas());
        $this->view->assign('systemModes', [
            'SYSTEM_OPTIONS_USEMODE_IFRAME' => 0,
            'SYSTEM_OPTIONS_USEMODE_PHPINCLUDE' => 1
        ]);
    }

    /**
     * Installer Step 5 after
     */
    protected function runAfterStep5()
    {
        $newconfig = $this->request->fromPOST('conf');
        $newconfig['system_version'] = \fpcm\classes\baseconfig::getVersionFromFile();

        $config = new \fpcm\model\system\config(false, false);
        $config->setNewConfig($newconfig);
        $config->prepareDataSave();

        if (!$config->update()) {
            $this->redirect('installer', [
                'step' => '5',
                'cserr' => '1',
                'language' => $this->langCode
            ]);
        }

        return true;
    }

    /**
     * Installer Step 6
     */
    protected function runStep6()
    {
        $data = $this->request->fromPOST('conf');
        $msg = $this->request->fromGET('msg');
        
        $user = new \fpcm\model\users\author();
        $user->setEmail(isset($data['system_email']) ? $data['system_email'] : (isset($_SESSION['username']) ? $_SESSION['email'] : ''));        
        $user->setUserName(isset($_SESSION['username']) && $msg !== -5 ? $_SESSION['username'] : '');        
        $user->setDisplayName(isset($_SESSION['displayname']) ? $_SESSION['displayname'] : '');        
        $user->setRoll(1);

        $this->view->assign('author', $user);
        $this->view->assign('userRolls', [
            'GLOBAL_ADMINISTRATOR' => 1
        ]);
        
        $this->view->assign('showDisableButton', false);
        $this->view->assign('showExtended', false);
        $this->view->assign('showImage', false);
        $this->view->assign('avatar', false);
        $this->view->assign('externalSave', true);
        $this->view->assign('inProfile', false);
        $this->view->assign('twoFaAuth', false);
        $this->view->addJsLangVars(['SAVE_FAILED_PASSWORD_SECURITY', 'SAVE_FAILED_PASSWORD_SECURITY_PWNDPASS']);

        if ($msg === null) {
            return true;
        }

        switch ($msg) {
            case false :
                $this->view->addErrorMessage('SAVE_FAILED_USER');
                break;
            case \fpcm\model\users\author::AUTHOR_ERROR_PASSWORDINSECURE :
                $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_SECURITY');
                break;
            case \fpcm\model\users\author::AUTHOR_ERROR_EXISTS :
                $this->view->addErrorMessage('SAVE_FAILED_USER_EXISTS');
                break;
            case -4 :
                $this->view->addErrorMessage('SAVE_FAILED_PASSWORD_MATCH');
                break;
            case -5 :
                $this->view->addErrorMessage('SAVE_FAILED_USER_SECURITY');
                break;
            case -6 :
                $this->view->addErrorMessage('SAVE_FAILED_USER');
                break;
        }
    }

    /**
     * Installer Step 6 after
     */
    protected function runAfterStep6()
    {
        $data = $this->request->fromPOST('data');
        $_SESSION['username'] = $data['username'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['displayname'] = $data['displayname'];
        
        foreach ($data as $key => $val) {

            if (trim($val)) {
                continue;
            }

            $this->redirect('installer', [
                'step' => '6',
                'msg' => -6,
                'language' => $this->langCode
            ]);
            $this->afterStepResult = false;
            return false;
        }

        if (in_array($data['username'], FPCM_INSECURE_USERNAMES)) {
            $this->redirect('installer', [
                'step' => '6',
                'msg' => -5,
                'language' => $this->langCode
            ]);
            $this->afterStepResult = false;
            return false;
        }

        $user = new \fpcm\model\users\author();
        $user->setUserName($data['username']);
        $user->setEmail($data['email']);
        $user->setDisplayName($data['displayname']);
        $user->setRoll(1);
        $user->setUserMeta([]);
        $user->setRegistertime(time());
        $user->setChangeTime(time());
        $user->setChangeUser(1);
        
        if ($data['password'] && $data['password_confirm'] && (md5($data['password']) == md5($data['password_confirm']))) {
            $user->setPassword($data['password']);
        } else {
            $res = -4;
            $this->afterStepResult = false;
        }

        if (!isset($res)) {
            $res = $user->save();
            if ($res === true) {
                return true;
            }
        }

        $this->redirect('installer', [
            'step' => '6',
            'msg' => $res,
            'language' => $this->langCode
        ]);

        $this->afterStepResult = false;
        return false;
    }

    /**
     * Installer Step 7
     */
    protected function runStep7()
    {
        $res = true;

        if ($this->afterStepResult) {
            $res = \fpcm\classes\baseconfig::enableInstaller(false);
        }

        $this->view->assign('disableInstallerMsg', !$res);

        $cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $cache->cleanup();
    }

}

?>