<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\comments;

use fpcm\model\comments\comment;
use fpcm\model\ips\ipaddress;

/**
 * Lock IP address from comment
 * 
 * @package fpcm\controller\ajax\articles\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 3.6
 */
class lockIp extends \fpcm\controller\abstracts\ajaxController
{

    /**
     * 
     * @return bool
     */
    public function isAccessible(): bool
    {
        return $this->config->system_comments_enabled && $this->permissions->comment->lockip;
    }
    
    /**
     * Request-Handler
     * @return bool
     */
    public function process()
    {
        $cid = $this->request->fromPOST('cid', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if (!$cid) {
            $this->response->setReturnData(new \fpcm\view\message($this->language->translate('SAVE_FAILED_IPADDRESS'), \fpcm\view\message::TYPE_ERROR))->fetch();
        }

        $comment = new comment($cid);
        if (!$comment->exists()) {
            $this->response->setReturnData(new \fpcm\view\message($this->language->translate('SAVE_FAILED_IPADDRESS'), \fpcm\view\message::TYPE_ERROR))->fetch();
        }

        if ($comment->getIpaddress() === $this->request->getIp()) {
            $this->response->setReturnData(new \fpcm\view\message($this->language->translate('SAVE_FAILED_IPADDRESS_SAME'), \fpcm\view\message::TYPE_ERROR))->fetch();
        }

        $ipAddr = new ipaddress();
        $ipAddr->setIpaddress($comment->getIpaddress());
        $ipAddr->setNocomments(1);
        $ipAddr->setUserid($this->session->getUserId());
        $ipAddr->setIptime(time());

        if (!$ipAddr->save()) {
            $this->getResponse();
        }

        $this->response->setReturnData(new \fpcm\view\message($this->language->translate('SAVE_SUCCESS_IPADDRESS'), \fpcm\view\message::TYPE_NOTICE))->fetch();
    }

}

?>