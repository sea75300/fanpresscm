<?php

/**
 * Template controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\templates;

class articleTemplateEditor extends \fpcm\controller\abstracts\controller {

    /**
     *
     * @var \fpcm\model\files\templatefile
     */
    protected $file;

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
        return 'templates/articeltpleditor';
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        if (!$this->getRequestVar('file')) {
            return false;
        }

        $this->file = new \fpcm\model\files\templatefile(
                $this->getRequestVar('file', [
                    \fpcm\classes\http::FPCM_REQFILTER_URLDECODE,
                    \fpcm\classes\http::FPCM_REQFILTER_DECRYPT
                ])
        );

        $this->file->loadContent();

        if (!$this->file->isWritable()) {
            $this->view->addErrorMessage('FILE_NOT_WRITABLE');
            return true;
        }

        $newCode = $this->getRequestVar('templatecode', [
            \fpcm\classes\http::FPCM_REQFILTER_TRIM,
            \fpcm\classes\http::FPCM_REQFILTER_STRIPSLASHES
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
     * @return boolean
     */
    public function process()
    {
        $this->view->assign('file', $this->file);
        $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);

        $editor = new \fpcm\components\editor\htmlEditor();
        $this->view->addCssFiles($editor->getCssFiles());

        $jsFiles = $editor->getJsFiles();
        unset($jsFiles[16], $jsFiles[18]);        
        $jsFiles[] = 'templates_articles.js';
        $this->view->addJsFiles($jsFiles);
        $this->view->addJsVars($editor->getJsVars());
        $this->view->setFormAction($this->file->getEditLink(),[], true);

        $this->view->render();
    }

}

?>
