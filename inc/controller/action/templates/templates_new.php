<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\templates;

/**
 * Template controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class templates_new
extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\requestFunctions
{
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
        return true;
    }

    /**
     * Controller-Processing
     * @return void
     */
    public function process()
    {
        $this->view->addJsVars([
            'templateId' => 1,
            'uploadDest' => 'drafts',
            'filemanagerUrl' => \fpcm\classes\tools::getFullControllerLink('files/list', ['mode' => '']),
            'filemanagerMode' => 2,
            'filemanagerPermissions' => $this->permissions->uploads,
        ]);

        $this->view->addJsLangVars([
                'HL_FILES_MNG', 'ARTICLES_SEARCH', 'FILE_LIST_NEWTHUMBS',
                'GLOBAL_DELETE', 'EDITOR_CATEGORIES_SEARCH',
                'FILE_LIST_UPLOADFORM', 'TEMPLATE_HL_DRAFTS_EDIT',
                'TEMPLATES_FOLDER_ARTICLES', 'TEMPLATES_FOLDER_COMMENTS',
                'TEMPLATES_FOLDER_COMMON', 'GLOBAL_FOLDER',
                'TEMPLATE_NOT_READABLE', 'TEMPLATE_NOT_WRITABLE',
                'GLOBAL_LASTCHANGE', 'GLOBAL_PREVIEW',
                'SAVE_SUCCESS_TEMPLATE', 'FILE_LIST_FILESIZE',
                'GLOBAL_NOTFOUND_FILE', 'FILE_NOT_WRITABLE',
                'TEMPLATES_FOLDER_DRAFTS', 'GLOBAL_NOTFOUND2',
                'GLOBAL_EDIT'
        ]);

        $this->view->addJsFiles(['templates/module2.js']);

        $tabs[] = (new \fpcm\view\helper\tabItem('tpl-frontend'))
                ->setText('HL_OPTIONS_TEMPLATES')
                ->setUrl('ajax/templates/list', ['src' => 'frontend']);

        if ($this->permissions->system->drafts) {
            
            $this->view->addFromComponent(\fpcm\components\components::getFileUploader());
            
            $this->view->includeForms('templates');

            $this->view->addButton(
                (new \fpcm\view\helper\button('fileUpload'))
                    ->setText('FILE_LIST_UPLOADFORM')
                    ->setIcon('upload')
                    ->setData([
                        'bs-toggle' => 'offcanvas',
                        'bs-target' => '#offcanvasUpload'
                    ])
                    ->setAria([
                        'bs-controls' => 'offcanvasUpload',
                    ])
                    ->setPrimary()
            );

            $tabs[] = (new \fpcm\view\helper\tabItem('tpl-drafts'))
                    ->setText('TEMPLATE_HL_DRAFTS')
                    ->setUrl('ajax/templates/list', ['src' => 'drafts']);
        }

        $this->view->addTabs(
            tabsId: 'fpcm-tabs-templates',
            tabs: $tabs,
            active: $this->getActiveTab()
        );

        $this->view->render();
    }

}
