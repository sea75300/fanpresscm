<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\captchas;

/**
 * Default captcha plugin
 *
 * @package fpcm\model\captchas
 * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
 * @copyright (c) 2011-2025, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class fpcmDefault extends \fpcm\model\abstracts\spamCaptcha {

    /**
     * maximale Anzahl an Links, bevor Kommentar als Spam markiert wird
     * @var int
     */
    private $maxCommentTextLinks = 5;

    /**
     * Captcha-Antwort prüfen
     * @return bool
     */
    public function checkAnswer() : bool
    {
        if ($this->session->exists()) {
            return true;
        }

        $req = \fpcm\model\http\request::getInstance();
        $cval = $req->fromPOST('commentCaptcha');

        if (!$cval) {
            return false;
        }

        $cvalHash = \fpcm\classes\tools::getHash($cval);
        $savedHash = \fpcm\classes\tools::getHash($this->config->comments_antispam_answer);

        return hash_equals($savedHash, $cvalHash);
    }

    /**
     * zusätzliche Prüfungen durchführen
     * @return bool
     */
    public function checkExtras() : bool
    {
        $req = \fpcm\model\http\request::getInstance();

        $cdata = $req->fromPOST('comment');
        if ($this->maxCommentTextLinks <= preg_match_all("#(https?)://\S+[^\s.,>)\];'\"!?]#", $cdata['text'])) {
            return true;
        }

        $comment = new \fpcm\model\comments\comment();
        $commentList = new \fpcm\model\comments\commentList();

        $comment->setEmail($cdata['email']);
        $comment->setName($cdata['name']);
        $comment->setWebsite($cdata['website']);
        $comment->setIpaddress($req->getIp());

        if ($commentList->spamExistsbyCommentData($comment)) {
            return true;
        }

        return false;
    }

    /**
     * Create input field for Captcha
     * @param bool $wrap
     * @return string
     * @since 4.3
     */
    public function createPluginTextInput(bool $float = false)
    {
        if ($this->session->exists()) {
            return '';
        }

        $obj = (new \fpcm\view\helper\textInput('commentCaptcha'))
            ->setClass('fpcm-pub-textinput')
            ->setAutocomplete(false)
            ->setText($this->config->comments_antispam_question);

        if ($float) {
            $obj->setLabelTypeFloat();
            $obj->setPlaceholder($this->config->comments_antispam_question);
        }

        return (string) $obj;
    }

    /**
     * Create input field for Captcha
     * @param bool $wrap
     * @return string
     */
    public function createPluginInput($wrap = false) : string
    {
        if ($this->session->exists()) {
            return '';
        }

        return (string) (new \fpcm\view\helper\textInput('commentCaptcha'))->setClass('fpcm-pub-textinput')->setAutocomplete(false)->setText('');
    }

    /**
     * Ausgabe des Captcha-Textes
     * @return string
     */
    public function createPluginText() : string
    {
        if ($this->session->exists()) {
            return '';
        }

        return $this->config->comments_antispam_question;
    }

}
