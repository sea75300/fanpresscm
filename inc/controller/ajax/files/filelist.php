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
    class filelist extends \fpcm\controller\abstracts\ajaxController {
        
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
                $this->mode = (int) $this->getRequestVar('mode');
            }
            
            return true;
        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;
            
            $fileList   = new \fpcm\model\files\imagelist();

            $page       = $this->getRequestVar('page', array(9));
            $list       = $fileList->getDatabaseList(
                $this->config->file_list_limit,
                \fpcm\classes\tools::getPageOffset($page, $this->config->file_list_limit)
            );            

            $pagerData  = \fpcm\classes\tools::calcPagination(
                $this->config->file_list_limit,
                $page,
                $fileList->getDatabaseFileCount(),
                count($list)
            );

            $list = $this->events->runEvent('reloadFileList', $list);

            $userList = new \fpcm\model\users\userList();
            $this->initViewAssigns($list, $userList->getUsersAll(), $pagerData);
            $this->initPermissions();           

            $this->view->setExcludeMessages(true);
            $this->view->initAssigns();
            $this->view->render();
        }

    }
?>