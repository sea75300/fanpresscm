<?php

/**
 * FanPress CM 5.x
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
class lists extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\articles\listsCommon,
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

        if (!is_array($filter) || !count($filter)) {
            return;
        }

        $sort = $filter['sort'] ?? null;

        $cond = (count($filter) >= 2 ? \fpcm\model\abstracts\searchWrapper::COMBINATION_STR_AND : '');

        switch ($this->request->fromPOST('mode')) {
            case self::MODE_ARCHIVE :
                $this->conditions->modeArchive = true;
                break;
            case self::MODE_ACTIVE :
                $this->conditions->modeArchive = true;
                break;
        }
        
        $this->conditions->modeDeleted = false;

        $this->conditions->setMultiple();
        $this->conditions->setFilterParams($filter);

        if ($sort) {
            $this->conditions->prepareOrder($sort['field'], $sort['order']);
        }


        $ev = $this->events->trigger('article\prepareSearch', $this->conditions);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event article\prepareSearch failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return;
        }

        $this->conditions = $ev->getData();
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
