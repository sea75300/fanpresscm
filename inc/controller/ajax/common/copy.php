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
     * @var string
     */
    private string $message = '';

    /**
     * Controller-Processing
     */
    public function process()
    {
        if ($this->processByParam() !== true) {
            $this->response->setReturnData([])->fetch();
        }

        $this->response->setReturnData([
            'result' => $this->result,
            'message' => $this->message,
            'destination' => $this->destination
        ])->fetch();

    }

    /**
     *
     * @return bool
     */
    protected function processArticle() : bool
    {
        if (!$this->permissions->article->add) {
            return false;
        }

        $id = $this->request->fromPOST('id');
        if (!$id) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_ARTICLE'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $article = new \fpcm\model\articles\article($id);
        if (!$article->exists()) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_ARTICLE'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $newId = $article->copy();
        if (!$newId) {
            $this->message = new \fpcm\view\message($this->language->translate('SAVE_FAILED_ARTICLE'), \fpcm\view\message::TYPE_ERROR);
            return false;
        }

        $this->result = $newId;

        $this->destination = \fpcm\classes\tools::getControllerLink('articles/edit', [
            'id' => $newId,
            'added' => $this->permissions->article->approve ? 2 : 1
        ]);

        return true;
    }

}
