<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\controller\ajax\pub;

if (!defined('FPCM_NOTOKEN')) {
    define('FPCM_NOTOKEN', true);
}


/**
 * AJAX controlelr to work comments
 *
 * @package fpcm\controller\ajax\pub
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class comments extends \fpcm\controller\abstracts\ajaxController {

    /**
     * Return data
     * @var ?array|\fpcm\view\message
     */
    private $returnData;

    /**
     * Article id
     * @var int
     */
    private int $aid = 0;

    /**
     *
     * @return bool
     */
    public function hasAccess() : bool
    {
        if (!$this->checkReferer(true)) {
            $this->response->setCode(500)->fetch();
        }

        return true;
    }

    /**
     * Request-Handler
     * @return bool
     */
    public function request() : bool
    {
        if (!$this->config->system_comments_enabled ||
             $this->ipList->ipIsLocked() ||
             $this->ipList->ipIsLocked('nocomments')
           ) {

            $this->response->setCode(500)->fetch();
            return false;
        }

        return true;
    }

    /**
     * Controller processing
     * @return void
     */
    public function process()
    {
        $act = $this->request->fromPOST('action');

        if (!$act) {
            $this->response->setCode(500)->fetch();
        }

        $actFn = 'process'.ucfirst($act);
        if (!method_exists($this, $actFn)) {
            $this->response->setCode(500)->fetch();
        }

        $this->aid = $this->request->fromPOST('oid', [
            \fpcm\model\http\request::FILTER_CASTINT
        ]);

        if (!$this->aid || !$this->{$actFn}()) {
            $this->response->setCode(500)->fetch();
        }

        $this->response->setReturnData($this->returnData)->fetch();
    }

    /**
     *
     * @return bool
     */
    protected function processGetList() : bool
    {
        $tpl = new \fpcm\model\pubtemplates\comment($this->config->comments_template_active);

        $conditions = new \fpcm\model\comments\search();
        $conditions->articleid = $this->aid;
        $conditions->approved = $this->session->exists() ? null : 1;
        $conditions->private = $this->session->exists() ? null : 0;
        $conditions->spam = $this->session->exists() ? null : 0;
        $conditions->deleted = 0;

        $comments = (new \fpcm\model\comments\commentList())->getCommentsBySearchCondition($conditions);

        $i = 1;
        foreach ($comments as $comment) {

            $tpl->assignByObject($comment, $i);
            $this->returnData[] = $tpl->parse();

            $i++;
        }

        sleep(2);

        return true;
    }

    /**
     * Save comment
     * @return bool
     */
    protected function processSave() : bool
    {
        $article = new \fpcm\model\articles\article($this->aid);
        if (!$article->exists() || !$article->getComments()) {

            $this->returnData = new \fpcm\view\message(
                $this->language->translate('SAVE_FAILED_COMMENT'),
                \fpcm\view\message::TYPE_ERROR
            );

            return true;
        }

        $data = $this->request->fromPOST('comment');

        $privacy = (bool) ($data['privacy'] ?? false);

        if ($this->config->comments_privacy_optin && !$privacy) {

            $this->returnData = new \fpcm\view\message(
                $this->language->translate('PUBLIC_PRIVACY'),
                \fpcm\view\message::TYPE_ERROR
            );

            return true;
        }

        $timer = time();
        $flood = (new \fpcm\model\comments\commentList())->getLastCommentTimeByIP() + $this->config->comments_flood;

        if ($timer <= $flood) {

            $this->returnData = new \fpcm\view\message(
                $this->language->translate('PUBLIC_FAILED_FLOOD', [
                    '{{seconds}}' => $this->config->comments_flood
                ]),
                \fpcm\view\message::TYPE_ERROR
            );

            return true;
        }

        $captcha = \fpcm\components\components::getChatptchaProvider();
        if (!$captcha->checkAnswer()) {

            $this->returnData = new \fpcm\view\message(
                $this->language->translate('PUBLIC_FAILED_CAPTCHA'),
                \fpcm\view\message::TYPE_ERROR
            );

            return true;
        }

        if (!$data['name']) {

            $this->returnData = new \fpcm\view\message(
                $this->language->translate('PUBLIC_FAILED_NAME'),
                \fpcm\view\message::TYPE_ERROR
            );

            return true;
        }


        $data['email'] = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if ($this->config->comments_email_optional && !$data['email']) {

            $this->returnData = new \fpcm\view\message(
                $this->language->translate('PUBLIC_FAILED_EMAIL'),
                \fpcm\view\message::TYPE_ERROR
            );

            return true;
        }

        $data['website'] = filter_var($data['website'], FILTER_VALIDATE_URL);
        $data['website'] = $data['website'] ?? '';

        $commentObj = new \fpcm\model\comments\comment;
        $commentObj->setName($data['name']);
        $commentObj->setEmail($data['email']);
        $commentObj->setWebsite($data['website']);
        $commentObj->setText(nl2br(strip_tags($data['text'], \fpcm\model\comments\comment::COMMENT_TEXT_HTMLTAGS_CHECK)));
        $commentObj->setPrivate(isset($data['private']));
        $commentObj->setIpaddress($this->request->getIp());
        $commentObj->setApproved($this->config->comments_confirm ? false : true);
        $commentObj->setArticleid($this->aid);
        $commentObj->setCreatetime($timer);
        $commentObj->setSpammer(!$this->session->exists() && $captcha->checkExtras());
        $commentObj->prepareDataSave();

        if (!$commentObj->save()) {

            $this->returnData = new \fpcm\view\message(
                $this->language->translate('SAVE_FAILED_COMMENT'),
                \fpcm\view\message::TYPE_ERROR
            );

            return true;
        }

        $this->returnData = new \fpcm\view\message(
            $this->language->translate('SAVE_SUCCESS_COMMENT'),
            \fpcm\view\message::TYPE_NOTICE
        );

        $text = $this->language->translate('PUBLIC_COMMENT_EMAIL_TEXT', array(
            '{{name}}' => $commentObj->getName(),
            '{{email}}' => $commentObj->getEmail(),
            '{{commenttext}}' => strip_tags($commentObj->getText()),
            '{{articleurl}}' => $article->getElementLink(),
            '{{systemurl}}' => \fpcm\classes\dirs::getRootUrl()
        ));

        $to = [];
        if ($this->config->comments_notify != 1) {
            $to[] = $this->config->system_email;
        }

        if ($this->config->comments_notify > 0 && !$this->session->exists()) {
            $to[] = (new \fpcm\model\users\userList) ->getEmailByUserId($article->getCreateuser());
        }

        if (!count($to) || $this->session->exists()) {
            return true;
        }

        $email = new \fpcm\classes\email(implode(',', array_unique($to)), $this->language->translate('PUBLIC_COMMENT_EMAIL_SUBJECT'), $text);
        $email->submit();
        return true;
    }

}
