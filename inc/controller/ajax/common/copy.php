<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\common;

/**
 * AJAX copy controller
 *
 * @package fpcm\controller\ajax\commom
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2024, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 * @since 5.2.2
 */
class copy extends \fpcm\controller\abstracts\ajaxController
{

    use \fpcm\controller\traits\common\isAccessibleTrue;

    /**
     *
     * @var bool
     */
    private bool $result = false;

    /**
     *
     * @var string
     */
    private string $destination = '';

    /**
     *
     * @var \fpcm\view\message|null
     */
    private ?\fpcm\view\message $message = null;

    /**
     *
     * @var string
     */
    private string $callback = '';

    /**
     * Controller-Processing
     */
    public function process()
    {
        if ($this->request->fromPOST('dest') !== 'system') {
            $this->processModuleEvent();
        }
        elseif ($this->processByParam() !== true) {
            $this->response->setReturnData([
                'message' => $this->message
            ])->fetch();
        }

        $this->response->setReturnData([
            'result' => $this->result,
            'message' => $this->message,
            'destination' => $this->destination,
            'callback' => $this->callback
        ])->fetch();

    }

    /**
     *
     * @return bool
     */
    protected function processArticle() : bool
    {
        if (!$this->permissions->article->add) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $id = $this->request->fromPOST('id');
        if (!$id) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $obj = new \fpcm\model\articles\article($id);
        if (!$obj instanceof \fpcm\model\interfaces\isCopyable || !$obj->exists()) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $newId = $obj->copy();
        if (!$newId) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $this->result = $newId;

        $this->destination = \fpcm\classes\tools::getControllerLink('articles/edit', [
            'id' => $newId,
            'added' => $this->permissions->article->approve ? 2 : 1
        ]);

        return true;
    }

    /**
     *
     * @return bool
     */
    protected function processCategory() : bool
    {
        if (!$this->permissions->system->categories) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $id = $this->request->fromPOST('id');
        if (!$id) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $obj = new \fpcm\model\categories\category($id);
        if (!$obj instanceof \fpcm\model\interfaces\isCopyable || !$obj->exists()) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $newId = $obj->copy();
        if (!$newId) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $this->result = $newId;

        $this->destination = \fpcm\classes\tools::getControllerLink('categories/edit', [
            'id' => $newId
        ]);

        return true;
    }

    /**
     *
     * @return bool
     */
    protected function processFile() : bool
    {
        if (!$this->permissions->uploads->add) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_UPLOAD_COPY'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $fn = $this->request->fromPOST('id', [
            \fpcm\model\http\request::FILTER_BASE64DECODE,
            \fpcm\model\http\request::FILTER_DECRYPT
        ]);

        if (!$fn) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_UPLOAD_COPY'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $obj = new \fpcm\model\files\image($fn);
        if (!$obj instanceof \fpcm\model\interfaces\isCopyable || !$obj->existsFolder()) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_UPLOAD_COPY'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $newId = $obj->copy();
        if (!$newId) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_UPLOAD_COPY'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $this->result = $newId;
        $this->callback = 'filemanager.reloadFiles';
        return true;
    }

    /**
     *
     * @return bool
     */
    protected function processText() : bool
    {
        if (!$this->permissions->system->wordban) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $id = $this->request->fromPOST('id');
        if (!$id) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $obj = new \fpcm\model\wordban\item($id);
        if (!$obj instanceof \fpcm\model\interfaces\isCopyable || !$obj->exists()) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $newId = $obj->copy();
        if (!$newId) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_WORDBAN'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $this->result = $newId;

        $this->destination = \fpcm\classes\tools::getControllerLink('wordban/edit', [
            'id' => $newId
        ]);

        return true;
    }

    /**
     *
     * @return bool
     */
    protected function processRoll() : bool
    {
        if (!$this->permissions->system->rolls) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_ROLL'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $id = $this->request->fromPOST('id');
        if (!$id) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_ROLL'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $obj = new \fpcm\model\users\userRoll($id);
        if (!$obj instanceof \fpcm\model\interfaces\isCopyable || !$obj->exists()) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_ROLL'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $newId = $obj->copy();
        if (!$newId) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_ROLL'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $this->result = $newId;

        $this->destination = \fpcm\classes\tools::getControllerLink('users/editroll', [
            'id' => $newId
        ]);

        return true;
    }

    /**
     *
     * @return void
     * @since 5.2.3-dev
     */
    protected function processModuleEvent(): void
    {
        $res = $this->events->trigger('copyItem', [
            'key' => $this->request->fromPOST('dest'),
            'itemType' => $this->request->fromPOST('fn'),
            'itemId' => $this->request->fromPOST('id')
        ]);

        if (!$res->getSuccessed()) {
            $this->response->setReturnData([
                'message' => new \fpcm\view\message($this->language->translate('AJAX_RESPONSE_ERROR'), \fpcm\view\message::TYPE_ERROR)
            ])->fetch();
        }

        $data = $res->getData();

        if (!isset($data['result'])) {
            trigger_error('Undefined result key "result" in "copyItem" return value!');
            return;
        }

        if (!isset($data['message'])) {
            trigger_error('Undefined result key "message" in "copyItem" return value!');
            return;
        }

        if (!isset($data['destination'])) {
            trigger_error('Undefined result key "destination" in "copyItem" return value!');
            return;
        }

        if (!isset($data['callback'])) {
            trigger_error('Undefined result key "callback" in "copyItem" return value!');
            return;
        }

        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

    }

}
