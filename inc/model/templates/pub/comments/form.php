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

    use \fpcm\model\templates\pub\traits\comment;

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
        return $this->initCommentVars();
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
        return $this->assignCommentToForm($article, $comment, $captcha, $privacy);
    }

    /**
     * Before render event
     * @return bool
     */
    #[\Override]
    public function onBeforeRender(\Twig\Environment &$twig): bool
    {
        return $this->fromSystemTemplate(
            'comment_form.html.twig', //$this->getConfig()->articles_template_active,
            $this->vars
        );
    }

}
