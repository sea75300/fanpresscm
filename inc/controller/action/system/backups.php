<?php
    /**
     * Backup manager controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2018, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\system;
    
    class backups extends \fpcm\controller\abstracts\controller {

        protected function getPermissions()
        {
            return ['system' => 'backups'];
        }

        protected function getViewPath()
        {
            return 'backups/overview';
        }

        public function request() {
            
            if (!$this->session->exists()) {
                $this->redirectNoSession();
                return false;
            }
            
            if ($this->getRequestVar('save')) {
                $filePath = base64_decode(str_rot13($this->getRequestVar('save')));
                $file = new \fpcm\model\files\dbbackup($filePath);
                
                if (!$file->exists()) {
                    $this->view = new \fpcm\view\error();
                    $this->view->setMessage($this->lang->translate('GLOBAL_NOTFOUND_FILE'));
                    $this->view->render();
                    exit();
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
            

            $folderList = new \fpcm\model\files\backuplist();            
            $files      = $folderList->getFolderList();

            rsort($files);
            
            $this->view->assign('folderList', $files);
            $this->view->render();
        }

        protected function getHelpLink()
        {
            return 'hl_options';
        }
        
    }
?>
