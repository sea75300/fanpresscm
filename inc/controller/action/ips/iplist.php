<?php
    /**
     * IP address list controller
     * @author Stefan Seehafer <sea75300@yahoo.de>
     * @copyright (c) 2011-2017, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
    namespace fpcm\controller\action\ips;
    
    class iplist extends \fpcm\controller\abstracts\controller {
        
        /**
         * Controller-View
         * @var \fpcm\model\view\acp
         */
        protected $view;

        /**
         * IP-Liste
         * @var \fpcm\model\ips\iplist
         */
        protected $ipList;

        /**
         * Konstruktor
         */
        public function __construct() {
            parent::__construct();
            
            $this->checkPermission = array('system' => 'ipaddr');

            $this->view = new \fpcm\model\view\acp('iplist', 'ips');
            
            $this->ipList = new \fpcm\model\ips\iplist();
        }

        /**
         * Request-Handler
         * @return boolean
         */
        public function request() {

            if ($this->getRequestVar('added') == 1) {
                $this->view->addNoticeMessage('SAVE_SUCCESS_IPADDRESS');
            }
            
            if ($this->buttonClicked('delete') && !$this->checkPageToken()) {
                $this->view->addErrorMessage('CSRF_INVALID');
                return true;
            }
            
            if ($this->buttonClicked('delete') && !is_null($this->getRequestVar('ipids'))) {
                
                $ids = array_map('intval', $this->getRequestVar('ipids'));
                
                if ($this->ipList->deleteIpAdresses($ids)) {
                    $this->view->addNoticeMessage('DELETE_SUCCESS_IPADDRESS');
                } else {
                    $this->view->addErrorMessage('DELETE_FAILED_IPADDRESS');
                }
            }
            
            return true;
            
        }
        
        /**
         * Controller-Processing
         */
        public function process() {
            if (!parent::process()) return false;
            
            $userList = new \fpcm\model\users\userList();
            
            $this->view->assign('ipList', $this->ipList->getIpAll());
            $this->view->assign('users', $userList->getUsersAll());

            $this->view->setHelpLink('hl_options');
            $this->view->render();            
        }

    }
?>
