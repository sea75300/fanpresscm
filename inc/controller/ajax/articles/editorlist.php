<?php

namespace fpcm\controller\ajax\articles;

/**
 * Kommentare bzw. Revisionen asynchron laden
 * 
 * @package fpcm\controller\ajax\articles\removeeditortags
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.6
 */
class editorlist extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\comments\lists,
        \fpcm\controller\traits\common\dataView,
        \fpcm\model\articles\permissions;

    /**
     *
     * @var int
     */
    private $oid;

    /**
     *
     * @var string
     */
    private $module;

    /**
     *
     * @var \fpcm\model\articles\article
     */
    private $article;

    public function request()
    {
        $this->oid      = $this->getRequestVar('id', [\fpcm\classes\http::FPCM_REQFILTER_CASTINT]);
        $this->module   = $this->getRequestVar('view');
        $this->initActionObjects();

        return true;
    }

    /**
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['article' => 'edit'];
    }

    /**
     * 
     * @return string
     */
    protected function getViewPath()
    {
        return '';
    }
    
    /**
     * Controller-Processing
     */
    public function process()
    {
        $fn = 'process' . ucfirst($this->module);
        if (!method_exists($this, $fn) || !$this->oid) {
            exit;
        }

        call_user_func([$this, $fn]);
        $this->getSimpleResponse();
    }

    /**
     * 
     * @return boolean
     */
    private function processComments()
    {        
        $this->conditions->articleid    = $this->oid;
        $this->conditions->searchtype   = 0;

        $this->commentDataView();
        $dvVars = $this->dataView->getJsVars();
        $this->returnData = [
            'dataViewVars' => $dvVars['dataviews'][$this->getDataViewName()],
            'dataViewName' => $this->getDataViewName()
        ];

        return true;
    }

    /**
     * 
     * @return boolean
     */
    private function processRevisions()
    {
        if (!$this->article->exists()) {
            exit;
        }

        $revision = $this->article->getRevisions();
        $count    = $this->article->getRevisionsCount();

        $cols = [(new \fpcm\components\dataView\column('title', 'ARTICLE_LIST_TITLE'))->setSize(12)];

        if ($count) {
            $cols = [
                (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize('05')->setAlign('center'),
                (new \fpcm\components\dataView\column('button', ''))->setSize(1),
                (new \fpcm\components\dataView\column('title', 'ARTICLE_LIST_TITLE'))->setSize(5),
                (new \fpcm\components\dataView\column('date', 'EDITOR_REVISION_DATE'))->setSize(5)
            ];
        }
        
        $this->dataView = new \fpcm\components\dataView\dataView('revisionslist');
        $this->dataView->addColumns($cols);

        foreach ($revision as $revisionTime => $revisionTitle) {
            
            $button = (new \fpcm\view\helper\linkButton('rev'.$revisionTime))->setUrl($this->article->getEditLink().'&rev='.$revisionTime)->setText('EDITOR_STATUS_REVISION_SHOW')->setIcon('play')->setIconOnly(true);

            $this->dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol('select', (string) (new \fpcm\view\helper\checkbox('revisionIds[]', 'chbx' . $revisionTime))->setClass('fpcm-ui-list-checkbox')->setValue($revisionTime), 'fpcm-ui-dataview-lineheight4', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    new \fpcm\components\dataView\rowCol('button', (string) $button, 'fpcm-ui-dataview-align-center fpcm-ui-font-small', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                    new \fpcm\components\dataView\rowCol('title', (string) new \fpcm\view\helper\escape(strip_tags($revisionTitle)), 'fpcm-ui-ellipsis'),
                    new \fpcm\components\dataView\rowCol('date',  (string) new \fpcm\view\helper\dateText($revisionTime), 'fpcm-ui-ellipsis')
                ]
            ));
            
        }

        $dvVars = $this->dataView->getJsVars();
        $this->returnData = [
            'dataViewVars' => $dvVars['dataviews']['revisionslist'],
            'dataViewName' => 'revisionslist'
        ];
        
        return true;

    }

    /**
     * 
     * @return int
     */
    protected function getMode()
    {
        return 2;
    }

    /**
     * 
     * @return bool
     */
    protected function initActionObjects()
    {
        if (!$this->module || !$this->oid) {
            return true;
        }

        $fn = 'initObjects' . ucfirst($this->module);   
        if (!method_exists($this, $fn) || !$this->oid) {
            exit;
        }

        return call_user_func([$this, $fn]);        
    }


    /**
     * 
     * @return bool
     */
    protected function initObjectsComments()
    {
        return $this->commentObjects();
    }

    /**
     * 
     * @return bool
     */
    protected function initObjectsRevisions()
    {
        $this->article = new \fpcm\model\articles\article($this->oid);
        return true;
    }

}

?>