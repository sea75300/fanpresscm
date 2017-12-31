<?php
    /**
     * File manager controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\files;
    
    class filelist extends \fpcm\controller\abstracts\controller {
        
        use \fpcm\controller\traits\files\lists;
        
        /**
         * Controller-View
         * @var \fpcm\model\view\acp
         */
        protected $view;
        
        /**
         * Dateiliste
         * @var \fpcm\model\files\imagelist
         */
        protected $fileList;
        
        /**
         * Benutzerliste
         * @var \fpcm\model\users\userList
         */
        protected $userList;
        
        /**
         * Modus
         * @var int
         */
        protected $mode = 1;

        public function __construct() {
            parent::__construct(); 
            
            $this->checkPermission = array('uploads' => 'visible');            
            $this->view = new \fpcm\model\view\acp('listouter', 'filemanager');
            
            $this->fileList = new \fpcm\model\files\imagelist();
            $this->userList = new \fpcm\model\users\userList();
        }

        public function request() {
            
            $styleLeftMargin = true;
            if (!is_null($this->getRequestVar('mode'))) {
                $this->mode = (int) $this->getRequestVar('mode');
                
                if ($this->mode > 1) {
                    $this->view->setShowHeader(false);
                    $this->view->setShowFooter(false);
                    $styleLeftMargin = false;
                }
                
            }

            $this->view->assign('styleLeftMargin', $styleLeftMargin);
            
            if (!is_null(\fpcm\classes\http::getFiles())) {
                $uploader = new \fpcm\model\files\fileuploader(\fpcm\classes\http::getFiles());
                $result = $uploader->processUpload($this->session->getUserId());

                if (count($result['success'])) {
                    $this->view->addNoticeMessage('SAVE_SUCCESS_UPLOADPHP', array('{{filenames}}' => implode(', ', $result['success'])));
                }                
                
                if (count($result['error'])) {
                    $this->view->addErrorMessage('SAVE_FAILED_UPLOADPHP', array('{{filenames}}' => implode(', ', $result['error'])));
                }
            }
            
            if ($this->buttonClicked('deleteFiles') && !is_null($this->getRequestVar('filenames'))) {
                
                $fileNames = array_map('base64_decode', $this->getRequestVar('filenames'));
                
                $deletedOk = [];
                $deletedFailed = [];
                foreach ($fileNames as $fileName) {
                    $image = new \fpcm\model\files\image($fileName, '', '', false);
                    
                    if ($image->delete()) {
                        $deletedOk[] = $fileName;
                    } else {
                        $deletedFailed[] = $fileName;
                    }
                }
                
                if (count($deletedOk)) {
                    $this->view->addNoticeMessage('DELETE_SUCCESS_FILES', array('{{filenames}}' => implode(', ', $deletedOk)));                                    
                }
                if (count($deletedFailed)) {
                    $this->view->addErrorMessage('DELETE_FAILED_FILES', array('{{filenames}}' => implode(', ', $deletedFailed)));                    
                }                
            }
            
            if ($this->buttonClicked('createThumbs') && !is_null($this->getRequestVar('filenames'))) {
                $fileNames = array_map('base64_decode', $this->getRequestVar('filenames'));
                
                $success = [];
                $failed  = [];                
                foreach ($fileNames as $fileName) {
                    $image = new \fpcm\model\files\image($fileName, '', '', false);
                    
                    if ($image->createThumbnail()) {
                        $success[] = $fileName;
                    } else {
                        $deletedFailed[] = $fileName;
                    }                    
                }    
                
                if (count($success)) {
                    $this->view->addNoticeMessage('DELETE_SUCCESS_NEWTHUMBS', array('{{filenames}}' => implode(', ', $success)));                                    
                }
                if (count($failed)) {
                    $this->view->addErrorMessage('DELETE_FAILED_NEWTHUMBS', array('{{filenames}}' => implode(', ', $failed)));                    
                }                
            }
            
            if ($this->buttonClicked('renameFiles') && !is_null($this->getRequestVar('filenames') && $this->getRequestVar('newfilename'))) {
                $fileNames = array_map('base64_decode', $this->getRequestVar('filenames'));
                $fileName  = array_shift($fileNames);
                $image     = new \fpcm\model\files\image($fileName, '', '', false);                
                
                $newname   = $this->getRequestVar('newfilename');
                
                if ($image->rename($newname, $this->session->getUserId())) {
                    $this->view->addNoticeMessage('DELETE_SUCCESS_RENAME', array('{{filename1}}' => $fileName, '{{filename2}}' => $newname));
                } else {
                    $this->view->addErrorMessage('DELETE_FAILED_RENAME', array('{{filename1}}' => $fileName, '{{filename2}}' => $newname));
                }
                
                $this->fileList->createFilemanagerThumbs();
            }
            
            return true;            
        }
        
        public function process() {
            if (!parent::process()) return false;

            $this->view->addJsVars([
                'fpcmBaseUrl'           => \fpcm\classes\baseconfig::$rootPath,
                'fpcmFmgrMode'          => $this->mode,
                'fpcmEditorType'        => $this->config->system_editor,
                'fpcmJqUploadInit'      => $this->config->file_uploader_new,
                'fpcmLoadAjax'          => ($this->fileList->getDatabaseFileCount() ? 1 : 0),
                'fpcmCurrentModule'     => $this->getRequestVar('module'),
                'fpcmFilesLastSearch'   => 0
            ]);

            $this->view->addJsLangVars([
                'newNameMsg'        => $this->lang->translate('FILE_LIST_RENAME_NEWNAME'),
                'searchWaitMsg'     => $this->lang->translate('SEARCH_WAITMSG'),
                'searchHeadline'    => $this->lang->translate('ARTICLES_SEARCH'),
                'searchStart'       => $this->lang->translate('ARTICLE_SEARCH_START')
            ]);

            $this->view->assign('searchCombination', array(
                $this->lang->translate('ARTICLE_SEARCH_LOGICAND') => 0,
                $this->lang->translate('ARTICLE_SEARCH_LOGICOR')  => 1
            ));

            $this->view->assign('newUploader', $this->config->file_uploader_new);
            $this->view->assign('jquploadPath', \fpcm\classes\loader::libGetFileUrl('jqupload'));
            $this->view->setViewJsFiles(['filemanager.js', 'fileuploader.js']);
            
            if ($this->config->file_uploader_new) {
                $this->view->assign('actionPath', \fpcm\classes\baseconfig::$rootPath.$this->getControllerLink('ajax/jqupload'));
            } else {
                $this->view->assign('actionPath', \fpcm\classes\baseconfig::$rootPath.$this->getControllerLink('files/list', array('mode' => $this->mode)));
                
                $translInfo = [
                    '{{filecount}}' => ini_get("max_file_uploads"),
                    '{{filesize}}'  => \fpcm\classes\tools::calcSize(\fpcm\classes\baseconfig::uploadFilesizeLimit(true), 0)
                ];
                $this->view->assign('maxFilesInfo', $this->lang->translate('FILE_LIST_PHPMAXINFO', $translInfo));
            }

            $this->view->setHelpLink('hl_files_mng');
            $this->initViewAssigns([], [], \fpcm\classes\tools::calcPagination(1, 1, 0, 0));
            $this->initPermissions();
            $this->view->render();
        }

    }
?>
