<?php

namespace fpcm\controller\ajax\comments;

use fpcm\classes\http;
use fpcm\model\comments\comment;
use fpcm\model\ips\ipaddress;

/**
 * Lock IP address from comment
 * 
 * @package fpcm\controller\ajax\articles\search
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2018, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since FPCM 3.6
 */
class lockIp extends \fpcm\controller\abstracts\ajaxControllerJSON implements \fpcm\controller\interfaces\isAccessible {

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
        $this->returnCode = 0;
        $this->returnData = $this->language->translate('SAVE_FAILED_IPADDRESS');

        $cid = $this->request->fromPOST('cid', [
            http::FILTER_CASTINT
        ]);

        if (!$cid) {
            $this->getResponse();
        }

        $comment = new comment($cid);
        if (!$comment->exists()) {
            $this->getResponse();
        }

        $ipAddr = new ipaddress();
        $ipAddr->setIpaddress($comment->getIpaddress());
        $ipAddr->setNocomments(1);
        $ipAddr->setUserid($this->session->getUserId());
        $ipAddr->setIptime(time());

        if (!$ipAddr->save()) {
            $this->getResponse();
        }

        $this->returnCode = 1;
        $this->returnData = $this->language->translate('SAVE_SUCCESS_IPADDRESS');
        $this->getResponse();
    }

}

?>