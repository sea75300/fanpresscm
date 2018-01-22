<?php
    /**
     * AJAX inner file list controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\ajax\files;
    
    /**
     * AJAX Controller zum Laden der Dateiliste im Dateimanager
     * 
     * @package fpcm\controller\ajax\files\filelist
     * @author Stefan Seehafer <sea75300@yahoo.de>
     */
    class search extends \fpcm\controller\abstracts\ajaxController {
        
        use \fpcm\controller\traits\files\lists;
        
        /**
         * Dateimanager-Modus
         * @var int
         */
        protected $mode = 1;
        
        /**
         * Array mit zu prüfenden Berchtigungen
         * @var array
         */
        protected $checkPermission = ['article' => 'add', 'article' => 'edit', 'uploads' => 'add'];
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            if (!is_null($this->getRequestVar('mode'))) {
                $this->mode = $this->getRequestVar('mode', [
                    \fpcm\classes\http::FPCM_REQFILTER_CASTINT
                ]);
            }

            return true;
        }

        /**
         * Get view path for controller
         * @return string
         */
        protected function getViewPath() {
            return 'filemanager/listinner';
        }

        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;
            
            $filter     = $this->getRequestVar('filter');
            
            $sparams = new \fpcm\model\files\search();
            $sparams->filename      = $filter['filename'];
            $sparams->combination   = $filter['combination'] ? 'OR' : 'AND';

            if ($filter['datefrom']) {
                $sparams->datefrom   = strtotime($filter['datefrom']);
            }

            if ($filter['dateto']) {
                $sparams->dateto     = strtotime($filter['dateto']);
            }

            $fileList   = new \fpcm\model\files\imagelist();
            $list       = $fileList->getDatabaseListByCondition($sparams);

            $list = $this->events->runEvent('reloadFileList', $list);

            $userList = new \fpcm\model\users\userList();
            $this->initViewAssigns($list, $userList->getUsersAll(), []);
            $this->initPermissions();

            $this->view->assign('showPager', false);
            $this->view->render();
        }

    }
?>