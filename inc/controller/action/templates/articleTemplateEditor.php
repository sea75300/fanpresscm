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

class articleTemplateEditor extends \fpcm\controller\abstracts\controller
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
            return true;
        }

        $newCode = $this->request->fromPOST('templatecode', [
            \fpcm\model\http\request::FILTER_TRIM,
            \fpcm\model\http\request::FILTER_STRIPSLASHES
        ]);

        if ($this->buttonClicked('saveTemplate') && $newCode) {

            $this->file->setContent($newCode);
            if ($this->buttonClicked('saveTemplate') && !$this->checkPageToken()) {
                $this->view->addErrorMessage('CSRF_INVALID');
                return true;
            }

            $res = $this->file->save();

            if ($res === true) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLETEMPLATE');
            } elseif ($res === false) {
                $this->view->addErrorMessage('SAVE_FAILED_ARTICLETEMPLATE');
            }
        }

        return true;
    }

    /**
     * Controller-Processing
     * @return bool
     */
    public function process()
    {
        $this->view->assign('file', $this->file);
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);

        $editor = new \fpcm\components\editor\htmlEditor();
        $this->view->addCssFiles($editor->getCssFiles());

        $jsFiles = $editor->getJsFiles();
        unset($jsFiles[16]);  
  
        $jsFiles[] = 'templates/articles.js';
        $this->view->addJsFiles($jsFiles);
        $this->view->addJsVars($editor->getJsVars());
        $this->view->setFormAction($this->file->getEditLink(),[], true);
        $this->view->setBodyClass('fpcm ui-classic-backdrop');
        $this->view->render();
    }

}

?>
