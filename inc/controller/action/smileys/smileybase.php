<?php

/**
 * Smiley add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\smileys;

class smileybase extends \fpcm\controller\abstracts\controller {

    /**
     *
     * @var \fpcm\model\files\smiley
     */
    protected $smiley;

    /**
     *
     * @var int
     */
    protected $id;

    final protected function getViewPath() : string
    {
        return 'smileys/editor';
    }

    final protected function getPermissions()
    {
        return ['system' => 'smileys'];
    }

    final protected function getHelpLink()
    {
        return 'HL_OPTIONS_SMILEYS';
    }

    final protected function getActiveNavigationElement()
    {
        return 'submenu-itemnav-item-smileys';
    }

    public function request()
    {
        $this->id = \fpcm\classes\http::get('id', [\fpcm\classes\http::FILTER_CASTINT]);
        
        $this->smiley = new \fpcm\model\files\smiley();
        if ($this->id) {
            $this->smiley->setId($this->id);
            $this->smiley->initById();
        }

        if ($this->id && !$this->smiley->exists()) {
            $this->redirect('smileys/list');
            return true;
        }

        $this->save();
        return true;
    }

    public function process()
    {
        $smileyList = new \fpcm\model\files\smileylist();

        $files = [];
        foreach ($smileyList->getFolderList() as $file) {

            $fileName = basename($file);
            $url = \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_SMILEYS, $fileName);

            $files[] = [
                'label' => $url,
                'value' => $fileName
            ];
        }

        $this->view->addJsVars(['files' => $files]);
        $this->view->addJsFiles(['smileys.js']);
        $this->view->assign('smiley', $this->smiley);
        $this->view->addButton(new \fpcm\view\helper\saveButton('saveSmiley'));
        $this->view->render();
    }

    final protected function save() : bool
    {
        if (!$this->buttonClicked('saveSmiley')) {
            return false;
        }

        $smileyData = $this->getRequestVar('smiley');
        if (empty($smileyData['filename']) || !$smileyData['code']) {
            $this->view->addErrorMessage('SAVE_FAILED_SMILEY');
            return true;
        }
        
        if (!$this->smiley instanceof \fpcm\model\files\smiley) {
            $this->smiley = new \fpcm\model\files\smiley($smileyData['filename']);
        }
        else {
            $this->smiley->setFilename($smileyData['filename']);
        }
        
        $this->smiley->setSmileycode($smileyData['code']);
        
        $fn = $this->smiley->getId() ? 'update' : 'save';
        if (!call_user_func([$this->smiley, $fn])) {
            $this->view->addErrorMessage('SAVE_FAILED_SMILEY');
            return false;
        }
        
        $this->redirect('smileys/list', ['saved' => 1]);
        return true;
    }

}

?>
