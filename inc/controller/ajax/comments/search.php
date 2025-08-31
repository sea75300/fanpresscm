<?php

/**
 * AJAX comment search controller
 *
 * AJAX controller for article search
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\comments;

/**
 * Kommentar Suche
 *
 * @package fpcm\controller\ajax\comments\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since 3.3
 */
class search extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\comments\lists,
        \fpcm\controller\traits\common\searchParams;

    /**
     * Data view object
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     *
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->config->system_comments_enabled && $this->permissions->editCommentsMass();
    }

    /**
     *
     * @return int
     */
    protected function getMode()
    {
        return 3;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->response = new \fpcm\model\http\response();

        $filter = $this->request->fromPOST('filter');
        if (!is_array($filter) || !count($filter)) {
            return false;
        }

        $sort = $filter['sort'] ?? null;
        
        $fields = count($filter) - 1;
        
        array_push($filter, [ 'field' => 'deleted', 'combination' => $fields > 1 ? 'and' : '', 'value' => 0 ]);

        $this->conditions->setMultiple(true);
        $this->conditions->setFilterParams($filter);

        if ($sort) {
            $this->conditions->prepareOrder($sort['field'], $sort['order']);
        }

        $ev = $this->events->trigger('comments\prepareSearch', $this->conditions);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event comments\prepareSearch failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $this->conditions = $ev->getData();
        return true;
    }

    public function process()
    {
        $this->initDataView();
        $dvVars = $this->dataView->getJsVars();
        $this->response->setReturnData( new \fpcm\model\http\responseDataview( $this->getDataViewName(), $dvVars['dataviews'][$this->getDataViewName()], $this->filterError) )->fetch();
    }

}
