<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub\traits;


/**
 * Twig based comment form frontend template
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
trait comment
{

    const VAR_FORM = 'form';

    /**
     * On init event
     * @return bool
     */
    public function initCommentVars(): bool
    {
        $this->vars[self::VAR_FORM] = [];
        return true;
    }

    /**
     * Assign object data
     * @param \fpcm\model\articles\article $article
     * @param \fpcm\model\comments\comment $comment
     * @param type $captcha
     * @param bool $privacy
     * @return bool
     */
    final public function assignCommentToForm(
        \fpcm\model\articles\article $article,
        \fpcm\model\comments\comment $comment,
        object $captcha,
        bool $privacy = false
    ): bool
    {
        if (!$captcha instanceof \fpcm\model\abstracts\spamCaptcha) {
            throw new Exception('$captcha must be an instance of \fpcm\model\abstracts\spamCaptcha');
        }
        
        $this->vars[self::VAR_FORM] = [
            'formHeadline' => $this->getLanguage()->translate('COMMENTS_PUBLIC_FORMHEADLINE'),
            'submitUrl' => $article->getElementLink(),
            'nameField' => new \fpcm\model\templates\twigField(
                'newcomment[name]',
                $comment->getName()
            ),
            'emailField' => new \fpcm\model\templates\twigField(
                'newcomment[email]', 
                $comment->getEmail()
            ),
            'websiteField' => new \fpcm\model\templates\twigField(
                'newcomment[website]',
                $comment->getWebsite()
            ),
            'textfield' => new \fpcm\model\templates\twigField(
                'newcomment[text]',
                $comment->getText()
            ),
            'tags' => new \fpcm\model\templates\twigField(
                value: \fpcm\model\comments\comment::COMMENT_TEXT_HTMLTAGS_CHECK
            ),
            'spamPlugin' => [
                'text' => $captcha->createPluginText(),
                'field' => $captcha->createPluginInput()
            ],
            'privateCheckbox' => new \fpcm\model\templates\twigField(
                'newcomment[private]',
            ),
            'privacyComfirmation' => new \fpcm\model\templates\twigField(
                'newcomment[privacy]',
                (int) $privacy
            ),
            'submitButton' => new \fpcm\model\templates\twigField(
                'sendComment'
            ),
            'resetButton' => new \fpcm\model\templates\twigField(
                'resetComment'
            )
        ];

        return true;
    }

}
