<?php

/**
 * Template controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\templates;

class templates extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\templates\edit;

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'templates'];
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath()
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
     * @return boolean
     */
    public function request()
    {
        $editor = new \fpcm\components\editor\htmlEditor();
        $this->view->addCssFiles($editor->getCssFiles());

        $this->uploadEditorTemplate();
        $this->deleteEditorTemplate();
        $this->save();

        $jsFiles = $editor->getJsFiles();
        $this->view->addJsFiles($jsFiles);
        return true;
    }

    /**
     * Controller-Processing
     * @return boolean
     */
    public function process()
    {
        $tabs = [
            (new \fpcm\view\helper\tabItem('tpl-article'))
                ->setText('TEMPLATE_HL_ARTICLES')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\article::TEMPLATE_ID
                ]))
                ->setData(['toolbar-buttons' => 1, 'tplId' => \fpcm\model\pubtemplates\article::TEMPLATE_ID])
                ->setDataViewId('')
                ->setWrapper(false),

        ];
        
        if ($this->config->articles_template_active != $this->config->article_template_active) {
            $tabs[] = (new \fpcm\view\helper\tabItem('tpl-articleSingle'))
                ->setText('TEMPLATE_HL_ARTICLE_SINGLE')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\article::TEMPLATE_ID_SINGLE
                ]))
                ->setData(['toolbar-buttons' => 1, 'tplId' => \fpcm\model\pubtemplates\article::TEMPLATE_ID_SINGLE])
                ->setDataViewId('')
                ->setWrapper(false);
        }
        
        $this->view->assign('tabs', array_merge($tabs, [
            (new \fpcm\view\helper\tabItem('tpl-comment'))
                ->setText('TEMPLATE_HL_COMMENTS')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [''
                    . 'tpl' => \fpcm\model\pubtemplates\comment::TEMPLATE_ID
                ]))
                ->setData(['toolbar-buttons' => 1, 'tplId' => \fpcm\model\pubtemplates\comment::TEMPLATE_ID])
                ->setDataViewId('')
                ->setWrapper(false),

            (new \fpcm\view\helper\tabItem('tpl-commentForm'))
                ->setText('TEMPLATE_HL_COMMENTFORM')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\commentform::TEMPLATE_ID
                ]))
                ->setData(['toolbar-buttons' => 1, 'tplId' => \fpcm\model\pubtemplates\commentform::TEMPLATE_ID])
                ->setDataViewId('')
                ->setWrapper(false),

            (new \fpcm\view\helper\tabItem('tpl-latestNews'))
                ->setText('TEMPLATE_HL_LATESTNEWS')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\latestnews::TEMPLATE_ID
                ]))
                ->setData(['toolbar-buttons' => 1, 'tplId' => \fpcm\model\pubtemplates\latestnews::TEMPLATE_ID])
                ->setDataViewId('')
                ->setWrapper(false),

            (new \fpcm\view\helper\tabItem('tpl-tweet'))
                ->setText('TEMPLATE_HL_TWEET')
                ->setUrl(\fpcm\classes\tools::getFullControllerLink('ajax/templates/fetch', [
                    'tpl' => \fpcm\model\pubtemplates\tweet::TEMPLATE_ID
                ]))
                ->setData(['toolbar-buttons' => 2, 'tplId' => \fpcm\model\pubtemplates\tweet::TEMPLATE_ID])
                ->setDataViewId('')
                ->setWrapper(false),

            (new \fpcm\view\helper\tabItem('tpl-editor-templates'))
                ->setText('TEMPLATE_HL_DRAFTS')
                ->setUrl('#tab-article-editor-templates')
                ->setData(['toolbar-buttons' => 3, 'noEmpty' => true])
                ->setDataViewId('')
                ->setWrapper(false),
        ]));

        $this->view->addJsVars(['templateId' => 1, 'jqUploadInit' => 0]);
        $this->view->addJsLangVars(['HL_TEMPLATE_PREVIEW', 'TEMPLATE_HL_DRAFTS_EDIT']);
        $this->view->addJsFiles(['fileuploader.js', 'templates.js']);
        $this->initDataView();

        $this->view->assign('maxFilesInfo', $this->lang->translate('FILE_LIST_PHPMAXINFO', [
            '{{filecount}}' => 1,
            '{{filesize}}' => \fpcm\classes\tools::calcSize(\fpcm\classes\baseconfig::uploadFilesizeLimit(true), 0)
        ]));

        $this->view->setFormAction('templates/templates');
        $this->view->addButtons([
            (new \fpcm\view\helper\button('showpreview', 'showpreview'))->setText('GLOBAL_PREVIEW')->setIcon('eye')->setClass('fpcm-ui-maintoolbarbuttons-tab1'),
            (new \fpcm\view\helper\saveButton('saveTemplates', 'save1'))->setClass('fpcm-ui-maintoolbarbuttons-tab1 fpcm-ui-button-confirm'),
            (new \fpcm\view\helper\saveButton('saveTemplates', 'save2'))->setClass('fpcm-ui-maintoolbarbuttons-tab2 fpcm-ui-button-confirm fpcm-ui-hidden'),
            (new \fpcm\view\helper\deleteButton('fileDelete'))->setClass('fpcm-ui-maintoolbarbuttons-tab3 fpcm-ui-button-confirm fpcm-ui-hidden')
        ]);

        $this->view->render();
    }

    /**
     * 
     * @return boolean
     */
    private function initDataView()
    {
        $tplfilelist = new \fpcm\model\files\templatefilelist();

        $dataView = new \fpcm\components\dataView\dataView('draftfiles');
        $dataView->addColumns([
            (new \fpcm\components\dataView\column('select', ''))->setSize('05')->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(2)->setAlign('center'),
            new \fpcm\components\dataView\column('filename', 'FILE_LIST_FILENAME'),
            (new \fpcm\components\dataView\column('filesize', 'FILE_LIST_FILESIZE'))->setSize(2)
        ]);
        
        $items = $tplfilelist->getFolderObjectList();
        if (!count($items)) {

            $dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('col', 'GLOBAL_NOTFOUND2', 'fpcm-ui-padding-md-lr'),
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
                '<div class="fpcm-ui-controlgroup">',
                (new \fpcm\view\helper\linkButton(uniqid()))->setText('GLOBAL_DOWNLOAD')->setUrl($templateFile->getFileUrl())->setIcon('download')->setIconOnly(true)->setTarget('_blank'),
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
     * @return boolean
     */
    private function uploadEditorTemplate()
    {
        $files = \fpcm\classes\http::getFiles();
        if (!$this->buttonClicked('uploadFile') || !$files) {
            return false;
        }

        if (!(new \fpcm\model\files\fileuploader($files))->processArticleTemplateUpload()) {
            $this->view->addErrorMessage('SAVE_FAILED_UPLOADTPLFILE');
            return false;
        }

        $this->view->addNoticeMessage('SAVE_SUCCESS_UPLOADTPLFILE');
        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function deleteEditorTemplate()
    {
        $delFiles = $this->getRequestVar('deltplfiles', [
            \fpcm\classes\http::FILTER_BASE64DECODE
        ]);

        if (!$this->buttonClicked('fileDelete') || !is_array($delFiles) || !count($delFiles)) {
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

    /**
     * 
     * @return boolean
     */
    private function save()
    {
        $tplData = $this->getRequestVar('template', [
            \fpcm\classes\http::FILTER_TRIM,
            \fpcm\classes\http::FILTER_STRIPSLASHES
        ]);

        if (!$this->buttonClicked('saveTemplates') || $tplData === null) {
            return true;
        }

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }
        
        $fn = 'get'. ucfirst($tplData['id']).'Template';
        if (!method_exists($this, $fn)) {
            return false;
        }

        if (!call_user_func([$this, $fn]) ||
            !$this->prefix || !is_object($this->template) ||
            !$this->template instanceof \fpcm\model\pubtemplates\template) {
            return false;
        }
        
        $this->template->setContent($tplData['content']);
        $res = $this->template->save();

        $isCommentForm = $tplData['id'] == \fpcm\model\pubtemplates\commentform::TEMPLATE_ID ? true : false;
        if ($res === \fpcm\model\pubtemplates\commentform::SAVE_ERROR_FORMURL && $isCommentForm) {
            $this->view->addErrorMessage('SAVE_FAILED_TEMPLATE_CF_URLMISSING');
            return false;
        }
        elseif ($this->config->comments_privacy_optin && $res === \fpcm\model\pubtemplates\commentform::SAVE_ERROR_PRIVACY && $isCommentForm) {
            $this->view->addErrorMessage('SAVE_FAILED_TEMPLATE_CF_PRIVACYMISSING');
            return false;
        }

        if (!$res) {
            $this->view->addErrorMessage('SAVE_FAILED_TEMPLATE', [ '{{filename}}' => $this->template->getFilename() ]);
            return false;
        }

        $this->view->addNoticeMessage('SAVE_SUCCESS_TEMPLATE', [ '{{filename}}' => $this->template->getFilename() ]);
        return true;
    }
}

?>
