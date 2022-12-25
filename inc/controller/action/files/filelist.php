<?php

/**
 * File manager controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\files;

class filelist extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\files\lists,
        \fpcm\controller\traits\common\searchParams,
        \fpcm\controller\traits\theme\viewAjaxDummy;

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
        $this->view->setBodyClass('m-2 fpcm ui-classic-backdrop');
        $this->view->assign('toolbarClass', 'd-none');
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
            'uploadDest' => 'default',
            'thumbsize' => $this->config->file_thumb_size . 'px',
            'loaderTpl' => new \fpcm\model\files\jsViewTemplate('fmloader')
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
            'FILE_LIST_ALTTEXT', 'FILE_LIST_FILENAME', 'MSG_FILES_CREATETHUMBS'
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
        
        $this->view->addPager((new \fpcm\view\helper\pager('ajax/files/lists&mode='.$this->mode, 1, 1, $this->config->file_list_limit, 1)));
        $this->view->addJsFiles(array_merge( $jsFiles, $uploader->getJsFiles() ));
        $this->view->addJsFilesLate($uploader->getJsFilesLate());
        $this->view->setViewVars(array_merge([
            'searchUsers' =>  ['' => -1] + (new \fpcm\model\users\userList)->getUsersNameList(),
            'mode' => $this->mode,
            'hasFiles' => $hasFiles,
        ], $uploader->getViewVars() ));

        $this->assignSearchFromVars();
        $this->initViewAssigns([], []);

        $buttons = [
            new \fpcm\view\helper\wrapper('div', 'btn btn-light', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setText('GLOBAL_SELECTALL')->setIconOnly()->setClass('fpcm-select-all') ),
        ];

        if ($this->permissions->uploads->add) {

            $this->view->assign('uploadFormPath', $uploader->getTemplate());

            $buttons[] =  (new \fpcm\view\helper\button('fileUpload'))
                ->setText('FILE_LIST_UPLOADFORM')
                ->setIcon('upload')
                ->setData([
                    'bs-toggle' => 'offcanvas',
                    'bs-target' => '#offcanvasUpload'
                ])
                ->setAria([
                    'bs-controls' => 'offcanvasUpload',
                ])
                ->setPrimary();

        }
        
        $buttons[] = (new \fpcm\view\helper\button('openSearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly();

        if ($this->mode === 2) {
            $buttons[] = (new \fpcm\view\helper\submitButton('insertGallery'))->setText('FILE_LIST_INSERTGALLERY')->setIcon('images', 'far')->setIconOnly();
        }

        if ($this->permissions->uploads->thumbs) {
            $buttons[] = (new \fpcm\view\helper\submitButton('createThumbs'))
                    ->setText('FILE_LIST_NEWTHUMBS')
                    ->setIcon('image', 'far')
                    ->setIconOnly();
        }

        if ($this->permissions->uploads->delete) {
            $buttons[] = (new \fpcm\view\helper\deleteButton('deleteFiles'));
        }

        $this->view->addButtons($buttons);
        $this->view->setFormAction('files/list', ['mode' => $this->mode]);
        

        if ($this->mode === 1) {
            $this->view->addToolbarRight((string) (new \fpcm\view\helper\select('listView'))
                    ->setOptions(\fpcm\components\components::getFilemanagerViews())
                    ->setClass('fpcm-ui-listeview-setting')
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected($this->config->file_view) );
        }

        $tabs = [
            (new \fpcm\view\helper\tabItem('files-list'))
                ->setText('HL_FILES_MNG')
                ->setData(['ajax-quiet' => true])
                ->setTabToolbar(1)
                ->setUrl(\fpcm\classes\tools::getControllerLink('ajax/files/lists', [ 'mode' => $this->mode ]) )
        ];

        $this->view->includeForms('filemanager');
        $this->view->addTabs('files', $tabs);
        $this->view->render();
    }

}
