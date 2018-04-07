<?php

/**
 * Smiley list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\smileys;

class smileylist extends \fpcm\controller\abstracts\controller {

    use \fpcm\controller\traits\common\dataView;
    
    /**
     * Smiley-Liste
     * @var \fpcm\model\files\smileylist
     */
    protected $smileyList;

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['system' => 'smileys'];
    }

    /**
     * 
     * @return string
     */
    protected function getHelpLink()
    {
        return 'HL_OPTIONS_SMILEYS';
    }

    /**
     * 
     * @return boolean
     */
    public function request()
    {
        $this->smileyList = new \fpcm\model\files\smileylist();

        if ($this->getRequestVar('added')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_SMILEY');
        }

        if ($this->buttonClicked('configSave') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $ids = $this->getRequestVar('smileyids', [
            \fpcm\classes\http::FILTER_BASE64DECODE
        ]);
        if ($this->buttonClicked('deleteSmiley') && is_array($ids)) {
            $deleteItems = array_map('unserialize', $ids);
            if ($this->smileyList->deleteSmileys($deleteItems)) {
                $this->view->addNoticeMessage('DELETE_SUCCESS_SMILEYS');
            } else {
                $this->view->addErrorMessage('DELETE_FAILED_SMILEYS');
            }

            $this->cache->cleanup();
        }

        
        return true;
    }

    public function process()
    {        
        $this->items = $this->smileyList->getDatabaseList();
        $this->initDataView();

        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('addSmiley'))->setText('FILE_LIST_SMILEYADD')->setUrl(\fpcm\classes\tools::getFullControllerLink('smileys/add'))->setClass('fpcm-loader')->setIcon('plus'),
            (new \fpcm\view\helper\deleteButton('deleteSmiley'))->setClass('fpcm-ui-button-confirm')
        ]);
        
        $this->view->assign('headline', 'HL_OPTIONS_SMILEYS');
        $this->view->setFormAction('smileys/list');
        $this->view->addJsFiles(['smileys.js']);
        $this->view->render();
    }

    /**
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        return [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize('05')->setAlign('center'),
            (new \fpcm\components\dataView\column('image', ''))->setAlign('center'),
            (new \fpcm\components\dataView\column('filename', 'FILE_LIST_FILENAME'))->setSize(5),
            (new \fpcm\components\dataView\column('code', 'FILE_LIST_SMILEYCODE'))->setSize(4)
        ];
    }

    /**
     * 
     * @return string
     */
    protected function getDataViewName()
    {
        return 'smileys';
    }
    
    /**
     * 
     * @param \fpcm\model\files\smiley $smiley
     * @return \fpcm\components\dataView\row
     */
    protected function initDataViewRow($smiley)
    {
        $chbxdat = base64_encode(serialize([
            $smiley->getFilename(),
            $smiley->getSmileyCode()
        ]));
 
        return new \fpcm\components\dataView\row([
            new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('smileyids[]', 'chbx' . md5($chbxdat) ))->setClass('fpcm-ui-list-checkbox')->setValue($chbxdat), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
            new \fpcm\components\dataView\rowCol('image', $smiley->getImageTag()),
            new \fpcm\components\dataView\rowCol('filename', new \fpcm\view\helper\escape($smiley->getFilename()) ),
            new \fpcm\components\dataView\rowCol('code', new \fpcm\view\helper\escape($smiley->getSmileyCode()) )
        ]);
    }

}

?>
