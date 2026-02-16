<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\abstracts;

/**
 * AJAX dataview controller
 *
 * @package fpcm\controller\abstracts\ajaxController
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @abstract
 */
abstract class dataViewList extends ajaxController {

    use \fpcm\controller\traits\common\lists;

    /**
     * Pager object
     * @var \fpcm\view\helper\pager
     */
    protected ?\fpcm\view\helper\pager $pager = null;

    /**
     * Conditions object
     * @var \fpcm\model\abstracts\searchWrapper
     */
    protected $conditions;

    /**
     * Filter array
     * @var array
     */
    protected ?array $filter = null;

    /**
     * Items list array
     * @var mixed
     */
    protected mixed $items = [];

    /**
     * Max count of item
     * @var int
     */
    protected int $countMax = 0;

    /**
     * Items per page
     * @var int
     */
    protected int $itemsPerPage = 0;

    /**
     * Current items on page
     * @var int
     */
    protected int $countCurrent = 0;

    /**
     * Is filter view
     * @var bool
     */
    protected bool $isFilter = false;

    /**
     * has pager
     * @var bool
     */
    protected bool $hasPager = true;

    /**
     * Message object
     * @var \fpcm\view\message|null
     */
    protected ?\fpcm\view\message $message = null;

    /**
     * Controller request processing
     * @return bool
     */
    public function request()
    {
        $this->initLists();
        $this->initPageData();

        $this->filter = $this->request->fromPOST('filter');

        $this->isFilter = $this->filter !== null;

        if (!$this->isFilter) {
            return true;
        }

        $this->conditions->setMultiple();
        $this->conditions->setFilterParams($this->filter);

        $sort = $this->filter['sort'] ?? null;
        if ($sort !== null && is_array($sort)) {
            $this->conditions->prepareOrder($sort['field'], $sort['order']);
        }

        return true;
    }

    /**
     * process controller
     * @return bool
     */
    public function process()
    {
        $this->dataView = new \fpcm\components\dataView\dataView($this->getName());
        $this->dataView->addColumns($this->getCols());

        $this->execute();

        if ($this->items === \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX) {
            $this->items = [];
            $this->countMax = 0;
            $this->message = new \fpcm\view\message($this->language->translate($this->isFilter ? 'SEARCH_ERROR' : 'ARTICLELIST_ERROR'), \fpcm\view\message::TYPE_ERROR);
        }

        if (is_array($this->items)) {
            $this->countCurrent = count($this->items);
        }

        if (!$this->countCurrent) {

            $this->dataView->addRow(
                new \fpcm\components\dataView\row([
                    new \fpcm\components\dataView\rowCol(
                        'name',
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
            foreach ($this->items as $index => $item) {
                $rowItem =  $this->getRow($item, $index);
                $this->dataView->addRow($rowItem);
            }
        }


        if (!$this->isFilter && $this->hasPager) {
            $this->pager = new \fpcm\view\helper\pager(
                actionLink: sprintf("ajax/%s/lists", $this->getModul()),
                currentPage: $this->page,
                currentPageItemsCount: $this->countCurrent,
                itemsPerPage: $this->itemsPerPage,
                maxItemCount: $this->countMax
            );
        }

        $this->afterProcess();

        $this->response->setReturnData(new \fpcm\model\http\responseDataview(
            $this->getName(),
            $this->dataView->getJsVars()['dataviews'][$this->getName()],
            $this->message,
            $this->pager
        ))->fetch();

        return true;
    }

    /**
     * Init mail objects
     * @return bool
     */
    final protected function initActionObjects(): bool
    {
        $this->userList = new \fpcm\model\users\userList();

        $searchClass = sprintf("\\fpcm\\model\\%s\\search", $this->getModul());
        $this->conditions = new $searchClass();
        return true;
    }

    /**
     * Dataview columns
     * @return array
     */
    abstract protected function getCols() : array;

    /**
     * Returns list name
     * @return string
     */
    abstract protected function getName() : string;

    /**
     * Get dataview row
     * @param comments $item
     * @param int $cid
     * @return \fpcm\components\dataView\row
     */
    abstract protected function getRow($item, $index) : \fpcm\components\dataView\row;

    /**
     * Execute filter
     * @return bool
     */
    abstract protected function execute() : bool;

    /**
     * Init lists objects
     * @return void
     */
    abstract protected function initLists() : void;

    /**
     * Returns Module name
     * @return string
     */
    abstract protected function getModul() : string;

    /**
     * After process list
     * @return bool
     */
    protected function afterProcess() : bool
    {
        return true;
    }

    /**
     * Init page(r) data
     * @return bool
     */
    private function initPageData() : bool
    {
        $this->itemsPerPage = $this->config->articles_acp_limit;

        $page = $this->request->getPage();
        if ($page !== null) {
            $this->page = $page;
        }

        $this->offset = \fpcm\classes\tools::getPageOffset($this->page, $this->itemsPerPage);
        return true;
    }
}
