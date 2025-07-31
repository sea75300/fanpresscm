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

class articleTemplateEditor
extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\requestFunctions
{

    /**
     *
     * @var \fpcm\model\files\templatefile
     */
    protected $file;

    /**
     *
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->system->templates && $this->permissions->system->drafts;
    }

    /**
     *
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'templates/articeltpleditor';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        if (!$this->request->fromGET('file')) {
            return false;
        }

        $this->file = new \fpcm\model\files\templatefile(
            $this->request->fromGET('file', [
                \fpcm\model\http\request::FILTER_URLDECODE,
                \fpcm\model\http\request::FILTER_DECRYPT
            ])
        );

        $this->file->loadContent();

        if (!$this->file->isWritable()) {
            $this->view->addErrorMessage('FILE_NOT_WRITABLE');
        }

        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);

        $editor = new \fpcm\components\editor\aceEditor();

        $viewVars = $editor->getViewVars();
        $viewVars->prepareDrafts();
        
        $this->view->setViewVars($viewVars->toArray());
        $this->view->addCssFiles($editor->getCssFiles());
        $this->view->addJsVars(array_merge(
            [
                'filemanagerUrl' => \fpcm\classes\tools::getFullControllerLink('files/list', ['mode' => '']),
                'filemanagerMode' => 2,
                'filemanagerPermissions' => $this->permissions->uploads
            ],
            $editor->getJsVars()
        ));
        $this->view->addJsLangVars(array_merge(
            [
                'HL_FILES_MNG', 'ARTICLES_SEARCH', 'FILE_LIST_NEWTHUMBS', 'GLOBAL_DELETE',
                'EDITOR_CATEGORIES_SEARCH', 'FILE_LIST_UPLOADFORM'
            ],
            $editor->getJsLangVars()
        ));
        $this->view->addDialogs($editor->getDialogs());

        $jsFiles = ['templates/articles.js'] + $editor->getJsFiles();

        $this->view->addJsFiles($jsFiles);
        $this->view->setFormAction($this->file->getEditLink(),[], true);
        $this->view->assign('file', $this->file);

        $this->view->addTabs('atedit', [
            (new \fpcm\view\helper\tabItem('art-editor'))
                ->setText($this->file->getFilename())
                ->setFile($this->getViewPath())
        ]);
        
        $this->view->setBodyClass('m-2');
        
        $this->view->render();
    }

    public function onSaveTemplate()
    {
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $newCode = $this->request->fromPOST('templatecode', [
            \fpcm\model\http\request::FILTER_TRIM,
            \fpcm\model\http\request::FILTER_STRIPSLASHES
        ]);

        if (!$newCode) {
            $this->view->addErrorMessage('SAVE_FAILED_ARTICLETEMPLATE');
            return false;
        }

        $this->file->setContent($newCode);
        if ($this->file->save() === true) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLETEMPLATE');
            return true;
        }

        $this->view->addErrorMessage('SAVE_FAILED_ARTICLETEMPLATE');
        return false;
    }

}
