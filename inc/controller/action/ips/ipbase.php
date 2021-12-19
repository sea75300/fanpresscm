<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\ips;

/**
 * IP address edit controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2019, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
abstract class ipbase extends \fpcm\controller\abstracts\controller
implements \fpcm\controller\interfaces\isAccessible, \fpcm\controller\interfaces\requestFunctions {

    use \fpcm\controller\traits\common\simpleEditForm,
        \fpcm\controller\traits\theme\nav\ips;
    
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

    public function request()
    {
        $this->id = $this->request->getID();

        $this->ipaddress = new \fpcm\model\ips\ipaddress($this->id ? $this->id : null);

        $this->view->addButton(new \fpcm\view\helper\saveButton('ipSave'));
        $this->view->addJsFiles(['ipadresses.js']);

        $this->view->addTabs('ips', [
            (new \fpcm\view\helper\tabItem('ip'))
                ->setText('IPLIST_ADDIP')
                ->setFile($this->getViewPath().'.php')
        ]);

        $this->assignFields([
            (new \fpcm\view\helper\textInput('ipaddress'))
                    ->setValue($this->ipaddress->getIpaddress())
                    ->setText('IPLIST_IPADDRESS')
                    ->setIcon('network-wired')
                    ->setAutoFocused(true),
            new \fpcm\components\fieldGroup([
                (new \fpcm\view\helper\checkbox('nocomments'))->setText('IPLIST_NOCOMMENTS')->setSelected($this->ipaddress->getNocomments())->setSwitch(true),
                (new \fpcm\view\helper\checkbox('nologin'))->setText('IPLIST_NOLOGIN')->setSelected($this->ipaddress->getNologin())->setSwitch(true),
                (new \fpcm\view\helper\checkbox('noaccess'))->setText('IPLIST_NOACCESS')->setSelected($this->ipaddress->getNoaccess())->setSwitch(true),
            ], 'IPLIST_BLOCKTYPE', new \fpcm\view\helper\icon('lock'))
        ]);
        
        return true;
    }
    
    protected function onIpSave()
    {
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return false;
        }

        $ipAddr = $this->request->fromPOST('ipaddress');
        if (!filter_var(str_replace('*', 1, $ipAddr), FILTER_VALIDATE_IP, [ 'flags' => FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 ])) {
            $this->view->addErrorMessage('SAVE_FAILED_IPINVALID');
            return false;
        }

        if ($ipAddr === $this->request->getIp()) {
            $this->view->addErrorMessage('SAVE_FAILED_IPADDRESS_SAME');
            return false;            
        }
        
        $this->ipaddress->setIpaddress($ipAddr);
        $this->ipaddress->setIptime(time());
        $this->ipaddress->setUserid($this->session->getUserId());
        $this->ipaddress->setNoaccess($this->request->fromPOST('noaccess') ? true : false);
        $this->ipaddress->setNocomments($this->request->fromPOST('nocomments') ? true : false);
        $this->ipaddress->setNologin($this->request->fromPOST('nologin') ? true : false);

        if ($ipAddr === $this->request->getIp()) {
            $this->view->addErrorMessage('SAVE_FAILED_IPADDRESS');
            return false;
        }
        
        $fnName = $this->id ? 'update' : 'save';
        if (!call_user_func([$this->ipaddress, $fnName])) {
            $this->view->addErrorMessage('SAVE_FAILED_IPADDRESS');
            return false;
        }

        $this->redirect('ips/list', [
            ($this->id ? 'edited' : 'added') => 1
        ]);

        return true;
    }

}

?>