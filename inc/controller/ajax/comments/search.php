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
class search extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

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

        $this->conditions->setMultiple(true);
        $this->assignParamsVars( ($filter['combinations'] ?? []) , $this->conditions);

        $this->conditions->searchtype = (int) $filter['searchtype'];

        if (trim($filter['text'])) {
            $this->conditions->text = $this->request->filter($filter['text'], [
                \fpcm\model\http\request::FILTER_URLDECODE,
                \fpcm\model\http\request::FILTER_TRIM,
                \fpcm\model\http\request::FILTER_SANITIZE,
                \fpcm\model\http\request::FILTER_HTMLENTITY_DECODE,
                \fpcm\model\http\request::PARAM_SANITIZE => FILTER_SANITIZE_STRING
            ]);
        }

        if ($filter['datefrom'] && \fpcm\classes\tools::validateDateString($filter['datefrom'])) {
            $this->conditions->datefrom = strtotime($filter['datefrom']);
        }

        if ($filter['dateto'] && \fpcm\classes\tools::validateDateString($filter['dateto'])) {
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

        $this->response->setReturnData([
            'dataViewVars' => $dvVars['dataviews'][$this->getDataViewName()],
            'dataViewName' => $this->getDataViewName()
        ])->fetch();

    }

}

?>