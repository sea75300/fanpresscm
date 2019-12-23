<?php

namespace fpcm\controller\action\modules;

/**
 * Module list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
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
        $this->uploadModule();
        return true;
    }

    /**
     * 
     * @return bool
     */
    public function process()
    {
        $this->view->addJsLangVars([
            'MODULES_LIST_INFORMATIONS', 'MODULES_FAILED_ENABLE', 'MODULES_FAILED_DISABLE',
            'MODULES_FAILED_INSTALL', 'MODULES_FAILED_UNINSTALL'
        ]);

        $this->view->addJsFiles(['modules/list.js', 'files/uploader.js']);
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
        
        $buttons = [];
        if (\fpcm\classes\baseconfig::canConnect() && $this->permissions->modules->install) {
            $buttons[] = (new \fpcm\view\helper\button('checkUpdate', 'checkUpdate'))->setText('PACKAGES_MANUALCHECK')->setIcon('sync');
            
            $updatesAvailable = (new \fpcm\module\modules())->getInstalledUpdates();
            if (count($updatesAvailable)) {
                $this->view->addJsVars(['updateAllkeys' => $updatesAvailable]);
                $buttons[] = (new \fpcm\view\helper\button('runUpdateAll', 'runUpdateAll'))->setText('MODULES_LIST_UPDATE_ALL')->setIcon('sync');
            }
            
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
     * @return bool
     */
    private function uploadModule()
    {
        if (!$this->permissions->modules->install) {
            return false;
        }
        
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
