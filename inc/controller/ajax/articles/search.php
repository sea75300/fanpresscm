<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * AJAX article search ontroller
 * 
 * @package fpcm\controller\ajax\articles\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class search extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

    use \fpcm\controller\traits\articles\lists,
        \fpcm\controller\traits\common\searchParams;

    /**
     * Suchmodus
     * @var int
     */
    protected $mode = -1;

    /**
     *
     * @var bool
     */
    protected $deleteActions = false;

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->editArticles() || $this->permissions->article->archive;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->response = new \fpcm\model\http\response;
        
        $this->deleteActions = $this->permissions->article->delete;
        $this->initActionVars();

        $this->mode = $this->request->getIntMode();

        $filter = $this->request->fromPOST('filter');

        $sparams = new \fpcm\model\articles\search();
        $sparams->setMultiple(true);
        
        $this->assignParamsVars( ($filter['combinations'] ?? []) , $sparams);

        if (trim($filter['text'])) {

            $filter['text'] = \fpcm\classes\http::filter($filter['text'], [
                \fpcm\model\http\request::FILTER_HTMLENTITY_DECODE
            ]);

            switch ($filter['searchtype']) {
                case \fpcm\model\articles\search::TYPE_TITLE :
                    $sparams->title = $filter['text'];
                    break;
                case \fpcm\model\articles\search::TYPE_CONTENT :
                    $sparams->content = $filter['text'];
                    break;
                case \fpcm\model\articles\search::TYPE_COMBINED_OR :
                    $sparams->combination   = 'OR';
                    $sparams->title = $filter['text'];
                    $sparams->content = $filter['text'];
                    break;
                default:
                    $sparams->combination   = 'AND';
                    $sparams->title = $filter['text'];
                    $sparams->content = $filter['text'];
                    break;
            }
        }

        if ($filter['userid'] > 0) {
            $sparams->user = (int) $filter['userid'];
        }

        if ($filter['categoryid'] > 0) {
            $sparams->category = (int) $filter['categoryid'];
        }

        if ($filter['datefrom'] && \fpcm\classes\tools::validateDateString($filter['datefrom'])) {
            $sparams->datefrom = strtotime($filter['datefrom']);
        }

        if ($filter['dateto'] && \fpcm\classes\tools::validateDateString($filter['dateto'])) {
            $sparams->dateto = strtotime($filter['dateto']);
        }

        if ($filter['pinned'] > -1) {
            $sparams->pinned = (int) $filter['pinned'];
        }

        if ($filter['postponed'] > -1) {
            $sparams->postponed = (int) $filter['postponed'];
        }

        if ($filter['comments'] > -1) {
            $sparams->comments = (int) $filter['comments'];
        }

        if ($filter['draft'] > -1) {
            $sparams->draft = (int) $filter['draft'];
        }

        if ($filter['approval'] > -1) {
            $sparams->approval = (int) $filter['approval'];
        }

        if ($this->mode != -1) {
            $sparams->archived = (int) $this->mode;
        }

        switch ($this->mode) {
            case 1 :
                $this->showArchivedStatus = false;
                $this->showDraftStatus = false;
                $sparams->combinationDraft = \fpcm\model\articles\search::COMBINATION_AND;
                $sparams->combinationArchived = \fpcm\model\articles\search::COMBINATION_AND;
                break;
            case 0 :
                $sparams->combinationArchived = \fpcm\model\articles\search::COMBINATION_AND;
                $this->showArchivedStatus = false;
                break;
        }

        $sparams->combinationDeleted = \fpcm\model\articles\search::COMBINATION_AND;

        $sparams = $this->events->trigger('article\prepareSearch', $sparams);

        $this->articleItems = $this->articleList->getArticlesByCondition($sparams, true);

        return true;
    }

    public function process()
    {
        $this->translateCategories();
        $this->initDataView();

        $dvVars = $this->dataView->getJsVars();

        $this->response->setReturnData([
            'dataViewVars' => $dvVars['dataviews'][$this->getDataViewName()],
            'dataViewName' => $this->getDataViewName()
        ])->fetch();

    }

}

?>