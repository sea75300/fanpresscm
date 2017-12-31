<?php
    /**
     * Backup manager controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class backups extends \fpcm\controller\abstracts\controller {
        
        /**
         * Controller-View
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('system' => 'backups');

            $this->view   = new \fpcm\model\view\acp('overview', 'backups');
        }
        
        public function request() {
            
            if (!$this->session->exists()) {
                $this->redirectNoSession();
                return false;
            }
            
            if ($this->getRequestVar('save')) {
                $filePath = base64_decode(str_rot13($this->getRequestVar('save')));
                $file = new \fpcm\model\files\dbbackup(basename($filePath));
                
                if (!$file->exists()) {
                    $this->view = new \fpcm\model\view\error();
                    $this->view->setMessage($this->lang->translate('GLOBAL_NOTFOUND_FILE'));
                    $this->view->render();
                    die();
                }

                header('Content-Description: File Transfer');
                header('Content-Type: '.$file->getMimetype());
                header('Content-Disposition: attachment; filename="'.$file->getFilename().'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: '.$file->getFilesize());
                readfile($file->getFullpath());
                exit;
            }
            
            return true;
        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;

            $folderList = new \fpcm\model\files\backuplist();            
            $files      = $folderList->getFolderList();

            rsort($files);
            
            $this->view->assign('folderList', $files);
            $this->view->setHelpLink('hl_options');
            $this->view->render();
        }
        
    }
?>
