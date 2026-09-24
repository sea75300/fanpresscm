<?php

/**
 * FanPress CM 5.x
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */

namespace fpcm\model\templates\pub\articles;


/**
 * Twig based comment form frontend template
 *
 * @author Stefan Seehafer <sea75300@yahoo.de>
 * @copyright (c) 2026, Stefan Seehafer
 * @license http://www.gnu.org/licenses/gpl.txt GPLv3
 */
class form extends \fpcm\model\templates\twig
{

    const VAR_FORM = 'form';

    const VAR_ACTIVE = 'isActive';

    /**
     * vars within this template
     * @var array
     */
    private array $vars = [];

    /**
     * On init event
     * @return bool
     */
    #[\Override]
    public function onInit(): bool
    {
        $this->vars = [
            self::VAR_FORM => [],
            self::VAR_ACTIVE => $this->getConfig()->system_comments_enabled
        ];

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
    final public function assignObjects(
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
                'COMMMENT_AUTHOR',
                'newcomment[name]',
                $comment->getName()
            ),
            'emailField' => new \fpcm\model\templates\twigField(
                'GLOBAL_EMAIL',
                'newcomment[email]', 
                $comment->getEmail()
            ),
            'websiteField' => new \fpcm\model\templates\twigField(
                'COMMMENT_WEBSITE',
                'newcomment[website]',
                $comment->getWebsite()
            ),
            'textfield' => new \fpcm\model\templates\twigField(
                'COMMENTS_PUBLIC_FORMHEADLINE',
                'newcomment[text]',
                $comment->getText()
            ),
            'tags' => new \fpcm\model\templates\twigField(
                label: 'GLOBAL_HTMLTAGS_ALLOWED', 
                value: \fpcm\model\comments\comment::COMMENT_TEXT_HTMLTAGS_CHECK
            ),
            'spamPlugin' => [
                'text' => $captcha->createPluginText(),
                'field' => $captcha->createPluginInput()
            ],
            'privateCheckbox' => new \fpcm\model\templates\twigField(
                'COMMMENT_PRIVATE',
                'newcomment[private]'
            ),
            'privacyComfirmation' => new \fpcm\model\templates\twigField(
                'PUBLIC_PRIVACY',
                'newcomment[privacy]',
                $privacy
            ),
            'submitButton' => new \fpcm\model\templates\twigField(
                'GLOBAL_SUBMIT',
                'sendComment'
            ),
            'resetButton' => new \fpcm\model\templates\twigField(
                'GLOBAL_RESET',
                'resetComment'
            )
        ];

        return true;
    }

    /**
     * Before render event
     * @return bool
     */
    #[\Override]
    public function onBeforeRender(): bool
    {
        return $this->fromSystemTemplate(
            'comment_form.html.twig', //$this->getConfig()->articles_template_active,
            $this->vars
        );
    }

}
