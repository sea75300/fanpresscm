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
        \fpcm\model\traits\fileManagerTypes;

    /**
     * Dateimanager-Modus
     * @var int
     */
    protected int $mode = 1;

    /**
     * Dateimanager-Modus
     * @var int
     */
    protected string $type;

    /**
     *
     * @var \fpcm\model\files\search
     */
    protected $filter;

    /**
     *
     * @var bool
     */
    protected $showPager = false;

    /**
     *
     * @var string
     */
    protected $listModeView = '';

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
        
        $this->type = $this->request->fetchAll('type') ?? self::TYPE_IMAGES;

        $this->filter = new \fpcm\model\files\search();

        $filter = $this->request->fromPOST('filter');
        if (!is_array($filter) || !count($filter)) {
            $this->showPager = true;
            return true;
        }

        $this->filter->setMultiple();
        $this->filter->setFilterParams($filter);

        $sort = $filter['sort'] ?? null;
        if ($sort) {
            $this->filter->prepareOrder($sort['field'], $sort['order']);
        }

        return true;
    }

    /**
     * 
     * @return bool
     */
    protected function initActionObjects(): bool
    {
        $this->listModeView = in_array($this->config->file_view, \fpcm\components\components::getFilemanagerViews()) ? $this->config->file_view : 'cards';
        return true;
    }

    /**
     * Get view path for controller
     * @return string
     */
    protected function getViewPath() : string
    {
        return 'filemanager/views/' . $this->listModeView;
    }

    /**
     * Controller-Processing
     */
    public function process()
    {
        $fileList = match ($this->type) {
            self::TYPE_VIDEOS => new \fpcm\model\files\medialist,
            default => new \fpcm\model\files\imagelist,
        };

        $this->view->assign('btnList', in_array($this->type, [self::TYPE_IMAGES, self::TYPE_VIDEOS]) ? $this->type : self::TYPE_IMAGES);
        $this->view->assign('showImages', $this->type === self::TYPE_IMAGES);
        
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

        $this->view->assign('has_reminder', function ($reminders, $id, &$hasRem) {

            $hasRem = 0;

            $rem = $reminders[$id] ?? false;
            if (!$rem) {
                return '';
            }

            $hasRem = $rem->getId();
            if ($rem->getTime() <= time()) {
                return 'info';
            }

            return 'outline-info';
        });

        $addColsToEnd = FPCM_FILEMAGER_ITEMS_ROW - $max % FPCM_FILEMAGER_ITEMS_ROW;

        $this->view->assign('addColsToEnd', $addColsToEnd < FPCM_FILEMAGER_ITEMS_ROW ? $addColsToEnd : 0);
        $this->view->assign('showPager', $this->showPager);
        $this->view->assign('thumbsize', $this->config->file_thumb_size . 'px');
        $this->view->assign('pager', $pager);
        $this->view->assign('reminders', \fpcm\model\reminders\reminders::getInstance()->getRemindersForDatasets(\fpcm\model\files\image::class));
        $this->view->assign('ddModeUp', $this->listModeView !== 'small');
        $this->view->assign('ddLastEnd', $this->listModeView === 'cards');
        $this->view->assign('limit', $this->config->file_list_limit);

        $responseData = new \fpcm\model\http\responseDataHtml(
            $this->view->render(true), [
                'pager' => $pager->getJsVars()
        ]);

        $pager = (string) $pager;
        $pager = null;

        $this->response->setReturnData($responseData)->fetch();
    }

}
