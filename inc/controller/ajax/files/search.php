<?php
    /**
     * AJAX inner file list controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
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
         * Controller-View
         * @var \fpcm\model\view\ajax
         */        
        protected $view;
        
        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('article' => 'add', 'article' => 'edit', 'uploads' => 'add');
            
            $this->view = new \fpcm\model\view\ajax('listinner', 'filemanager');
        }
        
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
            $this->view->setExcludeMessages(true);
            $this->view->initAssigns();
            $this->view->render();
        }

    }
?>