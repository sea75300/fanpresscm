<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\templates;

/**
 * Template editor
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

class editor
extends \fpcm\controller\abstracts\controller
{

    use \fpcm\controller\traits\templates\editor;

    /**
     * Parent folder
     * @var string
     */
    private string $parent = '';

    /**
     *
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'templates/editor';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->hash = $this->request->fromGET('hash');
        if (!$this->hash) {
            return false;
        }

        try {
            $this->initFile();            
        } catch (\Exception $e) {
            $this->view->addErrorMessage($e->getMessage());
            return true;
        }        

        return true;
    }

    /**
     * Controller-Processing
     * @return void
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

        $jsFiles = ['templates/editor.js'] + $editor->getJsFiles();

        $this->view->addJsFiles($jsFiles);
        $this->view->setFormAction( 'templates/editor', ['hash' => $this->hash] );
        $this->view->assign('file', $this->file);

        $this->view->addTabs('atedit', [
            (new \fpcm\view\helper\tabItem('art-editor'))
                ->setText('TEMPLATE_EDITOR')
                ->setFile($this->getViewPath())
        ]);

        $this->view->setBodyClass('m-2');

        $this->view->render();
    }
}
