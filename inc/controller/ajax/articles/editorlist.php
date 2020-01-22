<?php

namespace fpcm\controller\ajax\articles;

/**
 * Kommentare bzw. Revisionen asynchron laden
 * 
 * @package fpcm\controller\ajax\articles\removeeditortags
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.6
 */
class editorlist extends \fpcm\controller\abstracts\ajaxControllerJSON implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\comments\lists,
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

    /**
     * Data view object
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     * 
     * @return bool
     */
    public function request()
    {
        $this->oid = $this->getRequestVar('id', [\fpcm\classes\http::FILTER_CASTINT]);
        $this->module = $this->getRequestVar('view');
        $this->initActionObjects();

        return true;
    }

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->editArticles();
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $fn = 'process' . ucfirst($this->module);
        if (!method_exists($this, $fn) || !$this->oid) {
            $this->returnData = [];
            $this->getSimpleResponse();
        }

        call_user_func([$this, $fn]);
        $this->getSimpleResponse();
    }

    /**
     * 
     * @return bool
     */
    private function processComments()
    {
        if (!$this->config->system_comments_enabled || !$this->permissions->editComments()) {
            $this->returnData = [];
            $this->getSimpleResponse();
        }
        
        $this->conditions->articleid = $this->oid;
        $this->conditions->searchtype = 0;

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
     * @return bool
     */
    private function processRevisions()
    {
        if (!$this->permissions->article->revisions || !$this->article->exists()) {
            $this->returnData = [];
            $this->getSimpleResponse();
        }

        $revision = $this->article->getRevisions();
        $count = $this->article->getRevisionsCount();

        $cols = [
            (new \fpcm\components\dataView\column('select', (new \fpcm\view\helper\checkbox('fpcm-select-all'))->setClass('fpcm-select-all')))->setSize(1)->setAlign('center'),
            (new \fpcm\components\dataView\column('button', ''))->setSize(1),
            (new \fpcm\components\dataView\column('title', 'ARTICLE_LIST_TITLE'))->setSize(8),
            (new \fpcm\components\dataView\column('date', 'EDITOR_REVISION_DATE'))->setSize('auto')
        ];

        $this->dataView = new \fpcm\components\dataView\dataView('revisionslist');
        $this->dataView->addColumns($cols);

        if (!$count) {
            $this->dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol(
                        'title',
                        (new \fpcm\view\helper\icon('list-ul '))->setSize('lg')->setStack(true)->setStack('ban fpcm-ui-important-text')->setStackTop(true).' '.
                        $this->language->translate('GLOBAL_NOTFOUND2'),
                        'fpcm-ui-padding-md-lr fpcm-ui-dataview-align-notfound',
                        \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT
                    ),
                ],
                '', false, true
            ));
        }
        else {
            foreach ($revision as $revisionTime => $revisionTitle) {

                $button = (new \fpcm\view\helper\linkButton('rev' . $revisionTime))->setUrl($this->article->getEditLink() . '&rev=' . $revisionTime)->setText('EDITOR_STATUS_REVISION_SHOW')->setIcon('play')->setIconOnly(true);

                $this->dataView->addRow(
                    new \fpcm\components\dataView\row([
                        new \fpcm\components\dataView\rowCol('select', (new \fpcm\view\helper\checkbox('revisionIds[]', 'chbx' . $revisionTime))->setClass('fpcm-ui-list-checkbox')->setValue($revisionTime), '', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                        new \fpcm\components\dataView\rowCol('button', $button, 'fpcm-ui-dataview-align-center fpcm-ui-font-small', \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT),
                        new \fpcm\components\dataView\rowCol('title', new \fpcm\view\helper\escape(strip_tags($revisionTitle)), 'fpcm-ui-ellipsis'),
                        new \fpcm\components\dataView\rowCol('date', new \fpcm\view\helper\dateText($revisionTime), 'fpcm-ui-ellipsis')
                    ]
                ));
            }
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
     * @return bool
     */
    private function processShortlink()
    {
        if (!$this->article->exists()) {
            $this->returnData = [];
            $this->getSimpleResponse();
        }

        $this->returnData = [
            'shortend' => $this->article->getArticleShortLink(),
            'permalink' => \fpcm\classes\baseconfig::canConnect() || (defined('FPCM_ARTICLE_DISABLE_SHORTLINKS') && FPCM_ARTICLE_DISABLE_SHORTLINKS) ? true : false
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

    /**
     * 
     * @return bool
     */
    protected function initObjectsShortlink()
    {
        return $this->initObjectsRevisions();
    }

}

?>
