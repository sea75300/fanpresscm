<?php

namespace fpcm\controller\action\modules;

/**
 * Module list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class modulelist extends \fpcm\controller\abstracts\controller {

    /**
     *
     * @var \fpcm\module\modules
     */
    protected $modules;

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
     * @return array
     */
    protected function getPermissions()
    {
        return [
            'system' => 'options',
            'modules' => 'configure'
        ];
    }
    
    /**
     * 
     * @return boolean
     */
    public function request()
    {        
        $this->view->setViewVars([
            'canInstall' => $this->permissions->check(['modules' => 'install']),
            'canUninstall' => $this->permissions->check(['modules' => 'uninstall']),
            'canConfigure' => $this->permissions->check(['modules' => 'configure']),
        ]);

        $this->uploadModule();
        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function process()
    {
        $this->view->addJsLangVars([
            'MODULES_LIST_INFORMATIONS', 'MODULES_FAILED_ENABLE', 'MODULES_FAILED_DISABLE',
            'MODULES_FAILED_INSTALL', 'MODULES_FAILED_UNINSTALL'
        ]);
        $this->view->addJsFiles(['modulelist.js', 'fileuploader.js']);
        $this->view->setFormAction('modules/list');
        $this->view->addJsVars([
            'jqUploadInit' => 0,
            'codes' => [
                'installFailed' => \fpcm\module\module::STATUS_NOT_INSTALLED,
                'uninstallFailed' => \fpcm\module\module::STATUS_NOT_UNINSTALLED,
                'enabledFailed' => \fpcm\module\module::STATUS_NOT_ENABLED,
                'disabledFailed' => \fpcm\module\module::STATUS_NOT_DISABLED,
            ]
        ]);
        
        $this->view->addDataView(new \fpcm\components\dataView\dataView('modulesLocal', false));
        $this->view->addDataView(new \fpcm\components\dataView\dataView('modulesRemote', false));
        
        if (\fpcm\classes\baseconfig::canConnect() && $this->permissions->check(['modules' => 'install'])) {
            $buttons[] = (new \fpcm\view\helper\button('checkUpdate', 'checkUpdate'))->setText('PACKAGES_MANUALCHECK')->setIcon('sync');
        }
        
        $this->view->addButtons($buttons);

        $this->view->assign('maxFilesInfo', $this->language->translate('FILE_LIST_PHPMAXINFO', [            
            '{{filecount}}' => 1,
            '{{filesize}}' => \fpcm\classes\tools::calcSize(\fpcm\classes\baseconfig::uploadFilesizeLimit(true), 0)
        ]));

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function uploadModule()
    {
        $files = \fpcm\classes\http::getFiles();
        if (!$this->buttonClicked('uploadFile') || !$files) {
            return true;
        }

        $uploader = new \fpcm\model\files\fileuploader($files);
        if ($uploader->processModuleUpload() == true) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_UPLOADMODULE');
            return true;
        }

        $this->view->addErrorMessage('SAVE_FAILED_UPLOADMODULE');
        return true;
        
    }

}

?>
