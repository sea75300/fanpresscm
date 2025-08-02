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
        $this->fileList = new \fpcm\model\files\imagelist();
        $this->userList = new \fpcm\model\users\userList();

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
            'loaderTpl' => new \fpcm\model\files\jsViewTemplate('fmloader'),
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
            'HL_REMINDER', 'REMINDER_SAVE_FAILED', 'GLOBAL_DELETE'
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
        $this->view->setJsModuleFiles($uploader->getJsModuleFiles() + ['/files/cropper.js']);
        $this->view->setViewVars(array_merge([
            'mode' => $this->mode,
            'hasFiles' => $hasFiles,
        ], $uploader->getViewVars() ));

        $this->assignSearchFromVars();
        $this->initViewAssigns([], []);
        $this->initButtons( $uploader->getTemplate() );

        $this->view->setFormAction('files/list', ['mode' => $this->mode]);

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

    private function initButtons(string $tpl)
    {
        $buttons = [
            new \fpcm\view\helper\filesSelectAllCheckbox('fpcm-select-all')
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

        if ($this->mode !== 1) {
            return;
        }

        $this->view->addToolbarRight([
            (new \fpcm\view\helper\button('settings'))->setText('HL_OPTIONS')->setIcon('cogs')->setIconOnly()
        ]);

    }

    public function assignSearchFromVars()
    {

        $fields = [
            'filename' => [
                'call' => 'input',
                'class' => 'fpcm-files-search-input',
                'maxlenght' => 255,
                'placeholder' => 'FILE_LIST_SEARCHTEXT',
                'label' => 'FILE_LIST_SEARCHTEXT',
                'autofocus' => true,
                'noCombination' => true
            ],
            'datefrom' => [
                'call' => 'input',
                'type' => 'date',
                'class' => 'fpcm-files-search-input',
                'label' => 'ARTICLE_SEARCH_DATE_FROM',
            ],
            'dateto' => [
                'call' => 'input',
                'type' => 'date',
                'class' => 'fpcm-files-search-input',
                'label' => 'ARTICLE_SEARCH_DATE_TO',
            ],
            'userid' => [
                'call' => 'select',
                'class' => 'fpcm-files-search-input',
                'options' => ['GLOBAL_SELECT' => -1] + $this->userList->getUsersNameList(),
                'label' => 'ARTICLE_SEARCH_USER',
                'firstOption' => \fpcm\view\helper\select::FIRST_OPTION_DISABLED
            ]
        ];

        $combinations = [
            'default' => [
                'ARTICLE_SEARCH_LOGICNONE' => -1,
                'ARTICLE_SEARCH_LOGICAND' => 0,
                'ARTICLE_SEARCH_LOGICOR' => 1,
            ]
        ];

        $this->view->addSearchForm($fields, $combinations);

        $this->view->addJsLangVars([
            'SEARCH_WAITMSG', 'ARTICLES_SEARCH', 'ARTICLE_SEARCH_START',
            'ARTICLE_SEARCH_USER', 'ARTICLE_SEARCH_DATE_TO', 'ARTICLE_SEARCH_DATE_FROM',
            'FILE_LIST_SEARCHTEXT', 'ARTICLE_SEARCH_LOGICNONE', 'ARTICLE_SEARCH_LOGICAND',
            'ARTICLE_SEARCH_LOGICOR', 'ARTICLE_SEARCH_LOGIC', 'GLOBAL_SELECT'
        ]);
    }

    private function initDialogs()
    {

        $settingsDlg = (new \fpcm\view\helper\dialog('filesSettings'));
        $settingsDlg->setFields([
            (new \fpcm\view\helper\select('file_list_limit'))
                ->setText('SYSTEM_OPTIONS_ACPARTICLES_LIMIT')
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

        $reminderDlg = new \fpcm\view\helper\dialog('reminders');
        $reminderDlg->setFields([
            (new \fpcm\view\helper\select('user-id'))
                ->setText('LOGS_LIST_USER')
                ->setFirstOption(\fpcm\view\helper\select::FIRST_OPTION_DISABLED)
                ->setOptions($this->userList->getUsersNameList() )
                ->setIcon('user')
                ->setLabelTypeFloat()
                ->setBottomSpace(''),
            [
                (new \fpcm\view\helper\dateTimeInput('resub-date'))
                    ->setText('EDITOR_POSTPONED_DATE')
                    ->setIcon('calendar')
                    ->setLabelTypeFloat()
                    ->setBottomSpace(''),
                (new \fpcm\view\helper\dateTimeInput('resub-time'))
                    ->setText('EDITOR_POSTPONED_DATETIME')
                    ->setNativeTime()
                    ->setLabelTypeFloat()
                    ->setBottomSpace('')
            ],
            (new \fpcm\view\helper\textInput('resub-comment'))
                ->setText('COMMMENT_TEXT')
                ->setLabelTypeFloat()
                ->setBottomSpace('')
        ]);

        $this->view->addDialogs([
            $settingsDlg,
            $reminderDlg
        ]);

    }

}
