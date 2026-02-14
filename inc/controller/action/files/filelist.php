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
        \fpcm\controller\traits\theme\viewAjaxDummy;

    /**
     * Dateiliste
     * @var \fpcm\model\files\mediaFilesList
     */
    protected $fileList;

    /**
     * Benutzerliste
     * @var \fpcm\model\users\userList
     */
    protected $userList;

    /**
     *
     * @return bool
     */
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
        $this->fileList = new \fpcm\model\files\mediaFilesList();
        $this->userList = \fpcm\model\users\userList::getInstance();

        $this->mode = $this->request->getIntMode();
        if ($this->mode == self::FILEMANAGER_TYPE_MAIN) {
            return true;
        }

        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
        $this->view->setBodyClass('m-2 fpcm ui-classic-backdrop');
        $this->view->assign('toolbarClass', 'd-none');
        return true;
    }

    /**
     * Controller processing
     * @return void
     */
    public function process()
    {
        $hasFiles = ($this->fileList->getDatabaseFileCount() ? true : false);

        /* @var $uploader \fpcm\components\fileupload\uploader */
        $uploader = \fpcm\components\components::getFileUploader();

        $this->initDialogs();

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
            'FILE_LIST_ALTTEXT', 'FILE_LIST_FILENAME', 'MSG_FILES_CREATETHUMBS',
            'GLOBAL_LASTCHANGE', 'FILE_LIST_UPLOAD_BY', 'FILE_LIST_FILESIZE',
            'FILE_LIST_RESOLUTION', 'FILE_LIST_FILETYPE', 'FILE_LIST_FILEHASH',
            'FILE_LIST_FILECREDITS', 'RENAME_FAILED_FILE', 'HL_OPTIONS',
            'SYSTEM_OPTIONS_FILEMANAGER_VIEWCARDS', 'FILE_LIST_EDIT_FLIP',
            'SYSTEM_OPTIONS_FILEMANAGER_VIEWLIST', 'FILE_LIST_EDIT_DYNAMIC',
            'HL_REMINDER', 'REMINDER_SAVE_FAILED', 'GLOBAL_DELETE',
            'SYSTEM_OPTIONS_FILEMANAGER_VIEWSMALL', 'RENAME_FAILED_FILE',
            'FILE_LIST_MEDIA_TYPE',
            'FILE_LIST_INSERT_FAILED_IMAGE',
            'FILE_LIST_INSERT_FAILED_VIDEO'
        ], $uploader->getJsLangVars()));

        if (!trim($uploader->getTemplate()) || !realpath($uploader->getTemplate())) {
            trigger_error('Undefined file upload template given in '.$uploader->getTemplate());
            $this->execDestruct = false;
            return;
        }

        $jsFiles = ['files/module.js', 'files/search.js', 'ui/dnd.js'];
        $messageMode = in_array(trim($this->config->system_editor, '\\'), [\fpcm\components\editor\tinymceEditor5::class, \fpcm\components\editor\hugerte::class]);

        if ($this->mode == 2 && $messageMode) {
            $jsFiles[] = 'files/editorMessages.js';
        }

        if ($this->mode > 1) {
            $jsFiles[] = 'files/editorInsertActions.js';
        }

        $this->view->addPager((new \fpcm\view\helper\pager('ajax/files/lists&mode='.$this->mode, 1, 1, $this->config->file_list_limit, 1)));
        $this->view->addJsFiles(array_merge( $jsFiles, $uploader->getJsFiles() ));
        $this->view->addJsFilesLate($uploader->getJsFilesLate());
        $this->view->setJsModuleFiles($uploader->getJsModuleFiles() + ['/files/cropper.js']);
        $this->view->setViewVars(array_merge([
            'mode' => $this->mode,
            'hasFiles' => $hasFiles,
            'allowedFileTypes' => \fpcm\model\files\mediaFile::$allowedExts
        ], $uploader->getViewVars() ));

        $this->assignSearchFromVars();
        $this->initViewAssigns([], []);
        $this->initButtons( $uploader->getTemplate() );

        $this->view->setFormAction('files/list', [
            'mode' => $this->mode
        ]);

        $tabs = [
            (new \fpcm\view\helper\tabItem('files-list'))
                ->setText('HL_FILES_MNG')
                ->setData(['ajax-quiet' => true])
                ->setTabToolbar(1)
                ->setUrl(\fpcm\classes\tools::getControllerLink('ajax/files/lists', [
                    'mode' => $this->mode
                ]))
        ];

        $this->view->includeForms('filemanager');

        $this->view->addTabs(
            tabsId: 'files',
            tabs: $tabs,
            active: $this->getActiveTab()
        );

        $this->view->render();
    }

    private function initButtons(string $tpl)
    {
        $buttons = [
            (new \fpcm\view\helper\filesSelectCheckbox('fpcm-select-all'))->setIcon('check-double')->setText('GLOBAL_SELECTALL')->setClass('fpcm-select-all')
        ];

        if ($this->permissions->uploads->add) {

            $this->view->assign('uploadFormPath', $tpl);

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

        if ($this->mode !== self::FILEMANAGER_TYPE_MAIN) {
            return;
        }

        $this->view->addToolbarRight([
            (new \fpcm\view\helper\button('settings'))->setText('HL_OPTIONS')->setIcon('cogs')->setIconOnly()
        ]);

    }

    public function assignSearchFromVars()
    {
        $searchDlg = new \fpcm\view\helper\dialogs\search();
        $searchDlg->setFields([
            'valueFields' => [
                'filename' => (new \fpcm\view\helper\textInput('filename'))
                    ->setText('FILE_LIST_FILENAME')
                    ->setMaxlenght(255)
                    ->setLabelTypeFloat(),
                'alttext' => (new \fpcm\view\helper\textInput('alttext'))
                    ->setText('EDITOR_IMGALTTXT')
                    ->setMaxlenght(255)
                    ->setLabelTypeFloat(),
                'credits' => (new \fpcm\view\helper\textInput('credits'))
                    ->setText('FILE_LIST_FILECREDITS')
                    ->setMaxlenght(255)
                    ->setLabelTypeFloat(),
                'datefrom' => (new \fpcm\view\helper\dateTimeInput('datefrom'))
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setNativeDate()
                    ->setLabelTypeFloat(),
                'dateto' => (new \fpcm\view\helper\dateTimeInput('dateto'))
                    ->setText('ARTICLE_SEARCH_DATE_FROM')
                    ->setNativeDate()
                    ->setLabelTypeFloat(),
                'userid' => (new \fpcm\view\helper\select('userid'))
                    ->setText('ARTICLE_SEARCH_USER')
                    ->setOptions(['GLOBAL_SELECT' => -1] + $this->userList->getUsersNameList())
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat(),
                'mediatype' => (new \fpcm\view\helper\select('mediatype'))
                    ->setText('FILE_LIST_MEDIA_TYPE')
                    ->setOptions($this->language->translate('FILE_LIST_MEDIA_TYPE_LIST'))
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setLabelTypeFloat(),
            ],
            'buildFields' => [
                (new \fpcm\view\helper\button('cremove'))
                    ->setText('GLOBAL_REMOVE')
                    ->setIcon('minus')
                    ->setIconOnly()
                    ->setClass('btn-sm')
                    ->setLabelTypeFloat(),
                (new \fpcm\view\helper\select('combinations'))
                    ->setText('ARTICLE_SEARCH_LOGIC')
                    ->setOptions($searchDlg->getDefaultCombinations())
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected(-1)
                    ->setLabelTypeFloat(),
                (new \fpcm\view\helper\select('fields'))
                    ->setOptions([
                        'FILE_LIST_FILENAME' => 'filename',
                        'EDITOR_IMGALTTXT' => 'alttext',
                        'FILE_LIST_FILECREDITS' => 'credits',
                        'ARTICLE_SEARCH_DATE_FROM' => 'datefrom',
                        'ARTICLE_SEARCH_DATE_TO' => 'dateto',
                        'FILE_LIST_UPLOAD_BY' => 'userid',
                        'FILE_LIST_MEDIA_TYPE' => 'mediatype',
                    ])
                    ->setLabelTypeFloat()
            ],
            'sortFields' => [
                (new \fpcm\view\helper\select('field'))
                    ->setText('GLOBAL_SORT_BY')
                    ->setOptions([
                        'FILE_LIST_FILENAME' => 'filename',
                        'EDITOR_IMGALTTXT' => 'alttext',
                        'GLOBAL_LASTCHANGE' => 'filetime',
                        'FILE_LIST_UPLOAD_BY' => 'userid',
                        'FILE_LIST_MEDIA_TYPE' => 'mediatype',
                    ])
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected('filetime')
                    ->setLabelTypeFloat(),
                (new \fpcm\view\helper\select('order'))
                    ->setText('GLOBAL_SORT_ODER')
                    ->setOptions($this->language->translate('GLOBAL_SORTBY_LIST'))
                    ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                    ->setSelected('desc')
                    ->setLabelTypeFloat(),
            ]
        ]);

        $this->view->addDialogs($searchDlg);

        $this->view->addFromLibrary('sortable_js/', [
            'Sortable.min.js'
        ]);
    }

    private function initDialogs()
    {

        $settingsDlg = (new \fpcm\view\helper\dialog('filesSettings'));
        $settingsDlg->setFields([
            (new \fpcm\view\helper\select('file_list_limit'))
                ->setText('SYSTEM_OPTIONS_FILEMANAGER_LIMIT')
                ->setOptions( \fpcm\model\system\config::getAcpArticleLimits() )
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setSelected($this->config->file_list_limit)
                ->setData([
                    'user_setting' => 'file_list_limit',
                    'index' => 0
                ])
                ->setIcon('folder-open')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            (new \fpcm\view\helper\select('file_view'))
                ->setText('SYSTEM_OPTIONS_FILEMANAGER_VIEW')
                ->setOptions(\fpcm\components\components::getFilemanagerViews())
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setSelected($this->config->file_view)
                ->setData([
                    'user_setting' => 'file_view',
                    'index' => 1
                ])
                ->setIcon('grip-horizontal')
                ->setLabelTypeFloat()
                ->setBottomSpace('')
        ]);

        $reminderDlg = new \fpcm\view\helper\dialogs\reminder();

        $this->view->addDialogs([
            $settingsDlg,
            $reminderDlg
        ]);

    }

}
