<?php

namespace fpcm\controller\action\modules;

/**
 * Module list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class modulelist extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    /**
     *
     * @var \fpcm\module\modules
     */
    protected $modules;

    /**
     *
     * @var bool
     */
    protected $uploadDisabled;

    /**
     *
     * @var bool
     */
    protected $tabs = [];

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->options && $this->permissions->modules->configure;
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'modules/list';
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'hl_modules';
    }
    
    /**
     * 
     * @return bool
     */
    public function request()
    {
        $this->uploadDisabled = defined('FPCM_DISABLE_MODULE_ZIPUPLOAD') && FPCM_DISABLE_MODULE_ZIPUPLOAD;
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function process()
    {

        $this->view->addJsLangVars([
            'MODULES_LIST_INFORMATIONS', 'MODULES_FAILED_ENABLE',
            'MODULES_FAILED_DISABLE', 'MODULES_FAILED_INSTALL',
            'MODULES_FAILED_UNINSTALL', 'MODULES_LIST_INSTALL'
        ]);

        $this->view->addJsFiles(['modules/list.js']);
        $this->view->setFormAction('modules/list');
        $this->view->addJsVars([
            'codes' => [
                'installFailed' => \fpcm\module\module::STATUS_NOT_INSTALLED,
                'uninstallFailed' => \fpcm\module\module::STATUS_NOT_UNINSTALLED,
                'enabledFailed' => \fpcm\module\module::STATUS_NOT_ENABLED,
                'disabledFailed' => \fpcm\module\module::STATUS_NOT_DISABLED,
            ],
            'uploadDest' => 'modules'
        ]);

        $this->view->assign('canUpload', !$this->uploadDisabled);
        $this->view->assign('uploadMultiple', false);
        $this->view->addDataView(new \fpcm\components\dataView\dataView('modulesLocal', false));
        $this->view->addDataView(new \fpcm\components\dataView\dataView('modulesRemote', false));
        
        $buttons = [];
        if (\fpcm\classes\baseconfig::canConnect() && $this->permissions->modules->install) {
            $buttons[] = (new \fpcm\view\helper\button('checkUpdate', 'checkUpdate'))->setText('PACKAGES_MANUALCHECK')->setIcon('sync');
            
            $updatesAvailable = (new \fpcm\module\modules())->getInstalledUpdates();
            if (count($updatesAvailable) > 1) {
                $buttons[] = (new \fpcm\view\helper\linkButton('runUpdateAll'))
                        ->setUrl(\fpcm\classes\tools::getFullControllerLink('package/modupdate', [
                                'key' => array_shift($updatesAvailable),
                                'updateKeys' => urlencode(base64_encode($this->crypt->encrypt(implode(';', $updatesAvailable))))
                            ])
                        )->setText('MODULES_LIST_UPDATE_ALL')
                        ->setIcon('sync');
            }
            
        }

        $this->view->addButtons($buttons);
        
        $this->tabs = [
            
            (new \fpcm\view\helper\tabItem('moduleslocal'))
                ->setText('MODULES_LIST_HEADLINE')
                ->setUrl(\fpcm\classes\tools::getControllerLink('ajax/modules/fetch', ['mode' => 'local']))
                ->setData(['dataview-list' => 'modulesLocal']),
            (new \fpcm\view\helper\tabItem('modulesremote'))
                ->setText('MODULES_LIST_AVAILABLE')
                ->setUrl(\fpcm\classes\tools::getControllerLink('ajax/modules/fetch', ['mode' => 'remote']))
                ->setData(['dataview-list' => 'modulesRemote']),            
        ];
        
        $this->view->includeForms('modules');
        $this->view->addTabs('modulemgr', $this->tabs);
        $this->initUpload();
        return true;
    }

    /**
     * 
     * @return bool
     */
    private function initUpload() : bool
    {
        if ($this->uploadDisabled) {
            return false;
        }

        /* @var $uploader \fpcm\components\fileupload\jqupload */
        $uploader = \fpcm\components\components::getFileUploader();
        $this->view->addJsFiles($uploader->getJsFiles());
        $this->view->addJsFilesLate($uploader->getJsFilesLate());
        $this->view->addCssFiles($uploader->getCssFiles());
        $this->view->addJsVars($uploader->getJsVars());
        $this->view->addJsLangVars($uploader->getJsLangVars());
        
        $vvars = $uploader->getViewVars();
        $this->view->setViewVars($vvars);
        
        $this->tabs[] = (new \fpcm\view\helper\tabItem('remote'))
            ->setText('MODULES_LIST_UPLOAD')
            ->setFile($vvars['uploadTemplatePath']);
        
        return true;
    }

}

?>
