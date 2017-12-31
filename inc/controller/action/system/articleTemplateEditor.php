<?php
    /**
     * Template controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class articleTemplateEditor extends \fpcm\controller\abstracts\controller {
        
        /**
         *
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         *
         * @var \fpcm\model\files\templatefile
         */
        protected $file;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('system' => 'templates');
            $this->view            = new \fpcm\model\view\acp('articeltpleditor', 'templates');

        }
        
        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            if (!$this->getRequestVar('file')) {
                return false;
            }
            
            $this->file = new \fpcm\model\files\templatefile($this->getRequestVar('file', [11,10]), '', \fpcm\model\abstracts\file::FPCM_FILE_LOADCONTENT);

            if (!$this->file->isWritable()) {
                $this->view->addErrorMessage('FILE_NOT_WRITABLE');
                return true;
            }

            $newCode = $this->getRequestVar('templatecode', [7]);
            if ($this->buttonClicked('saveTemplate') && $newCode) {

                $this->file->setContent($newCode);

                if ($this->buttonClicked('saveTemplate') && !$this->checkPageToken()) {
                    $this->view->addErrorMessage('CSRF_INVALID');
                    return true;
                }

                $res = $this->file->save();
                
                if ($res === true) {
                    $this->view->addNoticeMessage('SAVE_SUCCESS_ARTICLETEMPLATE');
                }
                elseif ($res === false) {
                    $this->view->addErrorMessage('SAVE_FAILED_ARTICLETEMPLATE');
                }
            }

            return true;
        }
        
        /**
         * Controller-Processing
         * @return boolean
         */
        public function process() {
            if (!parent::process()) return false;

            $this->view->assign('file', $this->file);
            $this->view->setShowHeader(false);
            $this->view->setShowFooter(false);

            $fileLib = new \fpcm\model\system\fileLib();
            $this->view->setViewCssFiles($fileLib->getCmCssFiles());
            $this->view->setViewJsFiles($fileLib->getCmJsFiles());
            $this->view->setViewJsFiles(['editor_codemirror.js', 'templates_articles.js']);

            $this->view->render();
        }
        
    }
?>
