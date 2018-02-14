<?php

/**
 * IP address list controller
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\ips;

class iplist extends \fpcm\controller\abstracts\controller {

    public function getViewPath()
    {
        return 'ips/iplist';
    }

    protected function getPermissions()
    {
        return ['system' => 'ipaddr'];
    }

    protected function getHelpLink()
    {
        return 'hl_options';
    }

    protected function getActiveNavigationElement()
    {
        return 'submenu-itemnav-item-ips';
    }

    /**
     * Request-Handler
     * @return boolean
     */
    public function request()
    {
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
    public function process()
    {
        $userList = new \fpcm\model\users\userList();

        $this->view->assign('ipList', $this->ipList->getIpAll());
        $this->view->assign('users', $userList->getUsersAll());
        $this->view->setFormAction('ips/list');
        $this->view->addButtons([
            (new \fpcm\view\helper\linkButton('addnew'))->setUrl(\fpcm\classes\tools::getFullControllerLink('ips/add'))->setText('IPLIST_ADDIP')->setIcon('unlock')->setClass('fpcm-loader'),
            new \fpcm\view\helper\deleteButton('delete')
        ]);
        $this->view->render();
    }

}

?>
