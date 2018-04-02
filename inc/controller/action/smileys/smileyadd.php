<?php

/**
 * Smiley add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\smileys;

class smileyadd extends \fpcm\controller\abstracts\controller {

    /**
     *
     * @var \fpcm\model\files\smiley
     */
    protected $smiley;

    protected function getViewPath()
    {
        return 'smileys/add';
    }

    protected function getPermissions()
    {
        return ['system' => 'smileys'];
    }

    protected function getHelpLink()
    {
        return 'HL_OPTIONS_SMILEYS';
    }

    protected function getActiveNavigationElement()
    {
        return 'submenu-itemnav-item-smileys';
    }

    public function request()
    {

        if ($this->buttonClicked('saveSmiley')) {
            $smileyData = $this->getRequestVar('smiley');

            if (empty($smileyData['filename']) || !$smileyData['code']) {
                $this->view->addErrorMessage('SAVE_FAILED_SMILEY');
                return true;
            }

            $this->smiley = new \fpcm\model\files\smiley($smileyData['filename']);
            $this->smiley->setSmileycode($smileyData['code']);

            if (!$this->smiley->save()) {
                $this->view->addErrorMessage('SAVE_FAILED_SMILEY');
                return true;
            }

            $this->cache->cleanup();
            $this->redirect('smileys/list', array('added' => 1));
        }

        return true;
    }

    public function process()
    {
        if (!is_object($this->smiley)) {
            $this->smiley = new \fpcm\model\files\smiley();
        }

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
        $this->view->setFieldAutofocus('smileycode');
        $this->view->addJsFiles(['smileys.js']);
        $this->view->assign('smiley', $this->smiley);
        $this->view->assign('files', $files);
        $this->view->addButton(new \fpcm\view\helper\saveButton('saveSmiley'));
        $this->view->setFormAction('smileys/add');

        $this->view->render();
    }

}

?>
