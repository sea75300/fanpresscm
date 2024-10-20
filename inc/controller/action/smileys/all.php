<?php

/**
 * Smiley list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\smileys;

class all extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\requestFunctions
{

    use \fpcm\controller\traits\common\dataView,
        \fpcm\controller\traits\theme\nav\smileys;
    
    /**
     * Smiley-Liste
     * @var \fpcm\model\files\smileylist
     */
    protected $smileyList;

    /**
     * 
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     * 
     * @return bool
     */
    public function request()
    {
        $this->smileyList = new \fpcm\model\files\smileylist();

        if ($this->request->hasMessage('saved')) {
            $this->view->addNoticeMessage('SAVE_SUCCESS_SMILEY');
        }
        
        if ($this->request->hasMessage('deleted')) {
            $this->view->addNoticeMessage('DELETE_SUCCESS_SMILEYS');
        }
        
        return true;
    }

    public function process()
    {        
        $this->items = $this->smileyList->getDatabaseList();
        $this->initDataView();

        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('addSmiley'))->setText('GLOBAL_NEW')->setUrl(\fpcm\classes\tools::getFullControllerLink('smileys/add'))->setIcon('plus')->setPrimary(),
            (new \fpcm\view\helper\deleteButton('deleteSmiley'))->setClickConfirm()
        ]);
        
        $this->view->setFormAction('smileys/list');
        $this->view->addJsFiles(['system/smileys.js']);
        $this->view->render();
    }

    protected function getDataViewTabs() : array
    {
        return [
            (new \fpcm\view\helper\tabItem($this->getDataViewName().'-list'))
                ->setText('HL_OPTIONS_SMILEYS')
                ->setFile('components/dataview__inline.php')
        ];
    }

    /**
     * 
     * @return array
     */
    protected function getDataViewCols()
    {
        return [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('buttons', ''))->setSize(1),
            (new \fpcm\components\dataView\column('filename', 'FILE_LIST_FILENAME'))->setSize(3),
            (new \fpcm\components\dataView\column('code', 'FILE_LIST_SMILEYCODE'))->setSize(3),
            (new \fpcm\components\dataView\column('image', ''))->setAlign('center')->setSize(4),
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
            new \fpcm\components\dataView\rowCol('buttons', (new \fpcm\view\helper\editButton('smiley'.$smiley->getId()))->setUrlbyObject($smiley)),
            new \fpcm\components\dataView\rowCol('filename', new \fpcm\view\helper\escape($smiley->getFilename()) ),
            new \fpcm\components\dataView\rowCol('code', new \fpcm\view\helper\escape($smiley->getSmileyCode()) ),
            new \fpcm\components\dataView\rowCol('image', $smiley->getImageTag()),
        ]);
    }
    
    protected function onDeleteSmiley()
    {
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        $ids = $this->request->fromPOST('smileyids', [
            \fpcm\model\http\request::FILTER_BASE64DECODE
        ]);

        if (!is_array($ids)) {
            $this->view->addErrorMessage('DELETE_FAILED_SMILEYS');
            return false;
        }

        $deleteItems = array_map('unserialize', $ids);
        if ($this->smileyList->deleteSmileys($deleteItems)) {
            $this->view->addNoticeMessage('DELETE_SUCCESS_SMILEYS');
            $this->cache->cleanup();
            return true;
        }
        
        $this->view->addErrorMessage('DELETE_FAILED_SMILEYS');
        return false;

    }

}
