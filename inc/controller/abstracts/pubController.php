<?php
    /**
     * Public base controller
     * 
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\abstracts;

    /**
     * Basis für "public"-Controller
     * 
     * @package fpcm\controller\abstracts\pubController
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @abstract
     */  
    class pubController extends controller {
        
        /**
         * Controller-View
         * @var \fpcm\model\view\pub
         */
        protected $view;

        /**
         * Update-Prüfung
         * @return void
         */
        protected function checkUpdates() {
            return;
        }

        /**
         * Controller-Processing
         * @return boolean
         */
        public function process() {
            $showToolbars = false;
            $permAdd = false;
            $permEditOwn = false;
            $permEditAll = false;
            $currentUserId = false;            
            $isAdmin = false;
            
            if ($this->session->exists()) {                
                $showToolbars   = true;
                $permAdd        = $this->permissions->check(array('article' => 'add'));
                $permEditOwn    = $this->permissions->check(array('article' => 'edit'));
                $permEditAll    = $this->permissions->check(array('article' => 'editall'));
                $currentUserId  = $this->session->getUserId();
                $isAdmin        = $this->session->getCurrentUser()->isAdmin();
            }            
            
            $this->view->assign('showToolbars', $showToolbars);
            $this->view->assign('permAdd', $permAdd);
            $this->view->assign('permEditOwn', $permEditOwn);
            $this->view->assign('permEditAll', $permEditAll);
            $this->view->assign('currentUserId', $currentUserId);
            $this->view->assign('isAdmin', $isAdmin);
            
            return;
        }
    }
?>