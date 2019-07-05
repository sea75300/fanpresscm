<?php

/**
 * IP address edit controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\ips;

class ipbase extends \fpcm\controller\abstracts\controller {

    /**
     * Ip-Adress-Objekt
     * @var int
     */
    protected $id;

    /**
     * Ip-Adress-Objekt
     * @var \fpcm\model\ips\ipaddress
     */
    protected $ipaddress;

    protected function getViewPath() : string
    {
        return 'ips/ipadd';
    }

    protected function getPermissions()
    {
        return ['system' => 'ipaddr'];
    }

    protected function getHelpLink()
    {
        return 'HL_OPTIONS_IPBLOCKING';
    }

    protected function getActiveNavigationElement()
    {
        return 'submenu-itemnav-item-ips';
    }

    public function request()
    {
        $this->id = $this->getRequestVar('id', [
            \fpcm\classes\http::FILTER_CASTINT
        ]);

        $this->ipaddress = new \fpcm\model\ips\ipaddress($this->id);
        $this->save();

        $this->view->assign('object', $this->ipaddress);
        $this->view->setFieldAutofocus('ipSave');
        $this->view->setFormAction('ips/add');
        $this->view->addButton(new \fpcm\view\helper\saveButton('ipSave'));
        $this->view->addJsFiles(['ipadresses.js']);
        return true;
    }
    
    protected function save()
    {
        if (!$this->buttonClicked('ipSave')) {
            return false;
        }
        
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }

        $ipAddr = $this->getRequestVar('ipaddress');
        $this->ipaddress->setIpaddress($ipAddr);
        $this->ipaddress->setIptime(time());
        $this->ipaddress->setUserid($this->session->getUserId());
        $this->ipaddress->setNoaccess($this->getRequestVar('noaccess') ? true : false);
        $this->ipaddress->setNocomments($this->getRequestVar('nocomments') ? true : false);
        $this->ipaddress->setNologin($this->getRequestVar('nologin') ? true : false);
        
        if ($ipAddr === \fpcm\classes\http::getIp()) {
            $this->view->addErrorMessage('SAVE_FAILED_IPADDRESS');
            return false;
        }
        
        $fnName = $this->id ? 'update' : 'save';
        if (!call_user_func([$this->ipaddress, $fnName])) {
            $this->view->addErrorMessage('SAVE_FAILED_IPADDRESS');
            return false;
        }

        $this->redirect('ips/list', [
            'added' => $this->id ? 2 : 1
        ]);

        return true;
    }

}

?>