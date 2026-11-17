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
        $this->parent = $this->request->fromGET('parent');
        if (!$this->hash || !$this->parent) {
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
        $viewVars->setTagsList([
            '<br>', '<hr>',
            '<div>', '<span>', '<header>', '<article>', '<section>', '<footer>',
            '<script>', '<iframe>', '<label>', '<path>',
            '<input>', '<button>', '<select>', '<option>',
            '<svg>', '<figure>'
        ]);

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
        $this->view->setFormAction('templates/editor', ['hash' => $this->hash]);
        $this->view->assign('file', $this->file);

        $this->view->addTabs('atedit', [
            (new \fpcm\view\helper\tabItem('art-editor'))
                ->setText('TEMPLATE_EDITOR')
                ->setFile($this->getViewPath())
        ]);

        $this->view->setBodyClass('m-2');
        
        $this->getVariablesByTemplate();

        $this->view->render();
    }

    /**
     * Get Variables by called template
     * @return void
     */
    public function getVariablesByTemplate() : void
    {
        if ($this->parent === \fpcm\model\templates\variables::drafts->value) {
            return;
        }

        $filename = $this->file->getFilename();
        
        $tpl = match ($this->parent) {
            \fpcm\model\templates\variables::articles->value => new \fpcm\model\templates\pub\articles\single(),
            \fpcm\model\templates\variables::comments->value => new \fpcm\model\templates\pub\comments\single(),
            default => null
        };        
        
        if ($this->parent === \fpcm\model\templates\variables::comments->value &&
            (
                str_starts_with($filename, \fpcm\model\templates\variables::form->value) ||
                str_ends_with($filename, \fpcm\model\templates\variables::form->value)
            )
        ) {
            $tpl = new \fpcm\model\templates\pub\comments\form();
        }
        elseif (substr($filename, -11) === \fpcm\model\templates\variables::extTplOverview->value) {

            $tpl = match ($this->parent) {
                \fpcm\model\templates\variables::articles->value => new \fpcm\model\templates\pub\articles\overview(),
                \fpcm\model\templates\variables::comments->value => new \fpcm\model\templates\pub\comments\overview(),
                default => null
            };
        }
        
        if (!$tpl instanceof \fpcm\model\templates\twig) {
            return;
        }
        
        $tpl->asExample();
        
        fpcmLogSystem($tpl->getGlobals());
        fpcmLogSystem($tpl->getVariables());

    }
}
