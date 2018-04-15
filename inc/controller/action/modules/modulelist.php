<?php

/**
 * Module list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\modules;

class modulelist extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\modules\moduleactions,
        \fpcm\controller\traits\common\dataView;

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

    /**
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        return [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize('05')->setAlign('center'),
            (new \fpcm\components\dataView\column('buttons', ''))->setAlign('center')->setSize(3),
            (new \fpcm\components\dataView\column('key', 'MODULES_LIST_KEY'))->setAlign('center')->setSize(3),
            (new \fpcm\components\dataView\column('description', 'MODULES_LIST_NAME'))->setAlign('center')->setSize(3),
            (new \fpcm\components\dataView\column('version', 'MODULES_LIST_VERSION_LOCAL'))->setAlign('center')->setSize(2)
        ];
    }

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'modulelist';
    }

    /**
     * 
     * @param \fpcm\modules\module $item
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($item)
    {
        $key = $item->getConfig()->key;
        
        $buttons = [];        
        
        $buttons[] = '<div class="fpcm-ui-controlgroup">';
        
        if ($item->isInstalled()) {
            $buttons[]  = $item->isActive()
                        ? (new \fpcm\view\helper\button('disable'.$key))->setText('MODULES_LIST_DISABLE')->setIcon('toggle-off')->setIconOnly(true)
                        : (new \fpcm\view\helper\button('enable'.$key))->setText('MODULES_LIST_ENABLE')->setIcon('toggle-on')->setIconOnly(true);

            $buttons[] = (new \fpcm\view\helper\button('uninstall'.$key))->setText('MODULES_LIST_UNINSTALL')->setIcon('minus-circle')->setIconOnly(true);
            $buttons[] = (new \fpcm\view\helper\button('update'.$key))->setText('MODULES_LIST_UPDATE')->setIcon('sync')->setIconOnly(true);
        }

        $buttons[] = (new \fpcm\view\helper\button('info'.$key))->setText('MODULES_LIST_INFORMATIONS')->setIcon('info-circle')->setIconOnly(true);
        $buttons[] = '</div>';

        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('modulekeys[]', 'chbx' . md5($key) ))->setClass('fpcm-ui-list-checkbox')->setValue($key), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('buttons', implode('', $buttons)),
            new \fpcm\components\dataView\rowCol('key', new \fpcm\view\helper\escape($key) ),
            new \fpcm\components\dataView\rowCol('description', new \fpcm\view\helper\escape($item->getConfig()->name ) ),
            new \fpcm\components\dataView\rowCol('version', new \fpcm\view\helper\escape($item->getConfig()->version) )
        ]);
    }

    protected function initActionObjects()
    {
        $this->modules = new \fpcm\modules\modules();
        
        $this->items = $this->modules->getInstalledModules();
        $this->itemsCount = count($this->items);
    }

    public function request()
    {

//        $this->moduleList = new \fpcm\model\modules\modulelist();
//
//        $this->moduleActions = array(
//            $this->lang->translate('MODULES_LIST_INSTALL') => 'install',
//            $this->lang->translate('MODULES_LIST_UNINSTALL') => 'uninstall',
//            $this->lang->translate('MODULES_LIST_UPDATE') => 'update',
//            $this->lang->translate('MODULES_LIST_ENABLE') => 'enable',
//            $this->lang->translate('MODULES_LIST_DISABLE') => 'disable'
//        );
//
//        if (!is_null(\fpcm\classes\http::getFiles())) {
//            $uploader = new \fpcm\model\files\fileuploader(\fpcm\classes\http::getFiles());
//            $res = $uploader->processModuleUpload();
//
//            if ($res == true) {
//                $this->view->addNoticeMessage('SAVE_SUCCESS_UPLOADMODULE');
//            } else {
//                $this->view->addErrorMessage('SAVE_FAILED_UPLOADMODULE');
//            }
//        }

        return true;
    }

    public function process()
    {
        $this->view->addJsLangVars(['MODULES_LIST_INFORMATIONS']);
        $this->view->addJsFiles(['modulelist.js', 'fileuploader.js']);
        $this->view->addJsVars(['jqUploadInit' => 0]);

        $this->initDataView();

//        $this->assignModules($this->moduleList);
//
//        if (!$this->permissions->check(array('modules' => 'install'))) {
//            unset($this->moduleActions[$this->lang->translate('MODULES_LIST_INSTALL')], $this->moduleActions[$this->lang->translate('MODULES_LIST_UPDATE')]);
//        }
//        if (!$this->permissions->check(array('modules' => 'uninstall'))) {
//            unset($this->moduleActions[$this->lang->translate('MODULES_LIST_UNINSTALL')]);
//        }
//        if (!$this->permissions->check(array('modules' => 'enable'))) {
//            unset($this->moduleActions[$this->lang->translate('MODULES_LIST_ENABLE')], $this->moduleActions[$this->lang->translate('MODULES_LIST_DISABLE')]);
//        }
//
//        $this->view->assign('moduleManagerMode', true);
//        $this->view->assign('styleLeftMargin', true);
//
//        if (!\fpcm\classes\baseconfig::canConnect()) {
//            unset($this->moduleActions[$this->lang->translate('MODULES_LIST_INSTALL')], $this->moduleActions[$this->lang->translate('MODULES_LIST_UPDATE')]);
//            $this->view->assign('moduleManagerMode', false);
//        }
//
//        $translInfo = array(
//            '{{filecount}}' => 1,
//            '{{filesize}}' => \fpcm\classes\tools::calcSize(\fpcm\classes\baseconfig::uploadFilesizeLimit(true), 0)
//        );
//        $this->view->assign('maxFilesInfo', $this->lang->translate('FILE_LIST_PHPMAXINFO', $translInfo));
//        $this->view->assign('actionPath', \fpcm\classes\tools::getFullControllerLink('modules/list'));
//        $this->view->assign('styleLeftMargin', true);
//        $this->view->assign('moduleActions', $this->moduleActions);
//        $this->view->render();
    }

}

?>
