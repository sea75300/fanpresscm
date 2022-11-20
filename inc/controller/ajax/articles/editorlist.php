<?php

namespace fpcm\controller\ajax\articles;

/**
 * Kommentare bzw. Revisionen asynchron laden
 * 
 * @package fpcm\controller\ajax\articles\removeeditortags
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since 3.6
 */
class editorlist extends \fpcm\controller\abstracts\ajaxController
{

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
        $this->response = new \fpcm\model\http\response();
        $this->oid = $this->request->getID();
        $this->module = $this->request->fromGET('view');
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
        if ($this->processByParam('process', 'view') !== true) {
            $this->response->setReturnData([]);
        }

        $this->response->fetch();
    }

    /**
     * 
     * @return bool
     */
    protected function processComments()
    {
        if (!$this->config->system_comments_enabled || !$this->permissions->editComments()) {
            return false;
        }
        
        $this->conditions->articleid = $this->oid;
        $this->conditions->searchtype = 0;

        $this->commentDataView();
        $dvVars = $this->dataView->getJsVars();
        
        $this->response->setReturnData( new \fpcm\model\http\responseDataview( $this->getDataViewName(), $dvVars['dataviews'][$this->getDataViewName()]) );
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processRevisions()
    {
        if (!$this->permissions->article->revisions || !$this->article->exists()) {
            return false;
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
                        '',
                        \fpcm\components\dataView\rowCol::COLTYPE_ELEMENT
                    ),
                ],
                '', false, true
            ));
        }
        else {
            foreach ($revision as $revisionTime => $revisionTitle) {

                $button = (new \fpcm\view\helper\linkButton('rev' . $revisionTime))
                        ->setText('EDITOR_STATUS_REVISION_SHOW')
                        ->setIcon('play')
                        ->setIconOnly()
                        ->setUrl(\fpcm\classes\tools::getControllerLink('articles/revision', [
                            'aid' => $this->article->getId(), 
                            'rid' => $revisionTime
                        ]));

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

        $this->response->setReturnData( new \fpcm\model\http\responseDataview( 'revisionslist', $this->dataView->getJsVars()['dataviews']['revisionslist']) );
        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function processShortlink()
    {
        if (!$this->article->exists()) {
            return false;
        }
        
        $this->response->setReturnData([
            'shortend' => $this->article->getArticleShortLink(),
            'permalink' => \fpcm\classes\baseconfig::canConnect() || (defined('FPCM_ARTICLE_DISABLE_SHORTLINKS') && FPCM_ARTICLE_DISABLE_SHORTLINKS) ? true : false
        ]);

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
