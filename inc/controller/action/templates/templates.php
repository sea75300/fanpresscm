<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\templates;

/**
 * Template controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class templates extends \fpcm\controller\abstracts\controller implements \fpcm\controller\interfaces\requestFunctions
{

    use \fpcm\controller\traits\templates\edit;

    /**
     * 
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'templates/overview';
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'HL_OPTIONS_TEMPLATES';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $editor = new \fpcm\components\editor\htmlEditor();
        $this->view->addCssFiles($editor->getCssFiles());

        $jsFiles = $editor->getJsFiles();
        unset($jsFiles[16]);  

        $this->view->addJsFiles($jsFiles);
        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {

        /* @var $uploader \fpcm\components\fileupload\uploader */
        $uploader = \fpcm\components\components::getFileUploader();

        $this->view->addJsVars(array_merge([
            'templateId' => 1,
            'uploadDest' => 'drafts'
        ], $uploader->getJsVars() ));

        $this->view->addCssFiles($uploader->getCssFiles());
        $this->view->addJsLangVars(array_merge(['HL_TEMPLATE_PREVIEW', 'TEMPLATE_HL_DRAFTS_EDIT'], $uploader->getJsLangVars()));
        $this->view->addJsFiles(array_merge(['templates/module.js'], $uploader->getJsFiles() ));
        $this->view->addJsFilesLate($uploader->getJsFilesLate());
        $this->view->setJsModuleFiles($uploader->getJsModuleFiles());

        if (!trim($uploader->getTemplate()) || !realpath($uploader->getTemplate())) {
            trigger_error('Undefined file upload template given in '.$uploader->getTemplate());
            $this->execDestruct = false;
            return false;
        }

        $this->view->setViewVars( $uploader->getViewVars() );

        $this->initDataView();

        $this->view->setFormAction('templates/templates');
        
        $hiddenClass1 = in_array($this->getActiveTab(), [6,7]) ? 'fpcm-ui-hidden' : '';
        $hiddenClass2 = $this->getActiveTab() != 7 ? 'fpcm-ui-hidden' : '';

        $buttons = [
            (new \fpcm\view\helper\saveButton('saveTemplates'))->setClass( $this->getToolbarButtonToggleClass(1, '', true) )->setPrimary(),
            (new \fpcm\view\helper\button('showpreview'))->setText('GLOBAL_PREVIEW')->setIcon('eye')->setClass( $this->getToolbarButtonToggleClass(1, '', true) )
        ];
        
        if ($this->permissions->system->drafts) {
            
            
            $buttons[] =  (new \fpcm\view\helper\deleteButton('fileDelete'))
                ->setClass('fpcm-ui-maintoolbarbuttons-tab3 ' . $hiddenClass2 )->setClickConfirm();
            
            $buttons[] =  (new \fpcm\view\helper\button('fileUpload'))
                ->setText('FILE_LIST_UPLOADFORM')
                ->setClass('fpcm-ui-maintoolbarbuttons-tab3 ' . $hiddenClass2 )
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
        
        $this->view->addButtons($buttons);        
        
        $this->initTabs();

        $this->view->render();
    }

    /**
     * 
     * @return boolean
     */
    private function initTabs()
    {
        $tabs = [
            (new \fpcm\view\helper\tabItem('tpl-article'))
                ->setText('TEMPLATE_HL_ARTICLES')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\article::TEMPLATE_ID
                ]))
                ->setData(['tplId' => \fpcm\model\pubtemplates\article::TEMPLATE_ID])
                ->setTabToolbar(1)
                ->setDataViewId(''),

        ];
        
        if ($this->config->articles_template_active != $this->config->article_template_active) {
            $tabs[] = (new \fpcm\view\helper\tabItem('tpl-articleSingle'))
                ->setText('TEMPLATE_HL_ARTICLE_SINGLE')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\article::TEMPLATE_ID_SINGLE
                ]))
                ->setData(['tplId' => \fpcm\model\pubtemplates\article::TEMPLATE_ID_SINGLE])
                ->setTabToolbar(1)
                ->setDataViewId('');
        }
        
        $tabs = array_merge($tabs, [
            (new \fpcm\view\helper\tabItem('tpl-comment'))
                ->setText('TEMPLATE_HL_COMMENTS')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [''
                    . 'tpl' => \fpcm\model\pubtemplates\comment::TEMPLATE_ID
                ]))
                ->setData(['tplId' => \fpcm\model\pubtemplates\comment::TEMPLATE_ID])
                ->setTabToolbar(1)
                ->setDataViewId(''),

            (new \fpcm\view\helper\tabItem('tpl-commentForm'))
                ->setText('TEMPLATE_HL_COMMENTFORM')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\commentform::TEMPLATE_ID
                ]))
                ->setData(['tplId' => \fpcm\model\pubtemplates\commentform::TEMPLATE_ID])
                ->setTabToolbar(1)
                ->setDataViewId(''),

            (new \fpcm\view\helper\tabItem('tpl-shareButtons'))
                ->setText('TEMPLATE_HL_SHAREBUTTONS')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\sharebuttons::TEMPLATE_ID
                ]))
                ->setData(['tplId' => \fpcm\model\pubtemplates\sharebuttons::TEMPLATE_ID])
                ->setTabToolbar(1)
                ->setDataViewId(''),

            (new \fpcm\view\helper\tabItem('tpl-latestNews'))
                ->setText('TEMPLATE_HL_LATESTNEWS')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\latestnews::TEMPLATE_ID
                ]))
                ->setData(['tplId' => \fpcm\model\pubtemplates\latestnews::TEMPLATE_ID])
                ->setTabToolbar(1)
                ->setDataViewId(''),

            (new \fpcm\view\helper\tabItem('tpl-tweet'))
                ->setText('TEMPLATE_HL_TWEET')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\tweet::TEMPLATE_ID
                ]))
                ->setData(['tplId' => \fpcm\model\pubtemplates\tweet::TEMPLATE_ID])
                ->setTabToolbar(1)
                ->setDataViewId(''),
        ]);
        
        if ($this->permissions->system->drafts) {
            $tabs[] = (new \fpcm\view\helper\tabItem('tpl-editor-templates'))
                    ->setText('TEMPLATE_HL_DRAFTS')
                    ->setFile( $this->getViewPath() )
                    ->setData(['noEmpty' => true])
                    ->setTabToolbar(3)
                    ->setDataViewId('draftfiles');
        }

        $this->view->addTabs('fpcm-tabs-templates', $tabs, '', $this->getActiveTab());
        return true;
    }


    /**
     * 
     * @return bool
     */
    private function initDataView()
    {
        $tplfilelist = new \fpcm\model\files\templatefilelist();

        $dataView = new \fpcm\components\dataView\dataView('draftfiles');
        $dataView->addColumns([
            (new \fpcm\components\dataView\column('select', '', 'mx-3'))->setSize('auto')->setAlign('center'),
            (new \fpcm\components\dataView\column('button', '', 'mx-3'))->setSize('auto')->setAlign('center'),
            (new \fpcm\components\dataView\column('filename', 'FILE_LIST_FILENAME'))->setSize(8),
            (new \fpcm\components\dataView\column('filesize', 'FILE_LIST_FILESIZE'))->setSize(2)
        ]);
        
        $items = $tplfilelist->getFolderObjectList();
        if (!count($items)) {

            $dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol(
                        'col',
                        (new \fpcm\view\helper\icon('list-ul '))->setSize('lg')->setStack(true)->setStack('ban fpcm-ui-important-text')->setStackTop(true).' '.
                        $this->language->translate('GLOBAL_NOTFOUND2'),
                        '',
                        \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT
                    ),
                ],
                '',
                false,
                true
            ));
            
            $this->view->addDataView($dataView);
            return true;
        }

        /* @var $templateFile \fpcm\model\files\tempfile */
        foreach ($items as $templateFile) {

            $buttons = [
                '<div>',
                (new \fpcm\view\helper\linkButton(uniqid()))->setText('GLOBAL_DOWNLOAD')->setUrl($templateFile->getFileUrl())->setIcon('download')->setIconOnly()->setTarget(\fpcm\view\helper\linkButton::TARGET_NEW),
                (new \fpcm\view\helper\editButton(uniqid()))->setUrlbyObject($templateFile)->setClass('fpcm-articletemplates-edit'),
                '</div>'
            ];

            $dataView->addRow(new \fpcm\components\dataView\row([
                new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('deltplfiles[]', 'chbx' . md5($templateFile->getFilename()) ))->setClass('fpcm-ui-list-checkbox')->setValue(base64_encode($templateFile->getFilename())), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                new \fpcm\components\dataView\rowCol('button', implode('', $buttons) ),
                new \fpcm\components\dataView\rowCol('filename', new \fpcm\view\helper\escape($templateFile->getFilename()) ),
                new \fpcm\components\dataView\rowCol('filesize', \fpcm\classes\tools::calcSize($templateFile->getFilesize()) )
            ]));
        }
        
        $this->view->addDataView($dataView);
    }

    /**
     * 
     * @return bool
     */
    protected function onFileDelete()
    {
        if (!$this->permissions->system->drafts) {
            return false;
        }
        
        $delFiles = $this->request->fromPOST('deltplfiles', [
            \fpcm\model\http\request::FILTER_BASE64DECODE
        ]);

        if (!is_array($delFiles) || !count($delFiles)) {
            return false;
        }

        $deletedOk = [];
        $deletedFailed = [];

        foreach ($delFiles as $delFile) {

            $articleTplFile = new \fpcm\model\files\templatefile($delFile);
            if (!$articleTplFile->delete()) {
                $deletedFailed[] = \fpcm\model\files\ops::removeBaseDir($delFile);
                continue;
            }

            $deletedOk[] = \fpcm\model\files\ops::removeBaseDir($delFile);
        }

        if (count($deletedOk)) {
            $this->view->addNoticeMessage('DELETE_SUCCESS_FILES', [
                '{{filenames}}' => implode(', ', $deletedOk)
            ]);
        }

        if (count($deletedFailed)) {
            $this->view->addErrorMessage('DELETE_FAILED_FILES', [
                '{{filenames}}' => implode(', ', $deletedFailed)
            ]);
        }

        return true;
    }

}
