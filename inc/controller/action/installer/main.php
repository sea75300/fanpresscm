<?php

/**
 * System install controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\installer;

define('FPCM_INSTALLER_NOCACHE', true);

class main extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\system\syscheck,
        \fpcm\controller\traits\common\timezone;

    /**
     *
     * @var \fpcm\classes\language
     */
    protected $lang;

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
    protected function getViewPath()
    {
        return 'installer/main';
    }

    /**
     * 
     * @return boolean
     */
    public function hasAccess()
    {
        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function request()
    {
        if (!\fpcm\classes\baseconfig::installerEnabled()) {
            trigger_error('Access to disabled installer from ip address ' . \fpcm\classes\http::getIp());
            $this->view = new \fpcm\view\error('The FanPress CM installer is not enabled!');
            $this->view->render();
            exit;
        }

        $this->step = $this->getRequestVar('step', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        if (!$this->step) {
            $this->step = 1;
        }

        $this->langCode = $this->getRequestVar('language');
        if (!$this->langCode) {
            $this->langCode = FPCM_DEFAULT_LANGUAGE_CODE;
        }

        $this->lang = \fpcm\classes\loader::getObject('\fpcm\classes\language', $this->langCode);
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
        $this->view->assign('currentStep', $this->step);
        $this->view->assign('step', $this->step + 1);
        $this->view->assign('showNextButton', $this->step > 1 ? true : false);
        $this->view->assign('showReload', false);
        $this->view->assign('languages', array_flip($this->lang->getLanguages()));
        $this->view->addJsFiles(['installer.js', 'systemcheck.js', \fpcm\classes\loader::libGetFileUrl('password-generator/password-generator.min.js')]);

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
            $this->view->assign('showReload', true);
            $this->view->assign('showNextButton', false);
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
        if ($this->getRequestVar('cserr') !== null) {
            $this->view->addErrorMessage('SAVE_FAILED_OPTIONS');
        }

        $timezones = [];
        foreach ($this->getTimeZones() as $area => $zones) {
            foreach ($zones as $zone) {
                $timezones[$area][$zone] = $zone;
            }
        }

        $this->view->assign('timezoneAreas', $timezones);
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
        $newconfig = $this->getRequestVar('conf');
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
        $data = $this->getRequestVar('conf');
        $msg = $this->getRequestVar('msg');
        
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
        $username = $this->getRequestVar('username');
        $email = $this->getRequestVar('email');
        $displayname = $this->getRequestVar('displayname');
        
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['displayname'] = $displayname;
        
        foreach ($this->getRequestVar() as $key => $data) {
            if ($data == '' && !in_array($key, array('module', 'step', 'btnSubmitNext', 'language'))) {
                $this->redirect('installer', [
                    'step' => '6',
                    'msg' => -6,
                    'language' => $this->langCode
                ]);
                $this->afterStepResult = false;
                return false;
            }
        }

        $insecureUserNamens = json_decode(FPCM_INSECURE_USERNAMES, true);
        if (in_array($username, $insecureUserNamens)) {
            $this->redirect('installer', [
                'step' => '6',
                'msg' => -5,
                'language' => $this->langCode
            ]);
            $this->afterStepResult = false;
            return false;
        }

        $user = new \fpcm\model\users\author($username);
        $user->setUserName($username);
        $user->setEmail($email);
        $user->setDisplayName($displayname);
        $user->setRoll(1);
        $user->setUserMeta([]);
        $user->setRegistertime(time());

        $newpass = $this->getRequestVar('password');
        $newpass_confirm = $this->getRequestVar('password_confirm');

        if ($newpass && $newpass_confirm && (md5($newpass) == md5($newpass_confirm))) {
            $user->setPassword($newpass);
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
        $this->view->assign('showNextButton', false);

        $cache = \fpcm\classes\loader::getObject('\fpcm\classes\cache');
        $cache->cleanup();
    }

}

?>