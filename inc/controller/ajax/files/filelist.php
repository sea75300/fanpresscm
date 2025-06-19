<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller to load files
 *
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class filelist extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\files\lists,
        \fpcm\controller\traits\common\searchParams;

    /**
     * Dateimanager-Modus
     * @var int
     */
    protected $mode = 1;

    /**
     *
     * @var \fpcm\model\files\search
     */
    protected $filter;

    /**
     *
     * @var \fpcm\model\files\search
     */
    protected $showPager = false;

    /**
     *
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->permissions->uploads->visible;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->mode = $this->request->fetchAll('mode', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if (!$this->mode) {
            $this->mode = 1;
        }

        $this->filter = new \fpcm\model\files\search();

        $filter = $this->request->fromPOST('filter');
        if (!is_array($filter) || !count($filter)) {
            $this->showPager = true;
            return true;
        }

        $this->filter->setMultiple(true);
        $this->assignParamsVars( ($filter['combinations'] ?? []) , $this->filter);

        if (trim($filter['filename'])) {
            $this->filter->filename = $this->request->filter($filter['filename'], [
                \fpcm\model\http\request::FILTER_URLDECODE,
                \fpcm\model\http\request::FILTER_TRIM,
                \fpcm\model\http\request::FILTER_HTMLENTITY_DECODE,
                \fpcm\model\http\request::FILTER_HTMLSPECIALCHARS
            ]);
        }

        if ($filter['datefrom'] && \fpcm\classes\tools::validateDateString($filter['datefrom'])) {
            $this->filter->datefrom   = strtotime($filter['datefrom']);
        }

        if ($filter['dateto'] && \fpcm\classes\tools::validateDateString($filter['dateto'])) {
            $this->filter->dateto     = strtotime($filter['dateto']);
        }

        if ($filter['userid']) {
            $this->filter->userid     = (int) $filter['userid'];
        }

        return true;
    }

    /**
     * Get view path for controller
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'filemanager/'.$this->getListView();
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $fileList = new \fpcm\model\files\imagelist();

        $page = $this->request->fromPOST('page', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        $this->filter->limit = $this->showPager ? [$this->config->file_list_limit, \fpcm\classes\tools::getPageOffset($page, $this->config->file_list_limit)] : null;
        $list = $fileList->getDatabaseListByCondition($this->filter);

        if ($list === \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX) {
            $list = [];
            $this->filterError = true;
        }
        
        $max = count($list);

        $pager = new \fpcm\view\helper\pager(
            'ajax/files/lists&mode='.$this->mode,
            $page,
            $max,
            $this->config->file_list_limit,
            $this->showPager ? $fileList->getDatabaseCountByCondition($this->filter) : 1
        );

        $ev = $this->events->trigger('reloadFileList', $list);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event reloadFileList failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $list = $ev->getData();

        $userList = new \fpcm\model\users\userList();
        $this->initViewAssigns($list, $userList->getUsersAll());

        $this->view->assign('is_last', function ($i) {
            return $i % FPCM_FILEMAGER_ITEMS_ROW === 0;
        });
        
        
        $addColsToEnd = FPCM_FILEMAGER_ITEMS_ROW - $max % FPCM_FILEMAGER_ITEMS_ROW;
        
        $this->view->assign('addColsToEnd', $addColsToEnd < FPCM_FILEMAGER_ITEMS_ROW ? $addColsToEnd : 0);
        $this->view->assign('showPager', $this->showPager);
        $this->view->assign('thumbsize', $this->config->file_thumb_size . 'px');
        $this->view->assign('pager', $pager);

        $responseData = new \fpcm\model\http\responseDataHtml(
            $this->view->render(true), [
                'pager' => $pager->getJsVars()
        ]);

        $pager = (string) $pager;
        $pager = null;

        $this->response->setReturnData($responseData)->fetch();
    }

    /**
     *
     * @return string
     */
    private function getListView() : string
    {
        return in_array($this->config->file_view, \fpcm\components\components::getFilemanagerViews()) ? $this->config->file_view : 'cards';
    }

}
