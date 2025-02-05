<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\action\ips\ip;

/**
 * IP address edit controller
 * @category Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class edit extends base {

    public function request()
    {
        parent::request();
        if (!$this->ipaddress->exists()) {
            return false;
        }

        return true;
    }

    public function process()
    {
        $this->view->addButton( (new \fpcm\view\helper\deleteButton('deleteIp'))->setClickConfirm() );
        $this->view->setFormAction('ips/edit&id='.$this->id);
        return true;
    }

    protected function onDeleteIp()
    {
        if (!$this->checkPageToken()) {
            $this->view->addErrorMessage('CSRF_INVALID');
            return true;
        }

        if ($this->ipaddress->delete()) {
            $this->redirect('ips/list', [
                'deleted' => 1
            ]);
            return true;
        }

        $this->view->addErrorMessage('DELETE_FAILED_IPADDRESS');
        return true;
    }

}
