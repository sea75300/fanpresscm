<?php

/**
 * IP address add controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\ips;

class ipadd extends \fpcm\controller\abstracts\controller {

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
        $this->ipaddress = new \fpcm\model\ips\ipaddress();
        $this->view->setFieldAutofocus('ipSave');
        $this->view->setFormAction('ips/add');
        $this->view->addButton(new \fpcm\view\helper\saveButton('ipSave'));
        $this->view->addJsFiles(['ipadresses.js']);

        if ($this->buttonClicked('ipSave') && !$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        if ($this->buttonClicked('ipSave')) {

            $ipAddr = $this->getRequestVar('ipaddress');

            $this->ipaddress->setIpaddress($ipAddr);
            $this->ipaddress->setIptime(time());
            $this->ipaddress->setUserid($this->session->getUserId());
            $this->ipaddress->setNoaccess($this->getRequestVar('noaccess') ? true : false);
            $this->ipaddress->setNocomments($this->getRequestVar('nocomments') ? true : false);
            $this->ipaddress->setNologin($this->getRequestVar('nologin') ? true : false);

            if ($this->ipaddress->save() && $ipAddr != \fpcm\classes\http::getIp()) {
                $this->redirect('ips/list', array('added' => 1));
                return true;
            }

            $this->view->addErrorMessage('SAVE_FAILED_IPADDRESS');
        }

        return true;
    }

}

?>