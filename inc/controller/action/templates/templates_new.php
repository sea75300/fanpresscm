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
class templates_new
extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\requestFunctions
{
    //use \fpcm\controller\traits\templates\edit;

    private \fpcm\components\editor\aceEditor $editor;

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
        $this->editor = new \fpcm\components\editor\aceEditor();
        return true;
    }

    /**
     * Controller-Processing
     * @return void
     */
    public function process()
    {
        /* @var $uploader \fpcm\components\fileupload\uploader */
        $uploader = \fpcm\components\components::getFileUploader();

        $this->view->addJsVars(array_merge([
            'templateId' => 1,
            'uploadDest' => 'drafts',
            'filemanagerUrl' => \fpcm\classes\tools::getFullControllerLink('files/list', ['mode' => '']),
            'filemanagerMode' => 2,
            'filemanagerPermissions' => $this->permissions->uploads,
        ], /*$uploader->getJsVars(), */ $this->editor->getJsVars() ));

        //$this->view->addCssFiles($uploader->getCssFiles());
        $this->view->addJsLangVars(array_merge(
            [
                'HL_FILES_MNG', 'ARTICLES_SEARCH', 'FILE_LIST_NEWTHUMBS', 
                'GLOBAL_DELETE', 'EDITOR_CATEGORIES_SEARCH', 
                'FILE_LIST_UPLOADFORM', 'TEMPLATE_HL_DRAFTS_EDIT',
                'TEMPLATES_FOLDER_ARTICLES', 'TEMPLATES_FOLDER_COMMENTS',
                'TEMPLATES_FOLDER_COMMON', 'GLOBAL_FOLDER',
                'TEMPLATE_NOT_READABLE', 'TEMPLATE_NOT_WRITABLE',
                'GLOBAL_LASTCHANGE', 'GLOBAL_PREVIEW',
                'SAVE_SUCCESS_TEMPLATE',
                'GLOBAL_NOTFOUND_FILE', 'FILE_NOT_WRITABLE'
            ],
            //$uploader->getJsLangVars(),
            $this->editor->getJsLangVars()
        ));

        $this->view->addJsFiles(array_merge(['templates/module2.js'], /*$uploader->getJsFiles(), $this->editor->getJsFiles()*/ ));
        //$this->view->addJsFilesLate($uploader->getJsFilesLate());
        //$this->view->setJsModuleFiles($uploader->getJsModuleFiles());
        $this->view->addDialogs($this->editor->getDialogs());

        //$this->view->setViewVars($uploader->getViewVars());

        $this->view->setFormAction('templates/templates');

        //$this->view->addButtons($buttons);

        $this->initTabs();

        $this->view->render();
    }

    /**
     *
     * @return boolean
     */
    private function initTabs()
    {
        $templatesUrl = \fpcm\classes\tools::getFullControllerLink(
            'ajax/templates/list',
            [
                'list' => 'frontend'
            ]
        );

        $draftsUrl = \fpcm\classes\tools::getFullControllerLink(
            'ajax/templates/list',
            [
                'list' => 'drafts'
            ]
        );

        $tabs[] = (new \fpcm\view\helper\tabItem('tpl-frontend'))
                ->setText('Frontend-Templates')
                ->setUrl($templatesUrl);

        if ($this->permissions->system->drafts) {
            $tabs[] = (new \fpcm\view\helper\tabItem('tpl-drafts'))
                    ->setText('TEMPLATE_HL_DRAFTS')
                    ->setUrl($draftsUrl);
        }

        $this->view->addTabs(
            tabsId: 'fpcm-tabs-templates',
            tabs: $tabs,
            active: $this->getActiveTab()
        );

        return true;
    }

}
