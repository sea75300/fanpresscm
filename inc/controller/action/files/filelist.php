<?php

/**
 * File manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\files;

class filelist extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\files\lists;

    /**
     * Dateiliste
     * @var \fpcm\model\files\imagelist
     */
    protected $fileList;

    /**
     * Benutzerliste
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     * Modus
     * @var int
     */
    protected $mode = 1;

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'filemanager/listouter';
    }

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['uploads' => 'visible'];
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'hl_files_mng';
    }

    /**
     * 
     * @return bool
     */
    public function request()
    {
        $this->initPermissions();
        
        $this->fileList = new \fpcm\model\files\imagelist();

        $styleLeftMargin = true;
        
        $this->mode = $this->getRequestVar('mode', [\fpcm\classes\http::FILTER_CASTINT]);
        if ($this->mode > 1) {
            $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
            $styleLeftMargin = false;
        }

        $this->view->assign('styleLeftMargin', $styleLeftMargin);

        $this->uploadPhpForm();
        $this->deleteFiles();

        return true;
    }
    
    private function uploadPhpForm() : bool
    {
        $files = \fpcm\classes\http::getFiles();
        if (!$this->permissionsData['permUpload'] || $files === null) {
            return false;
        }

        $result = (new \fpcm\model\files\fileuploader($files))->processUpload($this->session->getUserId());
            if (count($result['success'])) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_UPLOADPHP', array('{{filenames}}' => implode(', ', $result['success'])));
            }

            if (count($result['error'])) {
                $this->view->addErrorMessage('SAVE_FAILED_UPLOADPHP', array('{{filenames}}' => implode(', ', $result['error'])));
            }

        return true;
        }

    private function deleteFiles() : bool
    {
        $fileNames = $this->getRequestVar('filenames',[
            \fpcm\classes\http::FILTER_BASE64DECODE
        ]);

        if (!$this->permissionsData['permDelete'] || !$this->buttonClicked('deleteFiles') || !is_array($fileNames) || !count($fileNames)) {
            return false;
        }

            $deletedOk = [];
            $deletedFailed = [];
            foreach ($fileNames as $fileName) {

            if ((new \fpcm\model\files\image($fileName, false))->delete()) {
                    $deletedOk[] = $fileName;
                continue;
            }

                    $deletedFailed[] = $fileName;
                }

            if (count($deletedOk)) {
                $this->view->addNoticeMessage('DELETE_SUCCESS_FILES', array('{{filenames}}' => implode(', ', $deletedOk)));
            }

            if (count($deletedFailed)) {
                $this->view->addErrorMessage('DELETE_FAILED_FILES', array('{{filenames}}' => implode(', ', $deletedFailed)));
            }

        return true;
    }

    public function process()
    {
        $hasFiles = ($this->fileList->getDatabaseFileCount() ? true : false);
        
        $this->view->addJsVars([
            'fmgrMode' => $this->mode,
            'jqUploadInit' => $this->config->file_uploader_new ? true : false,
            'loadAjax' => $hasFiles,
            'currentModule' => $this->getRequestVar('module'),
            'filesLastSearch' => 0,
            'checkboxRefresh' => true
        ]);

        $this->view->addJsLangVars(['FILE_LIST_RENAME_NEWNAME', 'SEARCH_WAITMSG', 'ARTICLES_SEARCH',
            'ARTICLE_SEARCH_START', 'FILE_LIST_ADDTOINDEX', 'GLOBAL_PROPERTIES',
            'FILE_LIST_RESOLUTION_PIXEL'
        ]);

        $this->view->assign('searchCombination', array(
            'ARTICLE_SEARCH_LOGICAND' => 0,
            'ARTICLE_SEARCH_LOGICOR' => 1
        ));

        $this->view->assign('mode', $this->mode);
        $this->view->assign('hasFiles', $hasFiles);
        $this->view->assign('newUploader', $this->config->file_uploader_new);
        $this->view->assign('jquploadPath', \fpcm\classes\dirs::getLibUrl('jqupload/'));
        $this->view->addJsFiles(['filemanager.js', 'fileuploader.js']);

        $actionPath = \fpcm\classes\tools::getFullControllerLink('files/list', ['mode' => $this->mode]);
        
        if ($this->config->file_uploader_new) {
            $this->view->assign('actionPath', \fpcm\classes\tools::getFullControllerLink('ajax/jqupload'));
        } else {
            $this->view->assign('actionPath', $actionPath);
            $this->view->assign('maxFilesInfo', $this->language->translate('FILE_LIST_PHPMAXINFO', [
                '{{filecount}}' => ini_get("max_file_uploads"),
                '{{filesize}}' => \fpcm\classes\tools::calcSize(\fpcm\classes\baseconfig::uploadFilesizeLimit(true), 0)
            ]));
        }

        $this->initViewAssigns([], [], \fpcm\classes\tools::calcPagination(1, 1, 0, 0));

        $hiddenClass = ($this->mode === 1 ? '' : ' fpcm-ui-hidden');

        $buttons = [
            (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setText('GLOBAL_SELECTALL')->setIconOnly(true)->setClass($hiddenClass),
            (new \fpcm\view\helper\button('opensearch', 'opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly(true)->setClass('fpcm-ui-maintoolbarbuttons-tab1'.$hiddenClass)
        ];

        if ($this->permissionsData['permThumbs']) {
            $buttons[] = (new \fpcm\view\helper\submitButton('createThumbs', 'createThumbs'))->setText('FILE_LIST_NEWTHUMBS')->setIcon('image', 'far')->setIconOnly(true)->setClass('fpcm-ui-maintoolbarbuttons-tab1'.$hiddenClass);
        }

        if ($this->permissionsData['permDelete']) {
            $buttons[] = (new \fpcm\view\helper\deleteButton('deleteFiles', 'deleteFiles'))->setClass('fpcm-ui-button-confirm fpcm-ui-maintoolbarbuttons-tab1'.$hiddenClass);
        }

        if ($this->permissionsData['permUpload']) {
            $buttons[] = (new \fpcm\view\helper\linkButton('back'))->setUrl($actionPath)->setText('GLOBAL_BACK')->setIcon('chevron-circle-left')->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden');
        }

        if ($this->mode === 1) {
            foreach (\fpcm\components\components::getFilemanagerViews() as $descr => $view) {
                $buttons[] = (new \fpcm\view\helper\radiobutton('listView', 'listView'. ucfirst($view)))->setText($descr)->setClass('fpcm-ui-listeview-setting')->setValue($view)->setSelected($this->config->file_view);
            }
        }

        $this->view->addButtons($buttons);
        $this->view->setFormAction('files/list', ['mode' => $this->mode]);
        $this->view->render();
    }

}

?>
