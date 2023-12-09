<?php

/**
 * Smiley add controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\smileys;

abstract class smileybase extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\requestFunctions
{

    use \fpcm\controller\traits\common\simpleEditForm,
        \fpcm\controller\traits\theme\nav\smileys;
    
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

    public function request()
    {
        $this->id = $this->request->getID();
        
        $this->smiley = new \fpcm\model\files\smiley();
        if ($this->id) {
            $this->smiley->setId($this->id);
            $this->smiley->initById();
        }

        if ($this->id && !$this->smiley->exists()) {
            $this->redirect('smileys/list');
            return true;
        }

        return true;
    }

    public function process()
    {
        define('FPCM_VIEW_FLOATING_LABEL_ALL', true);
        
        $smileyList = new \fpcm\model\files\smileylist();

        $files = [];
        foreach ($smileyList->getFolderList() as $file) {

            $fileName = basename($file);

            $files[] = [
                'label' => $fileName,
                'value' => $fileName
            ];
        }

        $this->view->addJsVars([
            'files' => $files,
            'smileypath' => \fpcm\classes\dirs::getDataUrl(\fpcm\classes\dirs::DATA_SMILEYS, '')
        ]);

        $this->view->addJsFiles(['smileys.js']);
        $this->view->addButton(new \fpcm\view\helper\saveButton('saveSmiley'));

        $this->view->addTabs('smileys', [
            (new \fpcm\view\helper\tabItem('smiley'))
                ->setText('FILE_LIST_SMILEY'.$this->getActionText())
                ->setFile($this->getViewPath().'.php')
        ]);
        
        $this->assignFields([
            (new \fpcm\view\helper\textInput('smiley[code]'))
                    ->setValue($this->smiley->getSmileyCode())
                    ->setText('FILE_LIST_SMILEYCODE')
                    ->setIcon('bookmark')
                    ->setAutoFocused(true)
                    ->setRequired()
                    ->setLabelTypeFloat(),
            (new \fpcm\view\helper\textInput('smiley[filename]', 'smileyfilename'))
                    ->setValue($this->smiley->getFilename())
                    ->setText('FILE_LIST_FILENAME')
                    ->setIcon('link')
                    ->setRequired()
                    ->setLabelTypeFloat()
        ]);

        $this->view->render();
    }

    protected function onSaveSmiley() : bool
    {

        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $smileyData = $this->request->fromPOST('smiley');
        if (empty($smileyData['filename']) || !$smileyData['code']) {
            $this->view->addErrorMessage('SAVE_FAILED_SMILEY');
            return true;
        }
        
        $smileyData['filename'] = \fpcm\classes\tools::escapeFileName($smileyData['filename']);
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

    abstract protected function getActionText() : string;

}

?>
