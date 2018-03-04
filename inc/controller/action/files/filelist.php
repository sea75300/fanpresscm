<?php
    /**
     * File manager controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\files;
    
    class filelist extends \fpcm\controller\abstracts\controller {
        
        use \fpcm\controller\traits\files\lists;

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
        
        public function getViewPath()
        {
            return 'filemanager/listouter';
        }

        protected function getPermissions()
        {
            return ['uploads' => 'visible'];
        }

        public function request() {
            
            $this->fileList = new \fpcm\model\files\imagelist();
            $this->userList = new \fpcm\model\users\userList();

            $styleLeftMargin = true;
            if (!is_null($this->getRequestVar('mode'))) {
                $this->mode = (int) $this->getRequestVar('mode');
                
                if ($this->mode > 1) {
                    $this->view->showHeaderFooter(\fpcm\view\view::INCLUDE_HEADER_SIMPLE);
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
                    $image = new \fpcm\model\files\image($fileName, false);
                    
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
                    $image = new \fpcm\model\files\image($fileName, false);
                    
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
                $image     = new \fpcm\model\files\image($fileName, false);                
                
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

            $this->view->addJsVars([
                'fmgrMode'          => $this->mode,
                'editorType'        => $this->config->system_editor,
                'jqUploadInit'      => $this->config->file_uploader_new ? true : false,
                'fmLoadAjax'        => ($this->fileList->getDatabaseFileCount() ? true : false),
                'currentModule'     => $this->getRequestVar('module'),
                'filesLastSearch'   => 0,
                'checkboxRefresh'   => true
            ]);

            $this->view->addJsLangVars(['FILE_LIST_RENAME_NEWNAME', 'SEARCH_WAITMSG', 'ARTICLES_SEARCH', 'ARTICLE_SEARCH_START', 'FILE_LIST_ADDTOINDEX']);

            $this->view->assign('searchCombination', array(
                $this->lang->translate('ARTICLE_SEARCH_LOGICAND') => 0,
                $this->lang->translate('ARTICLE_SEARCH_LOGICOR')  => 1
            ));

            $this->view->assign('newUploader', $this->config->file_uploader_new);
            $this->view->assign('jquploadPath', \fpcm\classes\dirs::getLibUrl('jqupload/'));
            $this->view->addJsFiles(['filemanager.js', 'fileuploader.js']);
            
            if ($this->config->file_uploader_new) {
                $this->view->assign('actionPath', \fpcm\classes\tools::getFullControllerLink('ajax/jqupload'));
            } else {
                $this->view->assign('actionPath', \fpcm\classes\tools::getFullControllerLink('files/list', array('mode' => $this->mode)));
                
                $translInfo = [
                    '{{filecount}}' => ini_get("max_file_uploads"),
                    '{{filesize}}'  => \fpcm\classes\tools::calcSize(\fpcm\classes\baseconfig::uploadFilesizeLimit(true), 0)
                ];
                $this->view->assign('maxFilesInfo', $this->lang->translate('FILE_LIST_PHPMAXINFO', $translInfo));
            }

            $this->initViewAssigns([], [], \fpcm\classes\tools::calcPagination(1, 1, 0, 0));
            $this->initPermissions();

            $this->view->addButtons([
                (new \fpcm\view\helper\checkbox('fpcm-select-all')),
                (new \fpcm\view\helper\button('opensearch', 'opensearch'))->setText('ARTICLES_SEARCH')->setIcon('search')->setIconOnly(true)
            ]);
            
            if ($this->permissionsData['permRename']) {
                $this->view->addButton((new \fpcm\view\helper\submitButton('renameFiles'))->setText('FILE_LIST_RENAME')->setIcon('pencil-square')->setIconOnly(true) );
            }

            if ($this->permissionsData['permThumbs']) {
                $this->view->addButton((new \fpcm\view\helper\submitButton('createThumbs'))->setText('FILE_LIST_NEWTHUMBS')->setIcon('file-image-o')->setIconOnly(true) );
            }

            if ($this->permissionsData['permDelete']) {
                $this->view->addButton((new \fpcm\view\helper\deleteButton('deleteFiles'))->setClass('fpcm-ui-button-confirm'));
            }

            $this->view->setFormAction('files/list', ['mode' => $this->mode]);
            $this->view->render();
        }

        protected function getHelpLink()
        {
            return 'hl_files_mng';
        }

    }
?>
