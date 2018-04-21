<?php

/**
 * Module list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\modules;

class modulelist extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\modules\moduleactions;

    /**
     *
     * @var \fpcm\modules\modules
     */
    protected $modules;

    /**
     * 
     * @return string
     */
    protected function getViewPath()
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
     * @return type
     */
    protected function getPermissions()
    {
        return [
            'system' => 'options',
            'modules' => 'configure'
        ];
    }

    public function process()
    {
        $this->view->addJsLangVars(['MODULES_LIST_INFORMATIONS']);
        $this->view->addJsFiles(['modulelist.js', 'fileuploader.js']);
        $this->view->addJsVars([
            'jqUploadInit' => 0
        ]);
        
        $this->view->setViewVars([
            'canInstall' => $this->permissions->check(['modules' => 'install']),
            'canUninstall' => $this->permissions->check(['modules' => 'uninstall']),
            'canConfigure' => $this->permissions->check(['modules' => 'configure']),
        ]);
        
        $this->view->addDataView(new \fpcm\components\dataView\dataView('modulesLocal', false));
        $this->view->addDataView(new \fpcm\components\dataView\dataView('modulesRemote', false));

//        $this->view->assign('maxFilesInfo', $this->lang->translate('FILE_LIST_PHPMAXINFO', [            
//            '{{filecount}}' => 1,
//            '{{filesize}}' => \fpcm\classes\tools::calcSize(\fpcm\classes\baseconfig::uploadFilesizeLimit(true), 0)
//        ]));

    }

}

?>
