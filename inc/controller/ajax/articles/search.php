<?php

/**
 * AJAX article search controller
 * 
 * AJAX controller for article search
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\articles;

/**
 * Artikelsuche
 * 
 * @package fpcm\controller\ajax\articles\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
class search extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\articles\lists;

    /**
     * Suchparameter
     * @var array
     */
    protected $sparams = [];

    /**
     * Suchmodus
     * @var int
     */
    protected $mode = -1;

    /**
     * Get view path for controller
     * @return string
     */
    protected function getViewPath()
    {
        return '';
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
        $this->initActionObjects();
        $this->initActionVars();
        
        $this->mode = $this->getRequestVar('mode', [
            \fpcm\classes\http::FPCM_REQFILTER_CASTINT
        ]);

        $filter = $this->getRequestVar('filter');

        $sparams = new \fpcm\model\articles\search();

        if (trim($filter['text'])) {
            switch ($filter['searchtype']) {
                case 0 :
                    $sparams->title = $filter['text'];
                    break;
                case 1 :
                    $sparams->content = $filter['text'];
                    break;
                default:
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

        if ($filter['datefrom']) {
            $sparams->datefrom = strtotime($filter['datefrom']);
        }

        if ($filter['dateto']) {
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

        if ($this->mode != -1) {
            $sparams->archived = (int) $this->mode;
        }

        $sparams->approval = (int) $filter['approval'];
        $sparams->combination = $filter['combination'] ? 'OR' : 'AND';

        $sparams = $this->events->runEvent('articlesPrepareSearch', $sparams);

        $this->articleItems = $this->articleList->getArticlesByCondition($sparams, true);

        return true;
    }

    public function process()
    {
        $this->translateCategories();
        $this->initDataView();
        
        $dvVars = $this->dataView->getJsVars();
        
        $this->returnData = [
            'dataViewVars' => $dvVars['dataviews'][$this->getDataViewName()],
            'dataViewName' => $this->getDataViewName()
        ];

        $this->getSimpleResponse();
    }

}

?>