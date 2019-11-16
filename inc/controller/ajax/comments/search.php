<?php

/**
 * AJAX comment search controller
 * 
 * AJAX controller for article search
 * 
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\comments;

/**
 * Kommentar Suche
 * 
 * @package fpcm\controller\ajax\comments\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @since FPCM 3.3
 */
class search extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\comments\lists,
        \fpcm\controller\traits\common\searchParams;

    /**
     * Data view object
     * @var \fpcm\components\dataView\dataView
     */
    protected $dataView;

    /**
     * 
     * @return int
     */
    protected function getMode()
    {
        return 3;
    }

    /**
     * @see controller::getViewPath
     * @return string
     */
    protected function getViewPath(): string
    {
        return '';
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->setReturnJson();
        
        $filter = $this->getRequestVar('filter');

        $this->conditions->setMultiple(true);
        $this->assignParamsVars( ($filter['combinations'] ?? []) , $this->conditions);

        $this->conditions->searchtype = (int) $filter['searchtype'];

        if (trim($filter['text'])) {
            $this->conditions->text = \fpcm\classes\http::filter($filter['text'], [
                \fpcm\classes\http::FILTER_HTMLENTITY_DECODE
            ]);
        }

        if ($filter['datefrom']) {
            $this->conditions->datefrom = strtotime($filter['datefrom']);
        }

        if ($filter['dateto']) {
            $this->conditions->dateto = strtotime($filter['dateto']);
        }

        if ($filter['spam'] > -1) {
            $this->conditions->spam = (int) $filter['spam'];
        }

        if ($filter['private'] > -1) {
            $this->conditions->private = (int) $filter['private'];
        }

        if ($filter['approved'] > -1) {
            $this->conditions->approved = (int) $filter['approved'];
        }

        if ($filter['articleId'] > 0) {
            $this->conditions->articleid = (int) $filter['articleId'];
        }

        $this->conditions->combinationDeleted = \fpcm\model\comments\search::COMBINATION_AND;
        $this->conditions = $this->events->trigger('comments\prepareSearch', $this->conditions);

        return true;
    }

    public function process()
    {
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