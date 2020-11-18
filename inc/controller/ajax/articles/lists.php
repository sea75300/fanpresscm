<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * AJAX Article list controller
 * 
 * @package fpcm\controller\ajax\articles\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 4.5
 */
class lists extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\common\searchParams,
        \fpcm\controller\traits\articles\listsCommon,
        \fpcm\controller\traits\articles\lists;
    
    const MODE_ALL = 'all';

    const MODE_ACTIVE = 'active';

    const MODE_ARCHIVE = 'archive';

    /**
     * Search instance
     * @var \fpcm\model\articles\search
     */
    private $conditions;

    /**
     * Current Page
     * @var int
     */
    protected $page = 1;

    /**
     * Current offset
     * @var int
     */
    protected $offset = 0;

    /**
     * View message
     * @var \fpcm\view\message
     */
    protected $message = null;

    /**
     * Is filter view
     * @var bool
     */
    protected $isFilter = false;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        $res = $this->processByParam('getModePerms', 'mode');
        if ($res === self::ERROR_PROCESS_BYPARAMS) {
            return false;
        }
        
        return $res;
    }

    protected function getItemsIds() : array
    {
        if (!count($this->items)) {
            return [];
        }

        $articleIds = array_merge(
            $articleIds,
            array_walk( $this->items, function ($monthData) {
                return array_keys($monthData);
            } )
        );

        return $articleIds;
    }
    
    protected function getModePermsAll() : bool
    {
        return $this->permissions->article->edit || $this->permissions->article->editall;
    }
    
    protected function getModePermsActive() : bool
    {
        return $this->permissions->article->edit;
    }
    
    protected function getModePermsArchive() : bool
    {
        if (!$this->permissions->article->edit && !$this->permissions->article->editall) {
            return false;
        }

        return $this->permissions->article->archive;
    }
    
    protected function getModeConditionsAll() : void
    {
        $this->conditions->orderby = ['createtime DESC'];
    }
    
    protected function getModeConditionsActive() : void
    {
        $this->showArchivedStatus = false;
        $this->conditions->archived = 0;
        $this->conditions->deleted = 0;
        $this->conditions->orderby = ['createtime DESC'];
    }
    
    protected function getModeConditionsArchive() : void
    {
        $this->showArchivedStatus = false;
        $this->showDraftStatus = false;
        $this->conditions->archived = 1;
        $this->conditions->deleted = 0;
        $this->conditions->orderby = ['createtime DESC'];
    }
    
    protected function getFilterConditions() : void
    {
        $filter = $this->request->fromPOST('filter');

        $this->conditions->setMultiple(true);
        
        $this->assignParamsVars( ($filter['combinations'] ?? []) , $this->conditions);

        if (trim($filter['text'])) {

            $filter['text'] = $this->request->filter($filter['text'], [
                \fpcm\model\http\request::FILTER_URLDECODE,
                \fpcm\model\http\request::FILTER_TRIM,
                \fpcm\model\http\request::FILTER_SANITIZE,
                \fpcm\model\http\request::FILTER_HTMLENTITY_DECODE,
                \fpcm\model\http\request::PARAM_SANITIZE => FILTER_SANITIZE_STRING
            ]);

            switch ($filter['searchtype']) {
                case \fpcm\model\articles\search::TYPE_TITLE :
                    $this->conditions->title = $filter['text'];
                    break;
                case \fpcm\model\articles\search::TYPE_CONTENT :
                    $this->conditions->content = $filter['text'];
                    break;
                case \fpcm\model\articles\search::TYPE_COMBINED_OR :
                    $this->conditions->combination   = 'OR';
                    $this->conditions->title = $filter['text'];
                    $this->conditions->content = $filter['text'];
                    break;
                default:
                    $this->conditions->combination   = 'AND';
                    $this->conditions->title = $filter['text'];
                    $this->conditions->content = $filter['text'];
                    break;
            }
        }

        if ($filter['userid'] > 0) {
            $this->conditions->user = (int) $filter['userid'];
        }

        if ($filter['categoryid'] > 0) {
            $this->conditions->category = (int) $filter['categoryid'];
        }

        if ($filter['datefrom'] && \fpcm\classes\tools::validateDateString($filter['datefrom'])) {
            $this->conditions->datefrom = strtotime($filter['datefrom']);
        }

        if ($filter['dateto'] && \fpcm\classes\tools::validateDateString($filter['dateto'])) {
            $this->conditions->dateto = strtotime($filter['dateto']);
        }

        if ($filter['pinned'] > -1) {
            $this->conditions->pinned = (int) $filter['pinned'];
        }

        if ($filter['postponed'] > -1) {
            $this->conditions->postponed = (int) $filter['postponed'];
        }

        if ($filter['comments'] > -1) {
            $this->conditions->comments = (int) $filter['comments'];
        }

        if ($filter['draft'] > -1) {
            $this->conditions->draft = (int) $filter['draft'];
        }

        if ($filter['approval'] > -1) {
            $this->conditions->approval = (int) $filter['approval'];
        }

        switch ($this->request->fromPOST('mode')) {
            case self::MODE_ARCHIVE :
                $this->conditions->combinationDraft = \fpcm\model\articles\search::COMBINATION_AND;
                $this->conditions->combinationArchived = \fpcm\model\articles\search::COMBINATION_AND;
                break;
            case self::MODE_ACTIVE :
                $this->conditions->combinationArchived = \fpcm\model\articles\search::COMBINATION_AND;
                break;
        }

        $this->conditions->combinationDeleted = \fpcm\model\articles\search::COMBINATION_AND;

        $this->conditions = $this->events->trigger('article\prepareSearch', $this->conditions);
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->page = $this->request->getPage();

        $this->initActionObjects();
        $this->relatedCounts = $this->articleList->getRelatedItemsCount($this->getItemsIds());
        
        $this->conditions = new \fpcm\model\articles\search();
        
        $res = $this->processByParam('getModeConditions', 'mode');
        if ($res === self::ERROR_PROCESS_BYPARAMS) {
            $this->response->setReturnData( new \fpcm\view\message($this->language->translate($this->isFilter ? 'SEARCH_ERROR' : 'ARTICLELIST_ERROR'), \fpcm\view\message::TYPE_ERROR) )->fetch();
        }

        $this->isFilter = $this->request->fromPOST('filter') === null ? false : true;
        if ($this->isFilter) {
            $this->getFilterConditions();
        }
        else {
            $this->conditions->limit = [$this->config->articles_acp_limit, \fpcm\classes\tools::getPageOffset($this->page, $this->config->articles_acp_limit)];
        }

        return true;
    }

    public function process()
    {
        $this->count = $this->isFilter ? false : $this->articleList->countArticlesByCondition($this->conditions);
        $this->items = $this->articleList->getArticlesByCondition($this->conditions, true);

        if ($this->items === \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX) {
            $this->items = [];
            $this->count = 0;
            $this->message = new \fpcm\view\message($this->language->translate($this->isFilter ? 'SEARCH_ERROR' : 'ARTICLELIST_ERROR'), \fpcm\view\message::TYPE_ERROR);
        }
        else {
            $this->translateCategories();
        }

        $this->initDataView();

        $this->response->setReturnData(new \fpcm\model\http\responseDataview(
            $this->getDataViewName(),
            $this->dataView->getJsVars()['dataviews'][$this->getDataViewName()],
            $this->message,
            $this->isFilter ? '' : (new \fpcm\view\helper\pager('ajax/articles/lists', $this->page, count($this->items), $this->config->articles_acp_limit, $this->count))
        ))->fetch();
    }

}

?>