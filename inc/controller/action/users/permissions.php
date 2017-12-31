<?php
    /**
     * FanPress CM 3.x
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

namespace fpcm\controller\action\users;

    /**
     * Permission edit controller for single group
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     * @since FPCM 3.6
     */    
    class permissions extends \fpcm\controller\abstracts\controller {

        /**
         *
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         *
         * @var \fpcm\model\system\permissions
         */
        protected $permissionObj;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('system' => 'permissions');
            $this->view = new \fpcm\model\view\acp('permissions', 'users');
            $this->view->setShowHeader(false);
            $this->view->setShowFooter(false);

        }

        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            $rollId = $this->getRequestVar('roll', [
                \fpcm\classes\http::FPCM_REQFILTER_CASTINT
            ]);
            
            $this->view->assign('rollId', $rollId);
            
            $roll = new \fpcm\model\users\userRoll($rollId);
            $this->view->assign('rollname', $this->lang->translate($roll->getRollName()));

            $this->permissionObj = new \fpcm\model\system\permissions($rollId);
            
            $checkPageToken = $this->checkPageToken();
            if ($this->buttonClicked('permissionsSave') && !$checkPageToken) {
                $this->view->addErrorMessage('CSRF_INVALID');
            }
            
            if ($this->buttonClicked('permissionsSave') && !is_null($this->getRequestVar('permissions')) && $checkPageToken) {
                
                $permissionData = $this->getRequestVar('permissions', [
                    \fpcm\classes\http::FPCM_REQFILTER_CASTINT
                ]);

                if ($rollId == 1) {
                    $permissionData['system']['permissions'] = 1;
                }

                $permissionData = array_replace_recursive($this->permissions->getPermissionSet(), $permissionData);
                $this->permissionObj->setPermissionData($permissionData);
                if (!$this->permissionObj->update()) {
                    $this->view->addErrorMessage('SAVE_FAILED_PERMISSIONS');
                    return true;
                }

                $this->view->addNoticeMessage('SAVE_SUCCESS_PERMISSIONS');
            }
            
            return true;
            
        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;

            $this->view->assign('permissions', $this->permissionObj->getPermissionData());            
            $this->view->assign('hideTitle', false);

            $this->view->setViewJsFiles(['permissions.js']);            
            $this->view->render();            
        }
        
        /**
         * Intval auf alle Array-Elemente anwenden
         * @param array $data
         * @return array
         */
        private function intval($data) {
            return array_map('intval', $data);
        }

    }
?>
