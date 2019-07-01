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
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class filelist extends \fpcm\controller\abstracts\ajaxController {

    use \fpcm\controller\traits\files\lists;

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
     * Request-Handler
     * @return bool
     */
    public function request()
    {
        $this->mode = $this->getRequestVar('mode', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        if ($this->mode === null) {
            $this->mode = 1;
        }

        $this->filter = new \fpcm\model\files\search();

        $filter = $this->getRequestVar('filter');
        if (!is_array($filter) || !count($filter)) {
            $this->showPager = true;
            return true;
        }

        $this->filter->filename      = $filter['filename'];
        $this->filter->combination   = $filter['combination'] ? 'OR' : 'AND';

        if ($filter['datefrom']) {
            $this->filter->datefrom   = strtotime($filter['datefrom']);
        }

        if ($filter['dateto']) {
            $this->filter->dateto     = strtotime($filter['dateto']);
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
     * 
     * @return array
     */
    protected function getPermissions()
    {
        return ['uploads' => 'visible'];
    }
    
    /**
     * Controller-Processing
     */
    public function process()
    {
        $fileList = new \fpcm\model\files\imagelist();

        $page = $this->getRequestVar('page', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        $this->filter->limit = [$this->config->file_list_limit, \fpcm\classes\tools::getPageOffset($page, $this->config->file_list_limit)];
        $list = $fileList->getDatabaseListByCondition($this->filter);
        
        $pagerData = \fpcm\classes\tools::calcPagination(
            $this->config->file_list_limit,
            $page,
            $fileList->getDatabaseCountByCondition($this->filter),
            count($list)
        );

        $list = $this->events->trigger('reloadFileList', $list);

        $userList = new \fpcm\model\users\userList();
        $this->initViewAssigns($list, $userList->getUsersAll(), $pagerData);
        $this->initPermissions();

        $this->view->assign('canRename', $this->permissionsData['permRename']);
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