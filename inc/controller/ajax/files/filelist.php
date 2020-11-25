<?php

/**
 * FanPress CM 4.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\files;

/**
 * AJAX Controller to load files
 * 
 * @package fpcm\controller\ajax\files
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2020, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class filelist extends \fpcm\controller\abstracts\ajaxController implements \fpcm\controller\interfaces\isAccessible {

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
        $this->mode = $this->request->fromPOST('mode', [
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
                \fpcm\model\http\request::FILTER_SANITIZE,
                \fpcm\model\http\request::FILTER_HTMLENTITY_DECODE,
                \fpcm\model\http\request::PARAM_SANITIZE => FILTER_SANITIZE_STRING
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

        $this->filter->limit = [$this->config->file_list_limit, \fpcm\classes\tools::getPageOffset($page, $this->config->file_list_limit)];
        $list = $fileList->getDatabaseListByCondition($this->filter);

        if ($list === \fpcm\drivers\sqlDriver::CODE_ERROR_SYNTAX) {
            $list = [];
            $this->filterError = true;
        }
        
        $pagerData = \fpcm\classes\tools::calcPagination(
            $this->config->file_list_limit,
            $page,
            $this->showPager ? $fileList->getDatabaseCountByCondition($this->filter) : 0,
            count($list)
        );

        $list = $this->events->trigger('reloadFileList', $list);

        $userList = new \fpcm\model\users\userList();
        $this->initViewAssigns($list, $userList->getUsersAll(), $pagerData);
        $this->initPermissions();

        $this->view->assign('showPager', $this->showPager);
        $this->view->render();
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

?>