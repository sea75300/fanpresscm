<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\templates;

/**
 * AJAX save template preview code
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @package fpcm\controller\ajax\system\templates
 * @since 5.2.0-a1
 */
class saveTemplate extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\templates\edit;
    
    protected $tplid;

    protected $content;

    public function request(): bool
    {

        $this->tplid = $this->request->fromPOST('tplid', [
            \fpcm\model\http\request::FILTER_TRIM,
            \fpcm\model\http\request::FILTER_STRIPSLASHES
        ]);

        $this->content = $this->request->fromPOST('content', [
            \fpcm\model\http\request::FILTER_TRIM,
            \fpcm\model\http\request::FILTER_STRIPSLASHES
        ]);

        if (!$this->tplid || !$this->content) {
            $txt = $this->language->translate('SAVE_FAILED_TEMPLATE', [ '{{filename}}' => '1' ]);
            $this->response->setCode(400)->setReturnData(new \fpcm\view\message($txt, \fpcm\view\message::TYPE_ERROR) )->fetch();
            return false;
        }
        
        $fn = 'get'. ucfirst($this->tplid).'Template';
        if (!method_exists($this, $fn)) {
            $txt = $this->language->translate('SAVE_FAILED_TEMPLATE', [ '{{filename}}' => '2' ]);
            $this->response->setCode(400)->setReturnData(new \fpcm\view\message($txt, \fpcm\view\message::TYPE_ERROR) )->fetch();
            return false;
        }

        if (!call_user_func([$this, $fn]) ||
            !$this->prefix || !is_object($this->template) ||
            !$this->template instanceof \fpcm\model\pubtemplates\template) {
            $txt = $this->language->translate('SAVE_FAILED_TEMPLATE', [ '{{filename}}' => '2' ]);
            $this->response->setCode(400)->setReturnData(new \fpcm\view\message($txt, \fpcm\view\message::TYPE_ERROR) )->fetch();
            return false;
        }        
        
        return true;
    }

    
    /**
     * Controller-Processing
     */
    public function process()
    {        
        $this->template->setContent($this->content);
        $res = $this->template->save();

        $isCommentForm = $this->tplid == \fpcm\model\pubtemplates\commentform::TEMPLATE_ID ? true : false;
        if ($res === \fpcm\model\pubtemplates\commentform::SAVE_ERROR_FORMURL && $isCommentForm) {
            $this->response->setReturnData(new \fpcm\view\message($this->language->translate('SAVE_FAILED_TEMPLATE_CF_URLMISSING'), \fpcm\view\message::TYPE_ERROR) )->fetch();
            return false;
        }
        
        if ($this->config->comments_privacy_optin && $res === \fpcm\model\pubtemplates\commentform::SAVE_ERROR_PRIVACY && $isCommentForm) {
            $this->response->setReturnData(new \fpcm\view\message($this->language->translate('SAVE_FAILED_TEMPLATE_CF_PRIVACYMISSING'), \fpcm\view\message::TYPE_ERROR) )->fetch();
            return false;
        }

        if (!$res) {
            $txt = $this->language->translate('SAVE_FAILED_TEMPLATE', [ '{{filename}}' => $this->template->getFilename() ]);
            $this->response->setReturnData(new \fpcm\view\message($txt, \fpcm\view\message::TYPE_ERROR) )->fetch();
            return false;
        }

        $txt = $this->language->translate('SAVE_SUCCESS_TEMPLATE', [ '{{filename}}' => $this->template->getFilename() ]);
        $this->response->setReturnData(new \fpcm\view\message($txt, \fpcm\view\message::TYPE_NOTICE) )->fetch();        
        return true;
    }

}
