<?php

/**
 * File manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\files;

class filelist extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\files\lists,
        \fpcm\controller\traits\common\searchParams;

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

    public function isAccessible(): bool
    {
        return $this->permissions->uploads->visible;
    }

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
        $this->fileList = new \fpcm\model\files\imagelist();
        $this->mode = $this->request->getIntMode();
        if ($this->mode == 1) {
            return true;
        }

        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->setBodyClass('fpcm-ui-hide-toolbar');
        return true;
    }

    public function process()
    {
        $hasFiles = ($this->fileList->getDatabaseFileCount() ? true : false);

        /* @var $uploader \fpcm\components\fileupload\jqupload */
        $uploader = \fpcm\components\components::getFileUploader();
        
        $this->view->addCssFiles($uploader->getCssFiles());
        $this->view->addJsVars(array_merge([
            'fmgrMode' => $this->mode,
            'loadAjax' => $hasFiles,
            'currentModule' => $this->request->getModule(),
            'filesLastSearch' => 0,
            'checkboxRefresh' => true,
            'uploadDest' => 'default'
        ], $uploader->getJsVars() ));

        $this->view->addJsLangVars(array_merge([
            'FILE_LIST_RENAME_NEWNAME', 'FILE_LIST_ADDTOINDEX',
            'GLOBAL_PROPERTIES', 'FILE_LIST_RESOLUTION_PIXEL',
            'FILE_LIST_EDIT', 'FILE_LIST_EDIT_CROP',
            'FILE_LIST_EDIT_MOVE', 'FILE_LIST_EDIT_ROTATE_ANTICLOCKWISE',
            'FILE_LIST_EDIT_ROTATE_CLOCKWISE', 'FILE_LIST_EDIT_ZOOMIN',
            'FILE_LIST_EDIT_ZOOMOUT', 'FILE_LIST_EDIT_RESIZE', 'GLOBAL_RESET',
            'SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEHEIGHT',
            'SYSTEM_OPTIONS_NEWSSHOWMAXIMGSIZEWIDTH',
            'FILE_LIST_EDIT_RESIZE_NOTICE', 'FILE_LIST_ALTTEXT',
            'FILE_LIST_ALTTEXT', 'FILE_LIST_FILENAME'
        ], $uploader->getJsLangVars()));

        if (!trim($uploader->getTemplate()) || !realpath($uploader->getTemplate())) {
            trigger_error('Undefined file upload template given in '.$uploader->getTemplate());
            $this->execDestruct = false;
            return false;
        }

        $jsFiles = ['files/module.js'];
        if ($this->mode == 2 && $this->config->system_editor === '\fpcm\components\editor\tinymceEditor5') {
            $jsFiles[] = 'files/tinymce5Messages.js';
        }
        
        $this->view->addJsFiles(array_merge( $jsFiles, $uploader->getJsFiles() ));
        $this->view->addJsFilesLate($uploader->getJsFilesLate());
        $this->view->setViewVars(array_merge([
            'searchUsers' => ['ARTICLE_SEARCH_USER' => -1] + (new \fpcm\model\users\userList)->getUsersNameList(),
            'mode' => $this->mode,
            'hasFiles' => $hasFiles,
        ], $uploader->getViewVars() ));

        $this->assignSearchFromVars();
        $this->initViewAssigns([], [], \fpcm\classes\tools::calcPagination(1, 1, 0, 0));

        $buttons = [
            (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setText('GLOBAL_SELECTALL')->setIconOnly(true)->setWrapperClass('fpcm-ui-maintoolbarbuttons-tab1'),
            (new \fpcm\view\helper\button('opensearch', 'opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly(true)->setClass('fpcm-ui-maintoolbarbuttons-tab1')
        ];

        if ($this->mode === 2) {
            $buttons[] = (new \fpcm\view\helper\submitButton('insertGallery', 'insertGallery'))->setText('FILE_LIST_INSERTGALLERY')->setIcon('images', 'far')->setIconOnly(true)->setClass('fpcm-ui-maintoolbarbuttons-tab1');
        }

        if ($this->permissions->uploads->thumbs) {
            $buttons[] = (new \fpcm\view\helper\submitButton('createThumbs', 'createThumbs'))->setText('FILE_LIST_NEWTHUMBS')->setIcon('image', 'far')->setIconOnly(true)->setClass('fpcm-ui-maintoolbarbuttons-tab1');
        }

        if ($this->permissions->uploads->delete) {
            $buttons[] = (new \fpcm\view\helper\deleteButton('deleteFiles', 'deleteFiles'))->setClass('fpcm-ui-maintoolbarbuttons-tab1');
        }

        if ($this->mode === 1) {
            $buttons[] = (new \fpcm\view\helper\select('listView'))
                    ->setOptions(\fpcm\components\components::getFilemanagerViews())
                    ->setClass('fpcm-ui-listeview-setting')
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected($this->config->file_view)
                    ->setClass('fpcm-ui-maintoolbarbuttons-tab1');
        }

        if ($this->permissions->uploads->add) {
            $buttons[] = (new \fpcm\view\helper\button('fmgrUploadBack'))->setText('GLOBAL_BACK')->setIcon('chevron-circle-left')->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-hidden');
        }

        $this->view->addButtons($buttons);
        $this->view->setFormAction('files/list', ['mode' => $this->mode]);
        
        $tabs = [
            (new \fpcm\view\helper\tabItem('files-list'))
                ->setText('FILE_LIST_AVAILABLE')
                ->setTabToolbar(1)
                ->setUrl(\fpcm\classes\tools::getControllerLink('ajax/files/lists', [ 'mode' => $this->mode ]) )
        ];
        
        if ($this->permissions->uploads->add) {
            
            $path = str_replace(\fpcm\classes\dirs::getCoreDirPath(\fpcm\classes\dirs::CORE_VIEWS), '', $uploader->getTemplate());
            
            $tabs[] = (new \fpcm\view\helper\tabItem('upload'))
                    ->setText('FILE_LIST_UPLOADFORM')
                    ->setTabToolbar(2)
                    ->setFile($path);
            
        }

        $this->view->includeForms('filemanager');
        $this->view->addTabs('files', $tabs);
        $this->view->render();
    }

}
