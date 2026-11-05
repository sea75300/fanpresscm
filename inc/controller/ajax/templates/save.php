<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\templates;

/**
 * AJAX save template preview code
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.4.0-a1
 */
class save extends \fpcm\controller\abstracts\ajaxController
{
    use \fpcm\controller\traits\templates\editor;

    /**
     *
     * @return bool
     */
    public function request(): bool
    {
        $this->hash = $this->request->fromPOST('hash');
        if (!$this->hash) {
            trigger_error('Cannot save template, not hash value give!');
            $this->response->setCode(403)->fetch();
            return false;
        }

        fpcmLogSystem(__METHOD__);
        
        try {
            $this->initFile();
        } catch (\Exception $e) {

            $msg = new \fpcm\view\message(
                $this->language->translate($e->getMessage()),
                \fpcm\view\message::TYPE_ERROR
            );

            $this->response->setCode(200)->setReturnData(new \fpcm\model\http\responseMessage($msg))->fetch();
            return true;
        }

        return true;
    }


    /**
     * Controller-Processing
     */
    public function process()
    {

        $content = $this->request->fromPOST('content');

        $result = $this->file->save($content);

        if ($result) {

            $msg = new \fpcm\view\message(
                'SAVE_SUCCESS_TEMPLATE',
                \fpcm\view\message::TYPE_NOTICE
            );

            $this->response->setCode(200)->setReturnData(new \fpcm\model\http\responseMessage($msg))->fetch();
            return true;
        }

        $msg = new \fpcm\view\message(
            'SAVE_FAILED_TEMPLATE',
            \fpcm\view\message::TYPE_ERROR
        );

        $this->response->setCode(200)->setReturnData(new \fpcm\model\http\responseMessage($msg))->fetch();



        /*$this->template->setContent($this->content);
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
        $this->response->setReturnData(new \fpcm\view\message($txt, \fpcm\view\message::TYPE_NOTICE) )->fetch();        */
        return true;
    }

}
