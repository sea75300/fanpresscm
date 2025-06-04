<?php

/**
 * Public comment form template file object
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2011-2022, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\pubtemplates;

/**
 * Kommentar Form Template Objekt
 *
 * @package fpcm\model\system
 * @author Stefan Seehafer <sea75300@yahoo.de>
 */
final class commentform extends template {

    const TEMPLATE_ID = 'commentForm';
    const SAVE_ERROR_FORMURL = -1001;
    const SAVE_ERROR_PRIVACY = -1002;

    /**
     * Template-Platzhalter
     * @var array
     */
    protected $replacementTags = array(
        '{{formHeadline}}' => '',
        '{{submitUrl}}' => '',
        '{{nameDescription}}' => '',
        '{{nameField}}' => '',
        '{{emailDescription}}' => '',
        '{{emailField}}' => '',
        '{{websiteDescription}}' => '',
        '{{websiteField}}' => '',
        '{{textfield}}' => '',
        '{{smileysDescription}}' => '',
        '{{smileys}}' => '',
        '{{tags}}' => '',
        '{{spampluginQuestion}}' => '',
        '{{spampluginField}}' => '',
        '{{privateCheckbox}}' => '',
        '{{submitButton}}' => '',
        '{{resetButton}}' => '',
        '{{privacyComfirmation}}' => '',
    );

    /**
     * Konstruktor
     * @param string $fileName
     */
    public function __construct($fileName = null)
    {
        if (!$fileName) {
            $fileName = 'comment_form';
        }

        parent::__construct('common' . DIRECTORY_SEPARATOR . $fileName . '.html');
    }

    /**
     * Parst Template-Platzhalter
     * @return bool
     */
    public function parse()
    {
        if (!count($this->replacementTags) || !$this->content) {
            return false;
        }

        $ev = $this->events->trigger('template\parseCommentForm', $this->replacementTags);
        if (!$ev->getSuccessed() || !$ev->getContinue()) {
            trigger_error(sprintf("Event template\parseCommentForm failed. Returned success = %s, continue = %s", $ev->getSuccessed(), $ev->getContinue()));
            return false;
        }

        $this->replacementTags = $ev->getData();

        $tags = array_merge($this->replacementInternal, $this->replacementTags);
        return \fpcm\classes\tools::strReplaceArray($this->content, $tags);
    }

    /**
     * Speichert Template in Dateisystem
     * @return bool
     */
    public function save()
    {
        if (!str_contains($this->content, '{{submitUrl}}')) {
            trigger_error('Unable to update comment form template, {{submitUrl}} replacement is missing!');
            return self::SAVE_ERROR_FORMURL;
        }

        if (!str_contains($this->content, '{{privacyComfirmation}}')) {
            trigger_error('Unable to update comment form template, {{privacyComfirmation}} replacement is missing!');
            return self::SAVE_ERROR_PRIVACY;
        }
        
        return parent::save();
    }

    /**
     * Assigns template variables by object of type @see \fpcm\model\articles\article
     * @param \fpcm\model\articles\article $article
     * @param \fpcm\model\comments\comment $comment
     * @param \fpcm\model\abstracts\spamCaptcha $captcha
     * @return bool
     */
    public function assignByObject(\fpcm\model\articles\article $article, \fpcm\model\comments\comment $comment, $captcha) : bool
    {
        if (!$captcha instanceof \fpcm\model\abstracts\spamCaptcha) {
            trigger_error('$captcha must be an instance of \fpcm\model\abstracts\spamCaptcha');
            return false;
        }

        $this->setReplacementTags([
            '{{formHeadline}}' => $this->language->translate('COMMENTS_PUBLIC_FORMHEADLINE'),
            '{{submitUrl}}' => $article->getElementLink(),
            '{{nameDescription}}' => $this->language->translate('COMMMENT_AUTHOR'),
            '{{nameField}}' => (string) (new \fpcm\view\helper\textInput('newcomment[name]'))->setText('')->setClass('fpcm-pub-textinput')->setValue($comment->getName()),
            '{{emailDescription}}' => $this->language->translate('GLOBAL_EMAIL'),
            '{{emailField}}' => (string) (new \fpcm\view\helper\textInput('newcomment[email]'))->setText('')->setClass('fpcm-pub-textinput')->setValue($comment->getEmail())->setType('email'),
            '{{websiteDescription}}' => $this->language->translate('COMMMENT_WEBSITE'),
            '{{websiteField}}' => (string) (new \fpcm\view\helper\textInput('newcomment[website]'))->setText('')->setClass('fpcm-pub-textinput')->setValue($comment->getWebsite())->setType('url'),
            '{{textfield}}' => (string) (new \fpcm\view\helper\textarea('newcomment[text]', 'newcommenttext'))->setText('')->setClass('fpcm-pub-textarea')->setValue($comment->getText()),
            '{{smileysDescription}}' => $this->language->translate('HL_OPTIONS_SMILEYS'),
            '{{smileys}}' => (new \fpcm\model\files\smileylist())->getSmileysPublic(),
            '{{tags}}' => htmlentities(\fpcm\model\comments\comment::COMMENT_TEXT_HTMLTAGS_FORM),
            '{{spampluginQuestion}}' => $captcha->createPluginText(),
            '{{spampluginField}}' => $captcha->createPluginInput(),
            '{{privateCheckbox}}' => (string) (new \fpcm\view\helper\checkbox('newcomment[private]'))->setClass('fpcm-pub-checkboxinput'),
            '{{privacyComfirmation}}' => (string) (new \fpcm\view\helper\checkbox('newcomment[privacy]'))->setClass('fpcm-pub-checkboxinput'),
            '{{submitButton}}' => (string) (new \fpcm\view\helper\submitButton('sendComment'))->setText('GLOBAL_SUBMIT'),
            '{{resetButton}}' => (string) (new \fpcm\view\helper\resetButton('resetComment'))->setIcon('', '', false)
        ]);

        return true;
    }

}

?>