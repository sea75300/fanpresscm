<?php

/**
 * System install controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\installer;

define('FPCM_INSTALLER_NOCACHE', true);
define('FPCM_MODE_NOPAGETOKEN', true);

class main extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\system\syscheck,
        \fpcm\controller\traits\common\timezone;
    
    const ACTION = 'system/installer';

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
    protected $tabsDef = [
        1 => [
            'tpl' => '01_selectlang',
            'descr' => 'INSTALLER_LANGUAGE_SELECT',
            'icon' => 'language',
            'back' => 1
        ],
        2 => [
            'tpl' => '02_syscheck',
            'descr' => 'INSTALLER_SYSCHECK',
            'icon' => 'medkit',
            'back' => 2,
            'fill' => true
        ],
        3 => [
            'tpl' => '03_dbdata',
            'descr' => 'INSTALLER_DBCONNECTION',
            'icon' => 'circle-nodes',
            'back' => 3
        ],
        4 => [
            'tpl' => '04_createtables',
            'descr' => 'INSTALLER_CREATETABLES',
            'icon' => 'database',
            'back' => 4
        ],
        5 => [
            'tpl' => '05_sysconfig',
            'descr' => 'INSTALLER_SYSTEMCONFIG',
            'icon' => 'cog',
            'back' => 5,
            'fill' => true
        ],
        6 => [
            'tpl' => '06_firstuser',
            'descr' => 'INSTALLER_ADMINUSER',
            'icon' => 'user-plus',
            'back' => 6,
            'fill' => true
        ],
        7 => [
            'tpl' => '07_finalize',
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
        $this->request = \fpcm\classes\loader::getObject('\fpcm\model\http\request');
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
            trigger_error('Access to disabled installer from ip address ' . $this->request->getIp());
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

        $this->langCode = $this->request->fetchAll('language');
        if (!$this->langCode) {
            $this->langCode = FPCM_DEFAULT_LANGUAGE_CODE;
        }

        $this->language = \fpcm\classes\loader::getObject('\fpcm\classes\language', $this->langCode, false);
        $this->initView();

        return true;
    }

    public function process()
    {
        $tabCount = count(array_keys($this->tabsDef));
        
        if ($this->step > $tabCount) {
            $this->view = new \fpcm\view\error('Undefined installer step!');
            $this->view->render();
            exit;
        }

        $this->view->addJsVars([
            'noRefresh' => true
        ]);

        $this->view->addJsLangVars([
            'INSTALLER_DBCONNECTION_FAILEDMSG',
            'INSTALLER_CREATETABLES_STEP',
            'SAVE_FAILED_PASSWORD_MATCH',
            'INSTALLER_CREATETABLES_ERROR',
            'INSTALLER_CREATETABLES_HEAD'
        ]);
        
        $prevStep = $this->step - 1;
        $nextStep = $this->step + 1;

        if (method_exists($this, 'runAfterStep' . $prevStep)) {
            call_user_func([$this, 'runAfterStep' . $prevStep]);
        }

        if (method_exists($this, 'runStep' . $this->step)) {
            call_user_func([$this, 'runStep' . $this->step]);
        }

        $tplData = $this->tabsDef[$this->step] ?? $this->tabsDef[1];
        
        $buttons = [];
        if ($this->showReloadBtn) {
            $buttons[] = (new \fpcm\view\helper\linkButton('reloadbtn'))->setText('GLOBAL_RELOAD')->setUrl(\fpcm\classes\tools::getControllerLink(self::ACTION, [
                'step' => $this->step,
                'language' => $this->step > 1 ? $this->langCode : ''
            ]))->setIcon('sync');
        }
        elseif ($this->step < $tabCount) {
            
            if ($this->step > 1) {

                $buttons[] = (new \fpcm\view\helper\linkButton('backNext'))
                    ->setText('GLOBAL_BACK')
                    ->setClass('fpcm-installer-next-'.$this->step)
                    ->setIcon('chevron-circle-left')
                    ->setUrl(\fpcm\classes\tools::getControllerLink(self::ACTION, ['step' => $prevStep, 'language' => $this->langCode]) );
            }
            
            $fn = $this->step === 3 ? 'installer.checkDBData' : 0;            
            $buttons[] = (new \fpcm\view\helper\submitButton('submitNext'))
                ->setText('GLOBAL_NEXT')
                ->setClass('fpcm-installer-next-'.$this->step)
                ->setIcon('chevron-circle-right')
                ->setPrimary()
                ->setOnClick($fn);
            

        }

        $this->view->addButtons($buttons);

        $this->view->setFormAction(self::ACTION, [
            'step' => $nextStep,
            'language' => $this->langCode
        ]);

        $this->view->assign('tpl', $tplData['tpl']);
        $this->view->assign('headline', $tplData['descr']);
        $this->view->assign('icon', $tplData['icon']);
        $this->view->assign('fill', $tplData['fill'] ?? false);
        $this->view->assign('step', $this->step);
        $this->view->assign('progressWidth', ceil( ($this->step / $tabCount * 100) ) );
        
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->assign('languages', array_flip($this->language->getLanguages()));
        $this->view->addJsFiles(['{$coreJs}installer.js', '{$coreJs}systemcheck.js']);
        $this->view->addFromLibrary('nkorg/passgen', ['passgen.js']);
        $this->view->showPageToken(true);
        $this->view->setViewPath($this->getViewPath());
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
        $this->view->addJsVars(array(
            'sqlFilesCount' => count(\fpcm\classes\database::getTableFiles()),
        ));
        
        $this->view->assign('progressbarName', 'dbtables');
    }

    /**
     * Installer Step 5
     */
    protected function runStep5()
    {
        if ($this->request->fromGET('cserr') !== null) {
            $this->view->addErrorMessage('SAVE_FAILED_OPTIONS');
        }

        $this->view->addJsVars(array(
            'dtMasks' => $this->getDateTimeMasks()
        ));

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
            $this->redirect(self::ACTION, [
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

            $this->redirect(self::ACTION, [
                'step' => '6',
                'msg' => -6,
                'language' => $this->langCode
            ]);
            $this->afterStepResult = false;
            return false;
        }

        if (in_array($data['username'], FPCM_INSECURE_USERNAMES)) {
            $this->redirect(self::ACTION, [
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

        $this->redirect(self::ACTION, [
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